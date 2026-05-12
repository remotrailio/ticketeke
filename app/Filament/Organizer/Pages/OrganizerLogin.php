<?php

namespace App\Filament\Organizer\Pages;

use Filament\Auth\Pages\Login;
use Filament\Facades\Filament;

class OrganizerLogin extends Login
{
    public function mount(): void
    {
        $intended = session()->get('url.intended', '');

        if ($intended && ! str_starts_with($intended, url('/organizer'))) {
            session()->forget('url.intended');
        }

        parent::mount();
    }
}
