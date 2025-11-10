<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emotion extends Model
{
    use HasFactory;

    protected $table = 'emotions';

    protected $fillable = [
        'company_id',
        'key',
        'name',
        'color_hex',
        'valence',
        'arousal',
        'is_active',
        'sort_order',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
