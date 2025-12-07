<?php

namespace App\Services;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;

class TicketService
{
    public function getTicketsForUser(Authenticatable $user, int $perPage = 10): LengthAwarePaginator
    {
        return Ticket::query()
            ->where('user_id', $user->getAuthIdentifier())
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    public function createTicketForUser(
        Authenticatable $user,
        string $title,
        string $message,
        UploadedFile $file
    ): Ticket {
        $filePath = $this->storeFile($file);

        return Ticket::create([
            'user_id' => $user->getAuthIdentifier(),
            'title' => $title,
            'message' => $message,
            'file_url' => $filePath,
            'status' => TicketStatus::SUBMITTED,
        ]);
    }

    public function getTicketForUser(Authenticatable $user, Ticket $ticket): Ticket
    {
        Gate::forUser(user: $user)->authorize('view', $ticket);

        $ticket->load(['user', 'logs.user']);

        return $ticket;
    }

    public function getAdminResponse(Ticket $ticket): ?TicketLog
    {
        return $ticket->logs
            ->filter(fn ($log): bool => $log->user?->isAdmin())
            ->sortByDesc('created_at')
            ->first();
    }

    protected function storeFile(UploadedFile $file): string
    {
        return $file->store('attachments', 'public');
    }
}
