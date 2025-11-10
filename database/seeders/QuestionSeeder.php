<?php

namespace Database\Seeders;

use App\Models\Emotion;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $tenseId = Emotion::query()
            ->whereNull('company_id')
            ->where('key', 'tense')
            ->value('id');

        $rows = [
            [
                'key' => 'q_intensity',
                'prompt' => '¿Qué intensidad tiene esta emoción ahora mismo?',
                'type' => 'scale',
                'min' => 1,
                'max' => 5,
                'options' => null,
                'sort' => 10,
            ],
            [
                'key' => 'q_need_support',
                'prompt' => '¿Sientes que necesitas apoyo hoy?',
                'type' => 'scale',
                'min' => 1,
                'max' => 5,
                'options' => null,
                'sort' => 20,
            ],
            [
                'key' => 'q_trigger',
                'prompt' => '¿Qué lo ha disparado principalmente?',
                'type' => 'select',
                'min' => null,
                'max' => null,
                'options' => [
                    ['key' => 'workload', 'label' => 'Carga de trabajo'],
                    ['key' => 'deadline', 'label' => 'Plazos'],
                    ['key' => 'conflict', 'label' => 'Conflicto'],
                    ['key' => 'other', 'label' => 'Otro'],
                ],
                'sort' => 30,
            ],
        ];

        foreach ($rows as $row) {
            Question::updateOrCreate(
                ['company_id' => null, 'key' => $row['key']],
                [
                    'emotion_id' => $tenseId,
                    'prompt' => $row['prompt'],
                    'type' => $row['type'],
                    'min_value' => $row['min'],
                    'max_value' => $row['max'],
                    'options_json' => $row['options'],
                    'is_active' => true,
                    'active_from' => null,
                    'active_to' => null,
                    'sort_order' => $row['sort'],
                ]
            );
        }
    }
}
