<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cause extends Model
{
    use HasFactory;

    protected $table = 'causes';

    protected $fillable = [
        'company_id',
        'key',
        'name',
        'is_active',
        'sort_order',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
