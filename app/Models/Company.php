<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'default_timezone', 'default_locale'];
    
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function departments()
    {
        return $this->hasMany(\App\Models\Department::class);
    }
    
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
