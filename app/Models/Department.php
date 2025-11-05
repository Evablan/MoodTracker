<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;
    
    protected $fillable = ['company_id', 'name'];
    
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
    
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }
    
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
