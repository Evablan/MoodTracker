<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmotionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['key' => 'happy', 'name' => 'Feliz', 'color_hex' => '#22c55e', 'valence' => 2, 'arousal' => 3, 'sort_order' => 10],
            ['key' => 'calm', 'name' => 'Calmado', 'color_hex' => '#0ea5e9', 'valence' => 1, 'arousal' => 2, 'sort_order' => 20],
            ['key' => 'neutral', 'name' => 'Neutral', 'color_hex' => '#9ca3af', 'valence' => 0, 'arousal' => 2, 'sort_order' => 30],
            ['key' => 'frustrated', 'name' => 'Frustrado', 'color_hex' => '#f59e0b', 'valence' => -1, 'arousal' => 3, 'sort_order' => 40],
            ['key' => 'tense', 'name' => 'Tenso', 'color_hex' => '#ef4444', 'valence' => -2, 'arousal' => 4, 'sort_order' => 50],
        ];

        $payload = array_map(fn($e) => [
            'company_id' => null,
            'key'        => $e['key'],
            'name'       => $e['name'],
            'color_hex'  => $e['color_hex'],
            'valence'    => $e['valence'],
            'arousal'    => $e['arousal'],
            'is_active'  => true,
            'sort_order' => $e['sort_order'],
            'created_at' => now(),
            'updated_at' => now(),
        ], $rows);

        DB::table('emotions')->upsert(
            $payload,
            ['company_id', 'key'],
            ['name', 'color_hex', 'valence', 'arousal', 'is_active', 'sort_order', 'updated_at']
        );
    }
}
