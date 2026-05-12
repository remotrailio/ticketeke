<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'event_id', 'name', 'description', 'price', 'currency',
    'quantity', 'sold', 'min_per_order', 'max_per_order',
    'sales_start', 'sales_end', 'is_active', 'sort_order',
    'is_group_ticket', 'group_size',
])]
class TicketType extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (TicketType $ticketType) {
            $ticketType->uuid ??= (string) Str::uuid();
        });
    }

    protected function casts(): array
    {
        return [
            'price'           => 'decimal:2',
            'quantity'        => 'integer',
            'sold'            => 'integer',
            'min_per_order'   => 'integer',
            'max_per_order'   => 'integer',
            'sales_start'     => 'datetime',
            'sales_end'       => 'datetime',
            'is_active'       => 'boolean',
            'sort_order'      => 'integer',
            'is_group_ticket' => 'boolean',
            'group_size'      => 'integer',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(\App\Models\Ticket::class);
    }

    public function availableQuantity(): int
    {
        return max(0, $this->quantity - $this->sold);
    }

    public function isSoldOut(): bool
    {
        return $this->sold >= $this->quantity;
    }

    public function isOnSale(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();

        if ($this->sales_start && $now->lt($this->sales_start)) {
            return false;
        }

        if ($this->sales_end && $now->gt($this->sales_end)) {
            return false;
        }

        return ! $this->isSoldOut();
    }

    public function canPurchase(int $qty): bool
    {
        if (! $this->isOnSale()) {
            return false;
        }

        if ($this->isGroupTicket()) {
            return $qty === $this->group_size
                && $qty <= $this->availableQuantity();
        }

        return $qty >= $this->min_per_order
            && $qty <= $this->max_per_order
            && $qty <= $this->availableQuantity();
    }

    // ── Group ticket helpers ───────────────────────────────────────────────────

    public function isGroupTicket(): bool
    {
        return (bool) $this->is_group_ticket;
    }

    /**
     * Number of physical tickets generated per purchase unit.
     * For group tickets this equals group_size; for regular tickets it is 1.
     */
    public function getEffectiveQuantity(): int
    {
        return $this->isGroupTicket() ? (int) $this->group_size : 1;
    }

    /**
     * Total price charged for an order item with the given quantity.
     * Group tickets are flat-priced regardless of group_size.
     */
    public function getTotalPriceForOrder(int $quantity): float
    {
        if ($this->isGroupTicket()) {
            return (float) $this->price;
        }

        return (float) $this->price * $quantity;
    }
}
