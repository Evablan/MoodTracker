<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Cause;

class CauseSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['key' => 'work',    'name' => 'Trabajo',  'sort_order' => 10],
            ['key' => 'personal', 'name' => 'Personal', 'sort_order' => 20],
            ['key' => 'both',    'name' => 'Ambos',    'sort_order' => 30],
        ];
        foreach ($rows as $row) {
            Cause::updateOrCreate(
                ['company_id' => null, 'key' => $row['key']],
                ['name' => $row['name'], 'is_active' => true, 'sort_order' => $row['sort_order']]
            );
        }
    }
}
