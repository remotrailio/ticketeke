<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Organizer;
use App\Models\User;

class OrganizerOnboardingService
{
    public function handle(User $user, array $data): Organizer
    {
        $organizer = $user->organizer()->create($data);
        $user->update(['role' => UserRole::ORGANIZER->value]);

        return $organizer;
    }
}
