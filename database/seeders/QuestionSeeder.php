<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $tenseId = DB::table('emotions')->whereNull('company_id')->where('key', 'tense')->value('id');

        $rows = [
            [
                'key' => 'q_intensity',
                'prompt' => '¿Qué intensidad tiene esta emoción ahora mismo?',
                'type' => 'scale',
                'min' => 1,
                'max' => 5,
                'options' => null,
                'sort' => 10
            ],
            [
                'key' => 'q_need_support',
                'prompt' => '¿Sientes que necesitas apoyo hoy?',
                'type' => 'scale',
                'min' => 1,
                'max' => 5,
                'options' => null,
                'sort' => 20
            ],
            [
                'key' => 'q_trigger',
                'prompt' => '¿Qué lo ha disparado principalmente?',
                'type' => 'select',
                'min' => null,
                'max' => null,
                'options' => json_encode([
                    ['key' => 'workload', 'label' => 'Carga de trabajo'],
                    ['key' => 'deadline', 'label' => 'Plazos'],
                    ['key' => 'conflict', 'label' => 'Conflicto'],
                    ['key' => 'other', 'label' => 'Otro'],
                ]),
                'sort' => 30
            ],
        ];

        $payload = array_map(fn($q) => [
            'company_id'   => null,
            'emotion_id'   => $tenseId,
            'key'          => $q['key'],
            'prompt'       => $q['prompt'],
            'type'         => $q['type'],
            'min_value'    => $q['min'],
            'max_value'    => $q['max'],
            'options_json' => $q['options'],
            'is_active'    => true,
            'active_from'  => null,
            'active_to'    => null,
            'sort_order'   => $q['sort'],
            'created_at'   => now(),
            'updated_at'   => now(),
        ], $rows);

        DB::table('questions')->upsert(
            $payload,
            ['company_id', 'key'],
            ['emotion_id', 'prompt', 'type', 'min_value', 'max_value', 'options_json', 'is_active', 'sort_order', 'updated_at']
        );
    }
}
