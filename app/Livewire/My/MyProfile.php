<?php

namespace App\Livewire\My;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'My Profile'])]
class MyProfile extends Component
{
    public string $name = '';
    public string $email = '';
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $saved = false;
    public bool $passwordSaved = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name  = $user->name;
        $this->email = $user->email;
    }

    public function saveProfile(): void
    {
        $user = Auth::user();

        $this->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update(['name' => $this->name, 'email' => $this->email]);

        $this->saved = true;
    }

    public function savePassword(): void
    {
        $this->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        $user->update(['password' => Hash::make($this->password)]);

        $this->current_password      = '';
        $this->password              = '';
        $this->password_confirmation = '';
        $this->passwordSaved         = true;
    }

    public function render()
    {
        return view('livewire.my.my-profile');
    }
}
