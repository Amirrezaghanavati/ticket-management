<?php

namespace App\Listeners;

use App\Events\TicketStatusChanged;
use App\Models\TicketLog;
use Illuminate\Support\Facades\Auth;

class LogTicketStatusChange
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketStatusChanged $event): void
    {
        $message = $event->message ?? __('Ticket status changed from :oldStatus to :newStatus', [
            'oldStatus' => $event->oldStatus->label(),
            'newStatus' => $event->newStatus->label(),
        ]);

        TicketLog::create([
            'ticket_id' => $event->ticket->id,
            'message' => $message,
            'user_id' => Auth::id() ?? $event->ticket->user_id,
        ]);
    }
}
