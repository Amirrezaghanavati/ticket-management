<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebServiceAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'attempt_number',
        'success',
        'http_status_code',
        'response_message',
        'error_message',
        'attempted_at',
    ];

    protected function casts(): array
    {
        return [
            'success' => 'boolean',
            'http_status_code' => 'integer',
            'attempt_number' => 'integer',
            'attempted_at' => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
