<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Collection;

class OrderPricingService
{
    /**
     * Build a complete order summary from ticket types and a quantities map.
     *
     * @param  array<int|string, int>  $quantities  ticket_type_id => qty
     * @return array{lines: list<array{name:string,quantity:int,price:float,subtotal:float}>, subtotal:float, fee:float, total:float, currency:string}
     */
    public function buildOrderSummary(Event $event, Collection $ticketTypes, array $quantities): array
    {
        $lines = [];

        foreach ($quantities as $typeId => $qty) {
            $qty  = (int) $qty;
            $type = $ticketTypes->firstWhere('id', (int) $typeId);

            if (! $type || $qty < 1) {
                continue;
            }

            $lines[] = [
                'name'     => $type->name,
                'quantity' => $qty,
                'price'    => (float) $type->price,
                'subtotal' => (float) $type->getTotalPriceForOrder($qty),
            ];
        }

        $subtotal = $this->calculateSubtotal(array_column($lines, 'subtotal'));
        $fee      = $this->calculatePlatformFee($event, $subtotal);
        $total    = $this->calculateTotal($subtotal, $fee);
        $currency = strtoupper($ticketTypes->first()?->currency ?? 'KES');

        return compact('lines', 'subtotal', 'fee', 'total', 'currency');
    }

    public function calculateSubtotal(array $lineTotals): float
    {
        return round(array_sum($lineTotals), 2);
    }

    public function calculatePlatformFee(Event $event, float $subtotal): float
    {
        $percentage = (float) ($event->organizer->platform_fee_percentage ?? 10.00);

        return round($subtotal * ($percentage / 100), 2);
    }

    public function calculateTotal(float $subtotal, float $fee, float $discount = 0.0): float
    {
        return round($subtotal + $fee - $discount, 2);
    }
}
