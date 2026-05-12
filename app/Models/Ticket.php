<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'order_id', 'order_item_id', 'user_id',
    'ticket_code', 'qr_code', 'status', 'checked_in_at',
])]
class Ticket extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket) {
            $ticket->uuid        ??= (string) Str::uuid();
            $ticket->ticket_code ??= static::generateTicketCode();
            $ticket->qr_code     ??= $ticket->ticket_code;
        });
    }

    protected function casts(): array
    {
        return [
            'status'        => TicketStatus::class,
            'checked_in_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checkIn(): bool
    {
        if ($this->checked_in_at !== null) {
            return false;
        }

        $this->update([
            'checked_in_at' => now(),
            'status'        => TicketStatus::USED,
        ]);

        return true;
    }

    protected static function generateTicketCode(): string
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (static::where('ticket_code', $code)->exists());

        return $code;
    }
}
