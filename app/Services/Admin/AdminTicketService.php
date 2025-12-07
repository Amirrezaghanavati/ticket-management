<?php

namespace App\Services\Admin;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class AdminTicketService
{
    public function __construct(
        private readonly TicketWorkflowService $workflowService
    ) {}

    public function getTicketsWithPagination(
        Authenticatable $admin,
        int $perPage = 15
    ): LengthAwarePaginator {
        $tickets = $this->workflowService->getTicketsForAdmin($admin)
            ->with('user')
            ->latest()
            ->paginate($perPage);

        return $tickets;
    }

    public function getTicketForView(Authenticatable $admin, Ticket $ticket): Ticket
    {
        Gate::forUser(user: $admin)->authorize('view', $ticket);

        $ticket->load(['user', 'logs.user']);

        return $ticket;
    }

    public function approve(Authenticatable $admin, Ticket $ticket): Ticket
    {
        return $this->processAction($admin, $ticket, 'approve');
    }

    public function reject(Authenticatable $admin, Ticket $ticket): Ticket
    {
        return $this->processAction($admin, $ticket, 'reject');
    }

    public function processStatusChange(
        Authenticatable $admin,
        Ticket $ticket,
        int $status,
        ?string $message = null
    ): Ticket {
        return match ($status) {
            TicketStatus::APPROVED_BY_ADMIN1->value => $this->workflowService->approveByAdmin1($ticket, $admin, $message),
            TicketStatus::REJECTED_BY_ADMIN1->value => $this->workflowService->rejectByAdmin1($ticket, $admin, $message ?? ''),
            TicketStatus::APPROVED_BY_ADMIN2->value => $this->workflowService->approveByAdmin2($ticket, $admin, $message),
            TicketStatus::REJECTED_BY_ADMIN2->value => $this->workflowService->rejectByAdmin2($ticket, $admin, $message ?? ''),
            default => throw new \InvalidArgumentException(__('Invalid status transition.')),
        };
    }


    public function processAction(
        Authenticatable $admin,
        Ticket $ticket,
        string $action,
        ?string $message = null
    ): Ticket {
        $user = $this->getUserModel($admin);

        return match ($action) {
            'approve' => match ($user->role) {
                UserRole::ADMIN1 => $this->workflowService->approveByAdmin1($ticket, $admin, $message),
                UserRole::ADMIN2 => $this->workflowService->approveByAdmin2($ticket, $admin, $message),
                default => throw new \InvalidArgumentException(__('Invalid admin role for approval.')),
            },
            'reject' => match ($user->role) {
                UserRole::ADMIN1 => $this->workflowService->rejectByAdmin1($ticket, $admin, $message ?? ''),
                UserRole::ADMIN2 => $this->workflowService->rejectByAdmin2($ticket, $admin, $message ?? ''),
                default => throw new \InvalidArgumentException(__('Invalid admin role for rejection.')),
            },
            default => throw new \InvalidArgumentException(__('Invalid action. Must be "approve" or "reject".')),
        };
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

    public function getSuccessMessage(int $status): string
    {
        return match ($status) {
            TicketStatus::APPROVED_BY_ADMIN1->value,
            TicketStatus::APPROVED_BY_ADMIN2->value => __('Ticket approved successfully!'),
            TicketStatus::REJECTED_BY_ADMIN1->value,
            TicketStatus::REJECTED_BY_ADMIN2->value => __('Ticket rejected successfully!'),
            default => __('Ticket status changed successfully!'),
        };
    }
}
