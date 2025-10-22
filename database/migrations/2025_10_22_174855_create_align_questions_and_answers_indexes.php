<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // 1) LIMPIEZA DE POSIBLES DUPLICADOS EN questions.key (conserva el menor id)
        //    Si no hay duplicados, esto no hace nada.
        DB::statement(<<<'SQL'
WITH dups AS (
  SELECT id, key,
         ROW_NUMBER() OVER (PARTITION BY key ORDER BY id) AS rn
  FROM questions
)
DELETE FROM questions q
USING dups d
WHERE q.id = d.id
  AND d.rn > 1;
SQL);

        // 2) UNIQUE en questions.key para permitir upserts con ON CONFLICT (key)
        // Verificar si el índice ya existe antes de crearlo
        if (!Schema::hasIndex('questions', 'uq_questions_key')) {
            Schema::table('questions', function (Blueprint $table) {
                // nombre explícito para el índice
                $table->unique('key', 'uq_questions_key');
            });
        }

        // 3) UNIQUE (mood_entry_id, question_id) en mood_entry_answers
        //    Evita respuestas duplicadas para la misma pregunta en la misma entrada.
        DB::statement("
            CREATE UNIQUE INDEX IF NOT EXISTS ux_mea_entry_question
            ON mood_entry_answers (mood_entry_id, question_id)
        ");
    }

    public function down(): void
    {
        // Eliminar índices creados
        Schema::table('questions', function (Blueprint $table) {
            // debe coincidir el nombre usado en up()
            $table->dropUnique('uq_questions_key');
        });

        DB::statement("DROP INDEX IF EXISTS ux_mea_entry_question");
    }
};
