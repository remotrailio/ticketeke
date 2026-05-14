<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CheckoutService
{
    public function __construct(
        private readonly CheckoutReservationService $reservation,
    ) {}

    public function resolveGuestUser(string $email, string $name, ?string $phone = null): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => $name,
                'password' => Hash::make(Str::random(16)),
                'role'     => UserRole::ATTENDEE,
            ]
        );
    }

    /**
     * @param  array<array{ticket_type_id: int, quantity: int}>  $items
     */
    public function checkout(User $user, Event $event, array $items): Order
    {
        $event->loadMissing('organizer');

        // Fast pre-flight check before acquiring any locks
        $this->reservation->validateAvailability($items);

        return $this->reservation->createPendingOrder($user, $event, $items);
    }
}
