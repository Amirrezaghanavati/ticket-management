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

    $ticket->refresh();

    expect($ticket->webServiceAttempts)->toHaveCount(2)
        ->and($ticket->webServiceAttempts->pluck('id')->toArray())->toContain($attempt1->id, $attempt2->id);
});

it('dispatches TicketStatusChanged event when status changes', function () {
    Event::fake([TicketStatusChanged::class]);

    $ticket = Ticket::factory()->submitted()->create();
    $ticketId = $ticket->id;

    $ticket->status = TicketStatus::APPROVED_BY_ADMIN1;
    $ticket->save();

    Event::assertDispatched(TicketStatusChanged::class);

    $dispatchedEvents = Event::dispatched(TicketStatusChanged::class);
    expect($dispatchedEvents)->not->toBeEmpty()
        ->and($dispatchedEvents->first()[0]->ticket->id)->toBe($ticketId)
        ->and($dispatchedEvents->first()[0]->oldStatus)->toBe(TicketStatus::SUBMITTED)
        ->and($dispatchedEvents->first()[0]->newStatus)->toBe(TicketStatus::APPROVED_BY_ADMIN1);
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
    Event::fake([TicketStatusChanged::class]);

    $ticket = Ticket::factory()->submitted()->create();
    $ticketId = $ticket->id;

    $ticket->status = TicketStatus::APPROVED_BY_ADMIN1;
    $ticket->save();

    Event::assertDispatched(TicketStatusChanged::class, 1);

    $ticket->refresh();
    $ticket->status = TicketStatus::APPROVED_BY_ADMIN2;
    $ticket->save();

    Event::assertDispatched(TicketStatusChanged::class, 2);

    $dispatchedEvents = Event::dispatched(TicketStatusChanged::class);
    $lastEvent = $dispatchedEvents->last()[0];

    expect($lastEvent->ticket->id)->toBe($ticketId)
        ->and($lastEvent->oldStatus)->toBe(TicketStatus::APPROVED_BY_ADMIN1)
        ->and($lastEvent->newStatus)->toBe(TicketStatus::APPROVED_BY_ADMIN2);
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

it('has fillable attributes', function () {
    $ticket = Ticket::factory()->create([
        'title' => 'Test Title',
        'message' => 'Test Message',
    ]);

    expect($ticket->title)->toBe('Test Title')
        ->and($ticket->message)->toBe('Test Message');
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

it('can have empty file url', function () {
    $ticket = Ticket::factory()->create([
        'file_url' => '',
    ]);

    expect($ticket->file_url)->toBe('');
});
