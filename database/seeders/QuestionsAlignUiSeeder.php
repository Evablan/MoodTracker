<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsAlignUiSeeder extends Seeder
{
    public function run(): void
    {
        // --- GLOBALES QUE SÍ PINTAS (sliders guardados en mood_entry_answers)
        $globals = [
            [
                'key'         => 'q_energy_motivation',
                'prompt'      => 'Energía y motivación de hoy',
                'type'        => 'scale',
                'min_value'   => 1,
                'max_value'   => 5,
                'sort_order'  => 1,
            ],
            [
                'key'         => 'q_flow_focus',
                'prompt'      => 'Flujo y enfoque',
                'type'        => 'scale',
                'min_value'   => 1,
                'max_value'   => 5,
                'sort_order'  => 2,
            ],
            [
                'key'         => 'q_social_support',
                'prompt'      => 'Apoyo y reconocimiento',
                'type'        => 'scale',
                'min_value'   => 1,
                'max_value'   => 5,
                'sort_order'  => 3,
            ],
            [
                'key'         => 'q_future_outlook',
                'prompt'      => '¿Cómo ves los próximos días?',
                'type'        => 'scale',
                'min_value'   => 1,
                'max_value'   => 5,
                'sort_order'  => 4,
            ],
        ];

        foreach ($globals as $g) {
            DB::table('questions')->upsert([
                // columnas para insert
                'company_id'   => null,
                'emotion_id'   => null,           // GLOBAL => sin emoción
                'key'          => $g['key'],
                'prompt'       => $g['prompt'],
                'type'         => $g['type'],
                'min_value'    => $g['min_value'],
                'max_value'    => $g['max_value'],
                'options_json' => null,
                'is_active'    => true,
                'sort_order'   => $g['sort_order'],
            ], ['key'], [
                // columnas a actualizar en conflicto
                'emotion_id',
                'prompt',
                'type',
                'min_value',
                'max_value',
                'options_json',
                'is_active',
                'sort_order'
            ]);
        }

        // --- ESPECÍFICAS QUE HOY NO PINTAS (las archivamos, no se borran)
        DB::table('questions')
            ->whereIn('key', ['q_intensity', 'q_need_support'])
            ->update(['is_active' => false]);

        // --- SI TENÍAS 'q_trigger' EN questions, lo desactivamos
        //     porque la causa real debe venir de causes -> mood_entries.cause_id
        DB::table('questions')
            ->where('key', 'q_trigger')
            ->update(['is_active' => false, 'emotion_id' => null]);
    }
}
