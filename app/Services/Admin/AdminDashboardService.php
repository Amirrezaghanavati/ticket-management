<?php

namespace App\Services\Admin;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminDashboardService
{
    public function __construct(private readonly TicketWorkflowService $workflowService) {}

    protected function getPendingTicketsCountForAdmin(Authenticatable $admin): int
    {
        $user = $this->getUserModel($admin);

        return match ($user->role) {
            UserRole::ADMIN1 => Ticket::query()->where('status', TicketStatus::SUBMITTED->value)->count(),
            UserRole::ADMIN2 => Ticket::query()->where('status', TicketStatus::APPROVED_BY_ADMIN1->value)->count(),
            default => 0,
        };
    }

    protected function getApprovedTicketsCountForAdmin(Authenticatable $admin): int
    {
        $user = $this->getUserModel($admin);

        return match ($user->role) {
            UserRole::ADMIN1 => Ticket::query()->where('status', TicketStatus::APPROVED_BY_ADMIN1->value)->count(),
            UserRole::ADMIN2 => Ticket::query()->where('status', TicketStatus::APPROVED_BY_ADMIN2->value)->count(),
            default => 0,
        };
    }

    protected function getRejectedTicketsCountForAdmin(Authenticatable $admin): int
    {
        $user = $this->getUserModel($admin);

        return match ($user->role) {
            UserRole::ADMIN1 => Ticket::query()->where('status', TicketStatus::REJECTED_BY_ADMIN1->value)->count(),
            UserRole::ADMIN2 => Ticket::query()->where('status', TicketStatus::REJECTED_BY_ADMIN2->value)->count(),
            default => 0,
        };
    }

    protected function getRecentTicketsForAdmin(Authenticatable $admin, int $limit = 5): Collection
    {
        return $this->workflowService->getTicketsForAdmin($admin)
            ->with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getDashboardDataForAdmin(Authenticatable $admin): array
    {
        return [
            'pendingTickets' => $this->getPendingTicketsCountForAdmin($admin),
            'approvedTickets' => $this->getApprovedTicketsCountForAdmin($admin),
            'rejectedTickets' => $this->getRejectedTicketsCountForAdmin($admin),
            'recentTickets' => $this->getRecentTicketsForAdmin($admin),
        ];
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
