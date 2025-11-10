<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Emotion;

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

        foreach ($rows as $row) {
            Emotion::updateOrCreate(
                ['company_id' => null, 'key' => $row['key']],
                [
                    'name'       => $row['name'],
                    'color_hex'  => $row['color_hex'],
                    'valence'    => $row['valence'],
                    'arousal'    => $row['arousal'],
                    'is_active'  => true,
                    'sort_order' => $row['sort_order'],
                ]
            );
        }
    }
}
