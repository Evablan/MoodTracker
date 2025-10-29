<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ No sembrar datos de prueba en producción
        if (! App::environment(['local', 'staging'])) {
            $this->command->warn('Saltándose seeds de demo en entorno no permitido.');
            return;
        }

        // 2️⃣ Ejecutar los seeders dentro de una transacción
        DB::transaction(function () {

            // 3️⃣ Sembrar catálogos y relaciones base en orden lógico
            $this->call([
                CompanySeeder::class,         // empresas primero
                DepartmentSeeder::class,      // luego departamentos
                RolesTableSeeder::class,      // luego roles
                UserSeeder::class,            // luego usuarios
                AttachAdminRoleSeeder::class, // asignar rol admin
                EmotionSeeder::class,         // emociones
                CauseSeeder::class,           // causas
                QuestionSeeder::class,        // preguntas globales
            ]);

            // 4️⃣ Solo en entorno local: datos falsos para probar dashboard
            if (app()->environment('local')) {
                $this->call([
                    DemoDataSeeder::class, // genera entradas y respuestas de los últimos 30 días
                ]);
            }
            // Settings (local y staging)
            if (app()->environment(['local', 'staging'])) {
                $this->call(SettingsSeeder::class);
            }
        });
    }
}
