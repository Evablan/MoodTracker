<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\AttachAdminRoleSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! App::environment(['local', 'staging'])) {
            $this->command->warn('Saltándose seeds de demo en entorno no permitido.');
            return;
        }

        DB::transaction(function () {
            $this->call([
                CompanySeeder::class, // Empresas ANTES que roles   
                DepartmentSeeder::class, // Departamentos ANTES que roles  
                RolesTableSeeder::class,  // Roles ANTES que usuarios
                UserSeeder::class, // Usuarios ANTES que emociones, causas y preguntas
                AttachAdminRoleSeeder::class, // Asignar rol admin DESPUÉS de usuarios y roles
                EmotionSeeder::class, // Emociones ANTES que causas
                CauseSeeder::class, // Causas ANTES que preguntas
                QuestionSeeder::class, // Preguntas ANTES que datos de demo
            ]);
            if (app()->environment('local')) {
                $this->call([
                    DemoDataSeeder::class,
                ]);
            }
        });
    }
}
