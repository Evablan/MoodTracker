<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin', 'description' => 'Acceso total al sistema',        'created_at' => now(), 'updated_at' => now()],
            ['name' => 'hr_admin',    'description' => 'Panel RRHH (empresa/área)',      'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manager',     'description' => 'Gestión de equipo/segmento',     'created_at' => now(), 'updated_at' => now()],
            ['name' => 'employee',    'description' => 'Usuario estándar (autogestión)', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(['name' => $r['name']], $r);
        }
    }
}
