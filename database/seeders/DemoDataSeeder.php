<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 0) Seguridad: solo en local
        if (!app()->environment('local')) {
            $this->command?->warn('DemoDataSeeder: solo se ejecuta en local.');
            return;
        }

        // 1) Parámetros
        $target = (int) env('DEMO_TOTAL_ENTRIES', 500); // nº de entradas a generar

        // 2) Catálogos base
        $companyId = DB::table('companies')->where('slug', 'democorp')->value('id')
            ?? DB::table('companies')->value('id');

        if (!$companyId) {
            $this->command?->error('No hay companies. Ejecuta primero los seeders base: php artisan db:seed');
            return;
        }

        $departmentIds = DB::table('departments')->where('company_id', $companyId)->pluck('id')->all();
        $emotions      = DB::table('emotions')->whereNull('company_id')->orderBy('sort_order')->get(['id', 'key'])->all();
        $causes        = DB::table('causes')->whereNull('company_id')->orderBy('sort_order')->get(['id', 'key'])->all();

        // 2.1) 🔴 IMPORTANTE: limitar las preguntas a las 4 globales usadas por el dashboard
        $kpiKeys = ['q_energy_motivation', 'q_flow_focus', 'q_social_support', 'q_future_outlook'];
        $questions = DB::table('questions')
            ->whereNull('company_id')
            ->whereIn('key', $kpiKeys)
            ->orderBy('sort_order')
            ->get(['id', 'key', 'type', 'min_value', 'max_value', 'options_json'])
            ->all();

        // Validación defensiva: si faltan, fallamos explícitamente
        $foundKeys = array_map(fn($q) => $q->key, $questions);
        $missing   = array_diff($kpiKeys, $foundKeys);
        if (!empty($missing)) {
            $this->command?->error('Faltan preguntas globales: ' . implode(', ', $missing) .
                ' → Ejecuta el seeder/SQL de Questions antes de DemoDataSeeder.');
            return;
        }

        if (!$departmentIds || !$emotions || !$causes) {
            $this->command?->error('Faltan catálogos (departments/emotions/causes). Ejecuta: php artisan db:seed');
            return;
        }

        // 3) Limpieza de datos operativos (no borra catálogos)
        DB::statement('TRUNCATE TABLE public.mood_entry_answers, public.mood_entries RESTART IDENTITY CASCADE;');

        // 4) Rango temporal (últimos ~28 días)
        $startTs = now()->subDays(27)->startOfDay()->timestamp;
        $endTs   = now()->endOfDay()->timestamp;

        // (Opcional) Usuarios reales para que "Total Usuarios" > 0
        $userIds = DB::table('users')->pluck('id')->all();

        for ($i = 0; $i < $target; $i++) {
            // 4.1) Fecha/hora aleatoria dentro del rango
            $ts        = random_int($startTs, $endTs);
            $entryAt   = Carbon::createFromTimestamp($ts);
            $entryDate = $entryAt->toDateString();

            // 4.2) Selecciones aleatorias
            $deptId = $departmentIds[array_rand($departmentIds)];
            $emo    = $emotions[array_rand($emotions)];
            $cause  = $causes[array_rand($causes)];

            // 4.3) Calidad del trabajo (1..10) – centrada, pero con variación
            $workQuality = random_int(4, 9);

            // 4.4) (Opcional) Asignar usuario aleatorio al ~70% para que el dashboard muestre usuarios
            $userId = null;
            if (!empty($userIds) && random_int(1, 100) <= 70) {
                $userId = $userIds[array_rand($userIds)];
            }

            // 5) Inserta la entrada
            // 🔵 CLAVE: created_at/updated_at ahora usan $entryAt para respetar el rango temporal del dashboard
            $entryId = DB::table('mood_entries')->insertGetId([
                'company_id'    => $companyId,
                'department_id' => $deptId,
                'user_id'       => $userId,      // antes: null (anónimo)
                'emotion_id'    => $emo->id,
                'cause_id'      => $cause->id,
                'work_quality'  => $workQuality,
                'entry_at'      => $entryAt,     // si usas entry_at/entry_date en tu modelo/vistas
                'entry_date'    => $entryDate,
                'created_at'    => $entryAt,     // ← ANTES era now()
                'updated_at'    => $entryAt,     // ← ANTES era now()
            ]);

            // 6) Respuestas SOLO para las 4 preguntas de KPIs
            foreach ($questions as $q) {
                $answerData = [
                    'mood_entry_id'      => $entryId,
                    'question_id'        => $q->id,
                    'answer_numeric'     => null,
                    'answer_bool'        => null,
                    'answer_option_key'  => null,
                    'created_at'         => $entryAt, // ← ANTES era now()
                    'updated_at'         => $entryAt, // ← ANTES era now()
                ];

                if ($q->type === 'scale') {
                    // Hacemos que escalen alrededor de work_quality/2 con ruido, para que tenga sentido
                    $base = max(1, min(5, intdiv($workQuality + 1, 2))); // 4..9 → 2..5
                    $val  = max($q->min_value ?? 1, min($q->max_value ?? 5, $base + random_int(-1, 1)));
                    $answerData['answer_numeric'] = $val;
                } elseif ($q->type === 'bool') {
                    $answerData['answer_bool'] = (bool) random_int(0, 1);
                } elseif ($q->type === 'select') {
                    $options = json_decode($q->options_json ?? '[]', true);
                    $answerData['answer_option_key'] = !empty($options)
                        ? ($options[array_rand($options)]['key'] ?? 'default_option')
                        : 'default_option';
                }

                DB::table('mood_entry_answers')->insert($answerData);
            }
        }

        $this->command?->info("OK: generadas {$target} entradas demo con respuestas (fechadas en el rango).");
    }
}
