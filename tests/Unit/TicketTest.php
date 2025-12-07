<?php

use App\Enums\TicketStatus;
use App\Events\TicketStatusChanged;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\User;
use App\Models\WebServiceAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

it('casts status to TicketStatus enum', function () {
    $ticket = Ticket::factory()->create([
        'status' => TicketStatus::SUBMITTED,
    ]);

    expect($ticket->status)->toBeInstanceOf(TicketStatus::class)
        ->and($ticket->status)->toBe(TicketStatus::SUBMITTED);
});

it('can have different status values', function (TicketStatus $status) {
    $ticket = Ticket::factory()->create([
        'status' => $status,
    ]);

    expect($ticket->status)->toBe($status);
})->with([
    TicketStatus::DRAFT,
    TicketStatus::SUBMITTED,
    TicketStatus::APPROVED_BY_ADMIN1,
    TicketStatus::APPROVED_BY_ADMIN2,
    TicketStatus::REJECTED_BY_ADMIN1,
    TicketStatus::REJECTED_BY_ADMIN2,
    TicketStatus::SENT_TO_WEBSERVICE,
]);

it('belongs to a user', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'user_id' => $user->id,
    ]);

    expect($ticket->user)->toBeInstanceOf(User::class)
        ->and($ticket->user->id)->toBe($user->id);
});

it('has many ticket logs', function () {
    $ticket = Ticket::factory()->create();
    $log1 = TicketLog::factory()->create(['ticket_id' => $ticket->id]);
    $log2 = TicketLog::factory()->create(['ticket_id' => $ticket->id]);

    expect($ticket->logs)->toHaveCount(2)
        ->and($ticket->logs->first()->id)->toBe($log1->id)
        ->and($ticket->logs->last()->id)->toBe($log2->id);
});

it('has many web service attempts', function () {
    $ticket = Ticket::factory()->create();
    $attempt1 = WebServiceAttempt::factory()->create(['ticket_id' => $ticket->id]);
    $attempt2 = WebServiceAttempt::factory()->create(['ticket_id' => $ticket->id]);

    expect($ticket->webServiceAttempts)->toHaveCount(2)
        ->and($ticket->webServiceAttempts->first()->id)->toBe($attempt1->id)
        ->and($ticket->webServiceAttempts->last()->id)->toBe($attempt2->id);
});

it('dispatches TicketStatusChanged event when status changes', function () {
    Event::fake();

    $ticket = Ticket::factory()->submitted()->create();

    $ticket->update([
        'status' => TicketStatus::APPROVED_BY_ADMIN1,
    ]);

    Event::assertDispatched(TicketStatusChanged::class, function ($event) use ($ticket) {
        return $event->ticket->id === $ticket->id
            && $event->oldStatus === TicketStatus::SUBMITTED
            && $event->newStatus === TicketStatus::APPROVED_BY_ADMIN1;
    });
});

it('does not dispatch event when status does not change', function () {
    Event::fake();

    $ticket = Ticket::factory()->submitted()->create();

    $ticket->update([
        'title' => 'Updated Title',
    ]);

    Event::assertNotDispatched(TicketStatusChanged::class);
});

it('tracks old status correctly when status changes', function () {
    Event::fake();

    $ticket = Ticket::factory()->submitted()->create();
    $ticket->update(['status' => TicketStatus::APPROVED_BY_ADMIN1]);
    $ticket->update(['status' => TicketStatus::APPROVED_BY_ADMIN2]);

    Event::assertDispatched(TicketStatusChanged::class, function ($event) {
        return $event->oldStatus === TicketStatus::APPROVED_BY_ADMIN1
            && $event->newStatus === TicketStatus::APPROVED_BY_ADMIN2;
    });
});

it('supports soft deletes', function () {
    $ticket = Ticket::factory()->create();

    $ticket->delete();

    expect($ticket->trashed())->toBeTrue()
        ->and(Ticket::find($ticket->id))->toBeNull()
        ->and(Ticket::withTrashed()->find($ticket->id))->not->toBeNull();
});

it('can restore soft deleted tickets', function () {
    $ticket = Ticket::factory()->create();
    $ticket->delete();

    $ticket->restore();

    expect($ticket->trashed())->toBeFalse()
        ->and(Ticket::find($ticket->id))->not->toBeNull();
});

it('can have a parent ticket', function () {
    $parentTicket = Ticket::factory()->create();
    $childTicket = Ticket::factory()->create([
        'parent_id' => $parentTicket->id,
    ]);

    expect($childTicket->parent_id)->toBe($parentTicket->id);
});

it('factory can create tickets with different statuses', function () {
    expect(Ticket::factory()->draft()->create()->status)->toBe(TicketStatus::DRAFT)
        ->and(Ticket::factory()->submitted()->create()->status)->toBe(TicketStatus::SUBMITTED)
        ->and(Ticket::factory()->approvedByAdmin1()->create()->status)->toBe(TicketStatus::APPROVED_BY_ADMIN1)
        ->and(Ticket::factory()->approvedByAdmin2()->create()->status)->toBe(TicketStatus::APPROVED_BY_ADMIN2)
        ->and(Ticket::factory()->rejectedByAdmin1()->create()->status)->toBe(TicketStatus::REJECTED_BY_ADMIN1)
        ->and(Ticket::factory()->rejectedByAdmin2()->create()->status)->toBe(TicketStatus::REJECTED_BY_ADMIN2)
        ->and(Ticket::factory()->sentToWebService()->create()->status)->toBe(TicketStatus::SENT_TO_WEBSERVICE);
});

it('can store file url', function () {
    $fileUrl = 'attachments/test-file.pdf';
    $ticket = Ticket::factory()->create([
        'file_url' => $fileUrl,
    ]);

    expect($ticket->file_url)->toBe($fileUrl);
});

it('can have null file url', function () {
    $ticket = Ticket::factory()->create([
        'file_url' => null,
    ]);

    expect($ticket->file_url)->toBeNull();
});
