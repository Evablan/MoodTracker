<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CauseSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['key' => 'work',    'name' => 'Trabajo',  'sort_order' => 10],
            ['key' => 'personal', 'name' => 'Personal', 'sort_order' => 20],
            ['key' => 'both',    'name' => 'Ambos',    'sort_order' => 30],
        ];

        $payload = array_map(fn($c) => [
            'company_id' => null,
            'key'        => $c['key'],
            'name'       => $c['name'],
            'is_active'  => true,
            'sort_order' => $c['sort_order'],
            'created_at' => now(),
            'updated_at' => now(),
        ], $rows);

        DB::table('causes')->upsert(
            $payload,
            ['company_id', 'key'],
            ['name', 'is_active', 'sort_order', 'updated_at']
        );
    }
}
