<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('companies')->upsert([
            [
                'name' => 'DemoCorp',
                'slug' => 'democorp',
                'default_timezone' => 'Europe/Madrid',
                'default_locale' => 'es',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['slug'], ['name', 'default_timezone', 'default_locale', 'updated_at']);
    }
}
