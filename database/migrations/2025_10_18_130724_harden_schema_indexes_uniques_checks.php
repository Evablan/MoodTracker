<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * 1) UNICIDAD por key en catálogos (parcial → PostgreSQL)
         * - Evita duplicados globales y por empresa.
         * - Usamos índices únicos parciales con WHERE (company_id IS NULL/NOT NULL).
         */

        // emotions
        DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS uq_emotions_key_global
                       ON public.emotions (key)
                       WHERE company_id IS NULL;");

        DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS uq_emotions_company_key
                       ON public.emotions (company_id, key)
                       WHERE company_id IS NOT NULL;");

        // causes
        DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS uq_causes_key_global
                       ON public.causes (key)
                       WHERE company_id IS NULL;");

        DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS uq_causes_company_key
                       ON public.causes (company_id, key)
                       WHERE company_id IS NOT NULL;");

        // questions
        DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS uq_questions_key_global
                       ON public.questions (key)
                       WHERE company_id IS NULL;");

        DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS uq_questions_company_key
                       ON public.questions (company_id, key)
                       WHERE company_id IS NOT NULL;");

        /**
         * 2) ÍNDICES para rendimiento
         * - Consultas por fecha/empresa/departamento/emoción.
         */
        Schema::table('mood_entries', function (Blueprint $table) {
            $table->index(['company_id', 'entry_date'], 'idx_mood_entries_company_date');
            $table->index(['department_id', 'entry_date'], 'idx_mood_entries_dept_date');
            $table->index('emotion_id', 'idx_mood_entries_emotion');
            $table->index('cause_id', 'idx_mood_entries_cause');
            $table->index('user_id', 'idx_mood_entries_user');
        });

        Schema::table('mood_entry_answers', function (Blueprint $table) {
            $table->index('mood_entry_id', 'idx_mea_entry');
            $table->index('question_id', 'idx_mea_question');

            // Una respuesta por pregunta dentro de una entrada
            $table->unique(['mood_entry_id', 'question_id'], 'uq_mea_entry_question');
        });

        /**
         * 3) CHECKS de integridad
         */

        // mood_entries.work_quality entre 1 y 10
        DB::statement("ALTER TABLE public.mood_entries
                       ADD CONSTRAINT chk_mood_entries_work_quality
                       CHECK (work_quality BETWEEN 1 AND 10);");

        // questions.type dentro de los permitidos
        DB::statement("ALTER TABLE public.questions
                       ADD CONSTRAINT chk_questions_type
                       CHECK (type IN ('scale','bool','select'));");

      // Si la pregunta es de escala → min/max obligatorios y coherentes
DB::statement(<<<'SQL'
ALTER TABLE public.questions
ADD CONSTRAINT chk_questions_scale_bounds
CHECK (
  (type <> 'scale')
  OR (min_value IS NOT NULL AND max_value IS NOT NULL AND min_value < max_value)
);
SQL
);

// mood_entry_answers: exactamente UNA de las tres columnas de respuesta debe venir rellena
DB::statement(<<<'SQL'
ALTER TABLE public.mood_entry_answers
ADD CONSTRAINT chk_mea_exactly_one_answer
CHECK (
  (CASE WHEN answer_numeric    IS NOT NULL THEN 1 ELSE 0 END) +
  (CASE WHEN answer_bool       IS NOT NULL THEN 1 ELSE 0 END) +
  (CASE WHEN answer_option_key IS NOT NULL THEN 1 ELSE 0 END)
  = 1
);
SQL
);


    public function down(): void
    {
        // Quitar CHECKS
        @DB::statement(\"ALTER TABLE public.mood_entries DROP CONSTRAINT IF EXISTS chk_mood_entries_work_quality;\");
        @DB::statement(\"ALTER TABLE public.questions   DROP CONSTRAINT IF EXISTS chk_questions_type;\");
        @DB::statement(\"ALTER TABLE public.questions   DROP CONSTRAINT IF EXISTS chk_questions_scale_bounds;\");
        @DB::statement(\"ALTER TABLE public.mood_entry_answers DROP CONSTRAINT IF EXISTS chk_mea_exactly_one_answer;\");

        // Quitar UNIQUEs parciales (índices)
        @DB::statement(\"DROP INDEX IF EXISTS uq_emotions_key_global;\");
        @DB::statement(\"DROP INDEX IF EXISTS uq_emotions_company_key;\");
        @DB::statement(\"DROP INDEX IF EXISTS uq_causes_key_global;\");
        @DB::statement(\"DROP INDEX IF EXISTS uq_causes_company_key;\");
        @DB::statement(\"DROP INDEX IF EXISTS uq_questions_key_global;\");
        @DB::statement(\"DROP INDEX IF EXISTS uq_questions_company_key;\");

        // Quitar índices normales
        Schema::table('mood_entries', function (Blueprint $table) {
            $table->dropIndex('idx_mood_entries_company_date');
            $table->dropIndex('idx_mood_entries_dept_date');
            $table->dropIndex('idx_mood_entries_emotion');
            $table->dropIndex('idx_mood_entries_cause');
            $table->dropIndex('idx_mood_entries_user');
        });

        Schema::table('mood_entry_answers', function (Blueprint $table) {
            $table->dropIndex('idx_mea_entry');
            $table->dropIndex('idx_mea_question');
            $table->dropUnique('uq_mea_entry_question');
        });
    }
};
