<?php

namespace App\Jobs;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\WebServiceAttempt;
use App\Services\WebService\WebServiceClientInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTicketToWebServiceJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1000;

    public int $backoff = 3600;

    public function __construct(
        public int $ticketId
    ) {}

    public function handle(WebServiceClientInterface $webServiceClient): void
    {
        $ticket = Ticket::findOrFail($this->ticketId);

        $attemptNumber = $ticket->webServiceAttempts()->count() + 1;

        $response = $webServiceClient->send($ticket);

        WebServiceAttempt::create([
            'ticket_id' => $ticket->getKey(),
            'attempt_number' => $attemptNumber,
            'success' => $response->success,
            'http_status_code' => $response->statusCode,
            'response_message' => $response->message,
            'error_message' => $response->success ? null : $response->message,
            'attempted_at' => now(),
        ]);

        if ($response->success) {
            $ticket->update(['status' => TicketStatus::SENT_TO_WEBSERVICE]);

            return;
        }

        $this->release($this->backoff);
    }
}
