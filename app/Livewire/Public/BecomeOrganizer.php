<?php

namespace App\Livewire\Public;

use App\Enums\UserRole;
use App\Models\Organizer;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Become an Organizer'])]
class BecomeOrganizer extends Component
{
    public string $name                  = '';
    public string $email                 = '';
    public string $password              = '';
    public string $password_confirmation = '';
    public string $display_name          = '';

    private function displayNameRules(): array
    {
        return [
            'required'                        => 'Your organization needs a name.',
            'min'                             => 'Organization name must be at least 2 characters.',
            'max'                             => 'Organization name cannot exceed 255 characters.',
            'unique'                          => 'An organization with this name already exists — try a more specific name.',
            'regex'                           => 'Organization name can only contain letters, numbers, spaces, hyphens, and ampersands.',
        ];
    }

    public function updatedDisplayName(): void
    {
        $this->validateOnly('display_name', [
            'display_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\w\s\-&]+$/u', 'unique:organizers,display_name'],
        ], $this->displayNameRules());
    }

    public function submit(): void
    {
        $data = $this->validate(
            [
                'name'         => ['required', 'string', 'max:255'],
                'email'        => ['required', 'email', 'lowercase', 'max:255', 'unique:users,email'],
                'password'     => ['required', 'confirmed', Password::defaults()],
                'display_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\w\s\-&]+$/u', 'unique:organizers,display_name'],
            ],
            [
                'name.required'             => 'Please enter your full name.',
                'email.required'            => 'Please enter your email address.',
                'email.unique'              => 'An account with this email already exists.',
                'email.email'               => 'Please enter a valid email address.',
                'password.required'         => 'Please choose a password.',
                'password.confirmed'        => 'Passwords do not match.',
                'display_name.required'     => 'Your organization needs a name.',
                'display_name.min'          => 'Organization name must be at least 2 characters.',
                'display_name.max'          => 'Organization name cannot exceed 255 characters.',
                'display_name.unique'       => 'An organization with this name already exists — try a more specific name.',
                'display_name.regex'        => 'Organization name can only contain letters, numbers, spaces, hyphens, and ampersands.',
            ]
        );

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => UserRole::ORGANIZER,
        ]);

        Organizer::create([
            'user_id'      => $user->id,
            'display_name' => $data['display_name'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect('/organizer', navigate: true);
    }

    public function render()
    {
        return view('livewire.public.become-organizer');
    }
}
