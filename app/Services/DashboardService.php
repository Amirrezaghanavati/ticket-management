<?php

namespace App\Services;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    protected function getRecentTicketsForUser(Authenticatable $user, int $limit = 5): Collection
    {
        return Ticket::query()
            ->where('user_id', $user->getAuthIdentifier())
            ->latest()
            ->limit($limit)
            ->get();
    }

    protected function getTotalTicketsCountForUser(Authenticatable $user): int
    {
        return Ticket::query()
            ->where('user_id', $user->getAuthIdentifier())
            ->count();
    }

    protected function getOpenTicketsCountForUser(Authenticatable $user): int
    {
        return Ticket::query()
            ->where('user_id', $user->getAuthIdentifier())
            ->whereIn('status', [
                TicketStatus::DRAFT->value,
                TicketStatus::SUBMITTED->value,
                TicketStatus::APPROVED_BY_ADMIN1->value,
            ])
            ->count();
    }

    public function getDashboardDataForUser(Authenticatable $user): array
    {
        return [
            'tickets' => $this->getRecentTicketsForUser($user),
            'totalTickets' => $this->getTotalTicketsCountForUser($user),
            'openTickets' => $this->getOpenTicketsCountForUser($user),
        ];
    }
}
