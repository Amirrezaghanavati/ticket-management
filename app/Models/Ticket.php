<?php

namespace App\Models;

use App\Enums\TicketStatus;
use App\Events\TicketStatusChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserStatusChangedMail;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'parent_id',
        'title',
        'message',
        'file_url',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (Ticket $ticket): void {
            if ($ticket->isDirty('status')) {
                $oldStatusRaw = $ticket->getRawOriginal('status');
                $oldStatus = TicketStatus::from((int) $oldStatusRaw);
                $newStatus = $ticket->status;

                event(new TicketStatusChanged(
                    $ticket,
                    $oldStatus,
                    $newStatus
                ));

                // TODO: Send email to user
                // $subject = __('Ticket Status Changed');
                // $body = __('Your ticket status has been changed from :oldStatus to :newStatus.', [
                //     'oldStatus' => $oldStatus->label(),
                //     'newStatus' => $newStatus->label(),
                // ]);

                // Mail::to($ticket->user->email)->queue(new UserStatusChangedMail($subject, $body));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TicketLog::class);
    }

    public function webServiceAttempts(): HasMany
    {
        return $this->hasMany(WebServiceAttempt::class);
    }
}
