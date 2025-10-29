<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
            'consent_at' => 'datetime',
        ];
    }

    // app/Models/User.php
    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class)->withPivot('company_id');
    }

    public function hasRole(string $roleName, $companyId = null): bool
    {
        $roles = $this->roles;
        if ($companyId !== null) {
            return $roles->where('pivot.company_id', $companyId)->contains('name', $roleName)
                || $roles->where('name', 'super_admin')->isNotEmpty(); // super_admin siempre pasa
        }
        return $roles->contains('name', $roleName) || $roles->contains('name', 'super_admin');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('super_admin') || $this->hasRole('hr_admin');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    public function isEmployee(): bool
    {
        return $this->hasRole('employee');
    }

    public function assignRole(string $roleName, $companyId = null): void
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $this->roles()->syncWithoutDetaching([
                $role->id => ['company_id' => $companyId]
            ]);
        }
    }
}
