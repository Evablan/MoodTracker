<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'department_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'provider',
        'external_id',
        'status',
        'consent_at',
        'role',
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
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // app/Models/User.php
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class);
    }

    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class)
            ->withPivot('company_id')
            ->withTimestamps(); // para que se actualice el created_at y updated_at
    }

    public function hasRole(string $name, ?int $companyId = null): bool
    {
        $q = $this->roles()->where('name', $name);
        if ($companyId !== null) {
            $q->wherePivot('company_id', $companyId);
        }
        return $q->exists();
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

    public function assignRole(string $name, int $companyId): void
    {
        $roleId = \App\Models\Role::where('name', $name)->value('id');
        $this->roles()->syncWithoutDetaching([$roleId => ['company_id' => $companyId]]);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }
}
