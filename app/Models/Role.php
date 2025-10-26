<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['name', 'description'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('company_id');
    }

    public static function findByName(string $name): ?self
    {
        return static::where('name', $name)->first();
    }

    public function hasUsers(): bool
    {
        return $this->users()->exists();
    }
}
