<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public function up(): void
  {
    // --- Trigger de validación de respuestas vs tipo de pregunta
    $function_sql = "CREATE OR REPLACE FUNCTION validate_answer_vs_question()
RETURNS trigger AS \$func\$
DECLARE
  v_type text;
  v_min smallint;
  v_max smallint;
BEGIN
  SELECT type, min_value, max_value INTO v_type, v_min, v_max
  FROM public.questions WHERE id = NEW.question_id;

  IF v_type = 'scale' THEN
     IF NEW.answer_numeric IS NULL OR NEW.answer_bool IS NOT NULL OR NEW.answer_option_key IS NOT NULL THEN
       RAISE EXCEPTION 'Respuesta inválida para escala';
     END IF;
     IF v_min IS NOT NULL AND v_max IS NOT NULL AND (NEW.answer_numeric < v_min OR NEW.answer_numeric > v_max) THEN
       RAISE EXCEPTION 'Valor fuera de rango (% - %)', v_min, v_max;
     END IF;
  ELSIF v_type = 'bool' THEN
     IF NEW.answer_bool IS NULL OR NEW.answer_numeric IS NOT NULL OR NEW.answer_option_key IS NOT NULL THEN
       RAISE EXCEPTION 'Respuesta inválida para boolean';
     END IF;
  ELSIF v_type = 'select' THEN
     IF NEW.answer_option_key IS NULL OR NEW.answer_numeric IS NOT NULL OR NEW.answer_bool IS NOT NULL THEN
       RAISE EXCEPTION 'Respuesta inválida para select';
     END IF;
  END IF;

  RETURN NEW;
END;
\$func\$ LANGUAGE plpgsql;";

    DB::statement($function_sql);

    DB::statement("DROP TRIGGER IF EXISTS trg_validate_answer_vs_question ON public.mood_entry_answers");
    DB::statement("
            CREATE TRIGGER trg_validate_answer_vs_question
            BEFORE INSERT OR UPDATE ON public.mood_entry_answers
            FOR EACH ROW EXECUTE FUNCTION validate_answer_vs_question()
        ");

    // --- Bloqueo de legado (q_trigger_legacy)
    $legacy_function_sql = "CREATE OR REPLACE FUNCTION prevent_legacy_q_trigger()
RETURNS trigger AS \$func\$
BEGIN
  IF NEW.question_id = (SELECT id FROM public.questions WHERE key = 'q_trigger_legacy') THEN
    RAISE EXCEPTION 'q_trigger_legacy no admite nuevas respuestas (histórico congelado)';
  END IF;
  RETURN NEW;
END;
\$func\$ LANGUAGE plpgsql;";

    DB::statement($legacy_function_sql);

    DB::statement("DROP TRIGGER IF EXISTS trg_prevent_legacy_q_trigger ON public.mood_entry_answers");
    DB::statement("
            CREATE TRIGGER trg_prevent_legacy_q_trigger
            BEFORE INSERT OR UPDATE ON public.mood_entry_answers
            FOR EACH ROW EXECUTE FUNCTION prevent_legacy_q_trigger()
        ");

    // --- Vistas para reporting
    DB::statement(<<<'SQL'
CREATE OR REPLACE VIEW public.vw_mood_entries_clean AS
SELECT
  me.id              AS entry_id,
  me.user_id,
  me.created_at,
  e.id               AS emotion_id,
  e.name             AS emotion_name,
  c.id               AS cause_id,
  c.name             AS cause_name,
  me.work_quality
FROM public.mood_entries me
LEFT JOIN public.emotions e ON e.id = me.emotion_id
LEFT JOIN public.causes   c ON c.id = me.cause_id;
SQL);

    DB::statement(<<<'SQL'
CREATE OR REPLACE VIEW public.vw_answers_clean AS
SELECT
  me.id                    AS entry_id,
  q.key                    AS question_key,
  q.prompt                 AS question_text,
  q.type                   AS question_type,
  mea.answer_numeric,
  mea.answer_bool,
  mea.answer_option_key,
  mea.created_at
FROM public.mood_entry_answers mea
JOIN public.questions q ON q.id = mea.question_id
JOIN public.mood_entries me ON me.id = mea.mood_entry_id
WHERE q.key NOT IN ('q_trigger', 'q_trigger_legacy', 'q_intensity', 'q_need_support')
ORDER BY entry_id, q.sort_order, q.key;
SQL);
  }

  public function down(): void
  {
    // Drop views
    DB::statement("DROP VIEW IF EXISTS public.vw_answers_clean");
    DB::statement("DROP VIEW IF EXISTS public.vw_mood_entries_clean");

    // Drop triggers
    DB::statement("DROP TRIGGER IF EXISTS trg_prevent_legacy_q_trigger ON public.mood_entry_answers");
    DB::statement("DROP TRIGGER IF EXISTS trg_validate_answer_vs_question ON public.mood_entry_answers");

    // Drop functions
    DB::statement("DROP FUNCTION IF EXISTS prevent_legacy_q_trigger()");
    DB::statement("DROP FUNCTION IF EXISTS validate_answer_vs_question()");
  }
};
