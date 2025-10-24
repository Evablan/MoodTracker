<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // --- UNIQUE (questions.key)
        // Usa DO-block para evitar error si ya existe
        DB::statement(<<<'SQL'
DO $$
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM pg_constraint
    WHERE conname = 'uq_questions_key'
  ) THEN
    ALTER TABLE public.questions
      ADD CONSTRAINT uq_questions_key UNIQUE (key);
  END IF;
END $$;
SQL);

        // --- UNIQUE (mood_entry_id, question_id)
        DB::statement("
            CREATE UNIQUE INDEX IF NOT EXISTS ux_mea_entry_question
            ON public.mood_entry_answers (mood_entry_id, question_id)
        ");

        // --- FKs en mood_entries
        DB::statement(<<<'SQL'
DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname='fk_me_emotion') THEN
    ALTER TABLE public.mood_entries
      ADD CONSTRAINT fk_me_emotion FOREIGN KEY (emotion_id) REFERENCES public.emotions(id) ON UPDATE CASCADE;
  END IF;
  IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname='fk_me_cause') THEN
    ALTER TABLE public.mood_entries
      ADD CONSTRAINT fk_me_cause   FOREIGN KEY (cause_id)   REFERENCES public.causes(id)   ON UPDATE CASCADE;
  END IF;
  IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname='fk_me_user') THEN
    ALTER TABLE public.mood_entries
      ADD CONSTRAINT fk_me_user    FOREIGN KEY (user_id)    REFERENCES public.users(id)    ON UPDATE CASCADE;
  END IF;
END $$;
SQL);

        // --- FKs en mood_entry_answers
        DB::statement(<<<'SQL'
DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname='fk_mea_entry') THEN
    ALTER TABLE public.mood_entry_answers
      ADD CONSTRAINT fk_mea_entry   FOREIGN KEY (mood_entry_id) REFERENCES public.mood_entries(id) ON DELETE CASCADE;
  END IF;
  IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname='fk_mea_question') THEN
    ALTER TABLE public.mood_entry_answers
      ADD CONSTRAINT fk_mea_question FOREIGN KEY (question_id)  REFERENCES public.questions(id)    ON UPDATE CASCADE;
  END IF;
END $$;
SQL);

        // --- NOT NULL razonables (si ya lo están, no pasa nada)
        // user_id puede ser NULL para entradas anónimas
        // DB::statement("ALTER TABLE public.mood_entries ALTER COLUMN user_id SET NOT NULL");
        DB::statement("ALTER TABLE public.mood_entries ALTER COLUMN emotion_id SET NOT NULL");
        DB::statement("ALTER TABLE public.mood_entries ALTER COLUMN cause_id SET NOT NULL");
        DB::statement("ALTER TABLE public.mood_entries ALTER COLUMN work_quality SET NOT NULL");

        // --- Rango de quality (1..10)
        DB::statement(<<<'SQL'
DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname='chk_work_quality_range') THEN
    ALTER TABLE public.mood_entries
      ADD CONSTRAINT chk_work_quality_range CHECK (work_quality BETWEEN 1 AND 10);
  END IF;
END $$;
SQL);

        // --- Índices de rendimiento
        DB::statement("CREATE INDEX IF NOT EXISTS idx_me_user_date ON public.mood_entries(user_id, created_at)");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_me_emotion   ON public.mood_entries(emotion_id)");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_me_cause     ON public.mood_entries(cause_id)");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_mea_entry    ON public.mood_entry_answers(mood_entry_id)");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_mea_question ON public.mood_entry_answers(question_id)");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_questions_active ON public.questions(is_active)");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_questions_order  ON public.questions(sort_order)");
    }

    public function down(): void
    {
        // Quitar índices (opcionales)
        DB::statement("DROP INDEX IF EXISTS idx_questions_order");
        DB::statement("DROP INDEX IF EXISTS idx_questions_active");
        DB::statement("DROP INDEX IF EXISTS idx_mea_question");
        DB::statement("DROP INDEX IF EXISTS idx_mea_entry");
        DB::statement("DROP INDEX IF EXISTS idx_me_cause");
        DB::statement("DROP INDEX IF EXISTS idx_me_emotion");
        DB::statement("DROP INDEX IF EXISTS idx_me_user_date");
        DB::statement("DROP INDEX IF EXISTS ux_mea_entry_question");

        // Quitar constraints (si necesitas revertir)
        DB::statement("ALTER TABLE public.mood_entries      DROP CONSTRAINT IF EXISTS chk_work_quality_range");
        DB::statement("ALTER TABLE public.mood_entries      DROP CONSTRAINT IF EXISTS fk_me_user");
        DB::statement("ALTER TABLE public.mood_entries      DROP CONSTRAINT IF EXISTS fk_me_cause");
        DB::statement("ALTER TABLE public.mood_entries      DROP CONSTRAINT IF EXISTS fk_me_emotion");
        DB::statement("ALTER TABLE public.mood_entry_answers DROP CONSTRAINT IF EXISTS fk_mea_question");
        DB::statement("ALTER TABLE public.mood_entry_answers DROP CONSTRAINT IF EXISTS fk_mea_entry");
        DB::statement("ALTER TABLE public.questions          DROP CONSTRAINT IF EXISTS uq_questions_key");
    }
};
