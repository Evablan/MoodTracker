<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

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
                CompanySeeder::class,
                DepartmentSeeder::class,
                UserSeeder::class,
                EmotionSeeder::class,
                CauseSeeder::class,
                QuestionSeeder::class,
                DemoDataSeeder::class,
            ]);
        });
    }
}
