<?php

namespace App\Livewire;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ActivateAccount extends Component
{
    public string $token;
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?Invitation $invitation = null;

    public function mount(string $token): void
    {
        $this->token = $token;

        // Verify the token is valid, has not expired, and has not already been used
        $this->invitation = Invitation::where('invitation_token', $token)
            ->where('status', 'sent')
            ->where('expires_at', '>', now())
            ->first();

        if (! $this->invitation) {
            abort(404, 'Invalid, consumed, or expired invitation token.');
        }

        $this->email = $this->invitation->email;
    }

    public function activate()
    {
        $this->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 1. Create the base User account using details from invitation [1.2.3]
        $user = User::create([
            'name' => trim(($this->invitation->first_names ?? '') . ' ' . ($this->invitation->surname ?? '')),
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_locked' => false,
        ]);

        // 2. Programmatically log them in [1.2.3]
        Auth::login($user);

        // 3. Redirect them to complete onboarding with their token passed in URL [1.2.3]
        return redirect()->to("/student/register-student?token={$this->token}");
    }

    public function render()
    {
        return view('livewire.activate-account')
            ->layout('components.layouts.app'); // Standard layout file
    }
}
