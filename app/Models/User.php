<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_locked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_locked' => 'boolean',
        ];
    }

    /**
     * Authorize panel access globally across your panels.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();

        // 1. Enforce student panel restrictions
        if ($panelId === 'student') {
            // Allows access if they are not a registered staff member,
            // even if they do not yet have a student profile created.
            return !$this->staffMember()->exists();
        }

        // 2. Enforce staff panel restrictions
        if ($panelId === 'staff') {
            // Only allow access if a staff profile is mapped to this user account
            return $this->staffMember()->exists();
        }

        // 3. Admin / General App Panel
        return true;
}

    /**
     * Get the Student profile associated with the User.
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    /**
     * Get the Staff Member profile associated with the User.
     */
    public function staffMember(): HasOne
    {
        return $this->hasOne(StaffMember::class, 'user_id', 'id');
    }
}
