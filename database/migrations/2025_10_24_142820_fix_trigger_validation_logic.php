<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Corregir la función del trigger para manejar correctamente los valores NULL
        $function_sql = "CREATE OR REPLACE FUNCTION validate_answer_vs_question()
RETURNS trigger AS \$func\$
DECLARE
  v_type text;
  v_min smallint;
  v_max smallint;
BEGIN
  -- Obtener tipo y rangos de la pregunta
  SELECT type, min_value, max_value 
  INTO v_type, v_min, v_max
  FROM questions 
  WHERE id = NEW.question_id;

  -- Si no encuentra la pregunta, permitir (puede ser una pregunta eliminada)
  IF v_type IS NULL THEN
    RETURN NEW;
  END IF;

  -- Validar según el tipo de pregunta
  IF v_type = 'scale' THEN
     -- Para escala: solo answer_numeric debe tener valor, otros deben ser NULL
     IF NEW.answer_numeric IS NULL THEN
       RAISE EXCEPTION 'Respuesta inválida para escala: se requiere valor numérico';
     END IF;
     IF NEW.answer_bool IS NOT NULL OR NEW.answer_option_key IS NOT NULL THEN
       RAISE EXCEPTION 'Respuesta inválida para escala: solo debe tener valor numérico';
     END IF;
     -- Validar rango si está definido
     IF v_min IS NOT NULL AND v_max IS NOT NULL AND (NEW.answer_numeric < v_min OR NEW.answer_numeric > v_max) THEN
       RAISE EXCEPTION 'Valor fuera de rango (% - %)', v_min, v_max;
     END IF;
  ELSIF v_type = 'bool' THEN
     -- Para boolean: solo answer_bool debe tener valor, otros deben ser NULL
     IF NEW.answer_bool IS NULL THEN
       RAISE EXCEPTION 'Respuesta inválida para boolean: se requiere valor booleano';
     END IF;
     IF NEW.answer_numeric IS NOT NULL OR NEW.answer_option_key IS NOT NULL THEN
       RAISE EXCEPTION 'Respuesta inválida para boolean: solo debe tener valor booleano';
     END IF;
  ELSIF v_type = 'select' THEN
     -- Para select: solo answer_option_key debe tener valor, otros deben ser NULL
     IF NEW.answer_option_key IS NULL THEN
       RAISE EXCEPTION 'Respuesta inválida para select: se requiere opción seleccionada';
     END IF;
     IF NEW.answer_numeric IS NOT NULL OR NEW.answer_bool IS NOT NULL THEN
       RAISE EXCEPTION 'Respuesta inválida para select: solo debe tener opción seleccionada';
     END IF;
  END IF;

  RETURN NEW;
END;
\$func\$ LANGUAGE plpgsql;";

        DB::statement($function_sql);
    }

    public function down(): void
    {
        // Restaurar la función original (si es necesario)
        // Por ahora, no hacemos nada en el down
    }
};
