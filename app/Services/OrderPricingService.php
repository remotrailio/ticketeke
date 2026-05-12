<?php

namespace App\Services;

use App\Models\Event;

class OrderPricingService
{
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
