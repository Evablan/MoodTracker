<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seguridad: solo en local
        if (!app()->environment('local')) {
            $this->command?->warn('DemoDataSeeder: solo se ejecuta en local.');
            return;
        }

        // ---- Configurable por .env (opcional) ----
        $target = (int) env('DEMO_TOTAL_ENTRIES', 500); // nº de entradas a generar
        // ------------------------------------------

        // Asegúrate de que los catálogos existen
        $companyId = DB::table('companies')->where('slug', 'democorp')->value('id')
            ?? DB::table('companies')->value('id');

        if (!$companyId) {
            $this->command?->error('No hay companies. Ejecuta primero los seeders base: php artisan db:seed');
            return;
        }

        $departmentIds = DB::table('departments')->where('company_id', $companyId)->pluck('id')->all();
        $emotions      = DB::table('emotions')->whereNull('company_id')->orderBy('sort_order')->get(['id', 'key'])->all();
        $causes        = DB::table('causes')->whereNull('company_id')->orderBy('sort_order')->get(['id', 'key'])->all();
        $questions     = DB::table('questions')->whereNull('company_id')->orderBy('sort_order')->get(['id', 'key', 'type', 'min_value', 'max_value'])->all();

        if (!$departmentIds || !$emotions || !$causes || !$questions) {
            $this->command?->error('Faltan catálogos (departments/emotions/causes/questions). Ejecuta: php artisan db:seed');
            return;
        }

        // Limpia SOLO datos operativos para no duplicar
        DB::statement('TRUNCATE TABLE public.mood_entry_answers, public.mood_entries RESTART IDENTITY CASCADE;');

        $now     = now();
        $startTs = now()->subDays(27)->startOfDay()->timestamp; // últimas 4 semanas
        $endTs   = now()->endOfDay()->timestamp;

        for ($i = 0; $i < $target; $i++) {
            // Fecha/hora aleatoria dentro del rango
            $ts        = random_int($startTs, $endTs);
            $entryAt   = Carbon::createFromTimestamp($ts);
            $entryDate = $entryAt->toDateString();

            // Selecciones aleatorias
            $deptId = $departmentIds[array_rand($departmentIds)];
            $emo    = $emotions[array_rand($emotions)];
            $cause  = $causes[array_rand($causes)];

            // Calidad del trabajo (1..10)
            $workQuality = random_int(4, 9);

            // Inserta la entrada
            $entryId = DB::table('mood_entries')->insertGetId([
                'company_id'    => $companyId,
                'department_id' => $deptId,
                'user_id'       => null, // anónimo
                'emotion_id'    => $emo->id,
                'cause_id'      => $cause->id,
                'work_quality'  => $workQuality,
                'entry_at'      => $entryAt,
                'entry_date'    => $entryDate,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);

            // Respuestas a las 4 preguntas (escala 1..5)
            foreach ($questions as $q) {
                $val = random_int($q->min_value ?? 1, $q->max_value ?? 5);
                DB::table('mood_entry_answers')->insert([
                    'mood_entry_id'     => $entryId,
                    'question_id'       => $q->id,
                    'answer_numeric'    => $val,
                    'answer_bool'       => null,
                    'answer_option_key' => null,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
            }
        }

        $this->command?->info("OK: generadas {$target} entradas demo con respuestas.");
    }
}
