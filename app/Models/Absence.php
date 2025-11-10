<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $table = 'absences';
    protected $primaryKey = 'leave_id';

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'leave_type',
        'days',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
