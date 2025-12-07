<?php

namespace App\Services\Admin;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Jobs\SendTicketToWebServiceJob;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketWorkflowService
{
    public function approveByAdmin1(Ticket $ticket, Authenticatable $admin, ?string $message = null): Ticket
    {
        $this->validateAdminRole($admin, UserRole::ADMIN1);
        $this->validateTicketStatus($ticket, TicketStatus::SUBMITTED);

        return DB::transaction(function () use ($ticket) {
            $oldStatus = $ticket->status;
            $newStatus = $ticket->status->nextStatusForApprove();

            if ($newStatus === null) {
                throw new \InvalidArgumentException(__('Cannot approve ticket with current status.'));
            }

            $ticket->update(['status' => $newStatus]);

            return $ticket->fresh();
        });
    }

    public function rejectByAdmin1(Ticket $ticket, Authenticatable $admin, string $message): Ticket
    {
        $this->validateAdminRole($admin, UserRole::ADMIN1);
        $this->validateTicketStatus($ticket, TicketStatus::SUBMITTED);

        return DB::transaction(function () use ($ticket) {
            $oldStatus = $ticket->status;
            $newStatus = $ticket->status->nextStatusForReject();

            if ($newStatus === null) {
                throw new \InvalidArgumentException(__('Cannot reject ticket with current status.'));
            }

            $ticket->update(['status' => $newStatus]);

            return $ticket->fresh();
        });
    }

    public function approveByAdmin2(Ticket $ticket, Authenticatable $admin, ?string $message = null): Ticket
    {
        $this->validateAdminRole($admin, UserRole::ADMIN2);
        $this->validateTicketStatus($ticket, TicketStatus::APPROVED_BY_ADMIN1);

        return DB::transaction(function () use ($ticket, $admin) {
            $oldStatus = $ticket->status;
            $newStatus = TicketStatus::APPROVED_BY_ADMIN2;

            $ticket->update(['status' => $newStatus]);

            // Automatically send to web service after Admin2 approval
            $this->sendToWebService($ticket, $admin);

            return $ticket->fresh();
        });
    }

    public function rejectByAdmin2(Ticket $ticket, Authenticatable $admin, string $message): Ticket
    {
        $this->validateAdminRole($admin, UserRole::ADMIN2);
        $this->validateTicketStatus($ticket, TicketStatus::APPROVED_BY_ADMIN1);

        return DB::transaction(function () use ($ticket) {
            $oldStatus = $ticket->status;
            $newStatus = $ticket->status->nextStatusForReject();

            if ($newStatus === null) {
                throw new \InvalidArgumentException(__('Cannot reject ticket with current status.'));
            }

            $ticket->update(['status' => $newStatus]);

            return $ticket->fresh();
        });
    }

    public function getTicketsForAdmin(Authenticatable $admin): \Illuminate\Database\Eloquent\Builder
    {
        $user = $this->getUserModel($admin);

        return match ($user->role) {
            UserRole::ADMIN1 => Ticket::query()
                ->where('status', TicketStatus::SUBMITTED->value),
            UserRole::ADMIN2 => Ticket::query()
                ->where('status', TicketStatus::APPROVED_BY_ADMIN1->value),
            default => Ticket::query()->whereRaw('1 = 0'), // Empty query for non-admins
        };
    }

    public function canAdminProcessTicket(Authenticatable $admin, Ticket $ticket): bool
    {
        $user = $this->getUserModel($admin);

        return match ($user->role) {
            UserRole::ADMIN1 => $ticket->status->canBeApprovedByAdmin1() || $ticket->status->canBeRejectedByAdmin1(),
            UserRole::ADMIN2 => $ticket->status->canBeApprovedByAdmin2() || $ticket->status->canBeRejectedByAdmin2(),
            default => false,
        };
    }

    protected function validateAdminRole(Authenticatable $admin, UserRole $requiredRole): void
    {
        $user = $this->getUserModel($admin);

        if ($user->role !== $requiredRole) {
            throw new AuthorizationException(__('User does not have permission to perform this action.'));
        }
    }

    protected function validateTicketStatus(Ticket $ticket, TicketStatus $expectedStatus): void
    {
        if ($ticket->status !== $expectedStatus) {
            throw new \InvalidArgumentException(
                __('Ticket must be in :status status to perform this action.', [
                    'status' => $expectedStatus->label(),
                ])
            );
        }
    }

    protected function sendToWebService(Ticket $ticket, Authenticatable $admin): void
    {
        Log::info('Ticket approved by Admin2, dispatching web service job', [
            'ticket_id' => $ticket->getKey(),
            'admin_id' => $admin->getAuthIdentifier(),
        ]);

        SendTicketToWebServiceJob::dispatch($ticket->getKey());
    }

    protected function getUserModel(Authenticatable $admin): User
    {
        if ($admin instanceof User) {
            return $admin;
        }

        $user = User::find($admin->getAuthIdentifier());

        if ($user === null) {
            throw new ModelNotFoundException(__('User not found.'));
        }

        return $user;
    }
}
