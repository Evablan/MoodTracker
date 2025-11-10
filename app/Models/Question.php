<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'company_id',
        'emotion_id',
        'key',
        'prompt',
        'type',
        'min_value',
        'max_value',
        'options_json',
        'is_active',
        'active_from',
        'active_to',
        'sort_order',
    ];

    protected $casts = [
        'options_json' => 'array',
        'active_from' => 'datetime',
        'active_to' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function emotion()
    {
        return $this->belongsTo(Emotion::class);
    }
}
