<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodEmotion extends Model
{
    protected $fillable = [
        'work_quality',
        'emotion',
        'answer_1',
        'answer_2',
        'answer_3',
        'answer_4',
        'cause',
    ];
    //Guardar en store cuando tenga la bbdd
}
