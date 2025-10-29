<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alert extends Model
{
    use HasFactory;
    protected $fillable = [
        'department_id',
        'rule',
        'threshold',
        'period_week',
        'status',
        'created_by',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
