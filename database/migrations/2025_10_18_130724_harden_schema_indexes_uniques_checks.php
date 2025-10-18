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
         * 1) UNICIDAD por key en catálogos.
         *    - Si el catálogo tiene company_id → índices únicos parciales (global y por empresa).
         *    - Si no tiene company_id → único simple por key.
         */

        // EMOTIONS
        if (Schema::hasColumn('emotions', 'company_id')) {
            DB::statement("
                CREATE UNIQUE INDEX IF NOT EXISTS uq_emotions_key_global
                ON public.emotions (key)
                WHERE company_id IS NULL;
            ");
            DB::statement("
                CREATE UNIQUE INDEX IF NOT EXISTS uq_emotions_company_key
                ON public.emotions (company_id, key)
                WHERE company_id IS NOT NULL;
            ");
        } else {
            Schema::table('emotions', function (Blueprint $table) {
                $table->unique('key', 'uq_emotions_key');
            });
        }

        // CAUSES
        if (Schema::hasColumn('causes', 'company_id')) {
            DB::statement("
                CREATE UNIQUE INDEX IF NOT EXISTS uq_causes_key_global
                ON public.causes (key)
                WHERE company_id IS NULL;
            ");
            DB::statement("
                CREATE UNIQUE INDEX IF NOT EXISTS uq_causes_company_key
                ON public.causes (company_id, key)
                WHERE company_id IS NOT NULL;
            ");
        } else {
            Schema::table('causes', function (Blueprint $table) {
                $table->unique('key', 'uq_causes_key');
            });
        }

        // QUESTIONS
        if (Schema::hasColumn('questions', 'company_id')) {
            DB::statement("
                CREATE UNIQUE INDEX IF NOT EXISTS uq_questions_key_global
                ON public.questions (key)
                WHERE company_id IS NULL;
            ");
            DB::statement("
                CREATE UNIQUE INDEX IF NOT EXISTS uq_questions_company_key
                ON public.questions (company_id, key)
                WHERE company_id IS NOT NULL;
            ");
        } else {
            Schema::table('questions', function (Blueprint $table) {
                $table->unique('key', 'uq_questions_key');
            });
        }

        /**
         * 2) ÍNDICES para rendimiento en consultas habituales.
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
            // Evita duplicar la misma pregunta en el mismo envío
            $table->unique(['mood_entry_id', 'question_id'], 'uq_mea_entry_question');
        });

        /**
         * 3) CHECKS (validaciones a nivel BBDD).
         */

        // work_quality entre 1 y 10
        DB::statement("
            ALTER TABLE public.mood_entries
            ADD CONSTRAINT chk_mood_entries_work_quality
            CHECK (work_quality BETWEEN 1 AND 10);
        ");

        // questions.type dentro del conjunto permitido
        DB::statement("
            ALTER TABLE public.questions
            ADD CONSTRAINT chk_questions_type
            CHECK (type IN ('scale','bool','select'));
        ");

        // Si type = 'scale' → min/max obligatorios y coherentes
        DB::statement(
            <<<'SQL'
ALTER TABLE public.questions
ADD CONSTRAINT chk_questions_scale_bounds
CHECK (
  (type <> 'scale')
  OR (min_value IS NOT NULL AND max_value IS NOT NULL AND min_value < max_value)
);
SQL
        );

        // En mood_entry_answers debe venir EXACTAMENTE una de las tres respuestas
        DB::statement(
            <<<'SQL'
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
    }

    public function down(): void
    {
        // Quitar CHECKS (con IF EXISTS por seguridad)
        @DB::statement("ALTER TABLE public.mood_entries        DROP CONSTRAINT IF EXISTS chk_mood_entries_work_quality;");
        @DB::statement("ALTER TABLE public.questions          DROP CONSTRAINT IF EXISTS chk_questions_type;");
        @DB::statement("ALTER TABLE public.questions          DROP CONSTRAINT IF EXISTS chk_questions_scale_bounds;");
        @DB::statement("ALTER TABLE public.mood_entry_answers DROP CONSTRAINT IF EXISTS chk_mea_exactly_one_answer;");

        // Quitar UNIQUEs parciales/índices (PostgreSQL)
        @DB::statement("DROP INDEX IF EXISTS uq_emotions_key_global;");
        @DB::statement("DROP INDEX IF EXISTS uq_emotions_company_key;");
        @DB::statement("DROP INDEX IF EXISTS uq_causes_key_global;");
        @DB::statement("DROP INDEX IF EXISTS uq_causes_company_key;");
        @DB::statement("DROP INDEX IF EXISTS uq_questions_key_global;");
        @DB::statement("DROP INDEX IF EXISTS uq_questions_company_key;");

        // Quitar UNIQUEs simples si se crearon con Schema builder
        if (Schema::hasTable('emotions')) {
            Schema::table('emotions', function (Blueprint $table) {
                try {
                    $table->dropUnique('uq_emotions_key');
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('causes')) {
            Schema::table('causes', function (Blueprint $table) {
                try {
                    $table->dropUnique('uq_causes_key');
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('questions')) {
            Schema::table('questions', function (Blueprint $table) {
                try {
                    $table->dropUnique('uq_questions_key');
                } catch (\Throwable $e) {
                }
            });
        }

        // Quitar índices normales
        if (Schema::hasTable('mood_entries')) {
            Schema::table('mood_entries', function (Blueprint $table) {
                try {
                    $table->dropIndex('idx_mood_entries_company_date');
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex('idx_mood_entries_dept_date');
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex('idx_mood_entries_emotion');
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex('idx_mood_entries_cause');
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex('idx_mood_entries_user');
                } catch (\Throwable $e) {
                }
            });
        }

        if (Schema::hasTable('mood_entry_answers')) {
            Schema::table('mood_entry_answers', function (Blueprint $table) {
                try {
                    $table->dropIndex('idx_mea_entry');
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex('idx_mea_question');
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropUnique('uq_mea_entry_question');
                } catch (\Throwable $e) {
                }
            });
        }
    }
};
