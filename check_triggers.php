<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== VERIFICACIÓN DE TRIGGERS ===\n\n";

// 1. Verificar funciones
echo "1. FUNCIONES CREADAS:\n";
try {
    $funciones = DB::select("SELECT proname FROM pg_proc WHERE proname IN ('validate_answer_vs_question', 'prevent_legacy_q_trigger')");
    if (empty($funciones)) {
        echo "❌ NO se encontraron funciones\n";
    } else {
        foreach ($funciones as $f) {
            echo "✅ " . $f->proname . "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// 2. Verificar triggers
echo "\n2. TRIGGERS CREADOS:\n";
try {
    $triggers = DB::select("SELECT trigger_name FROM information_schema.triggers WHERE event_object_table = 'mood_entry_answers'");
    if (empty($triggers)) {
        echo "❌ NO se encontraron triggers\n";
    } else {
        foreach ($triggers as $t) {
            echo "✅ " . $t->trigger_name . "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// 3. Verificar vistas
echo "\n3. VISTAS CREADAS:\n";
try {
    $vistas = DB::select("SELECT viewname FROM pg_views WHERE schemaname = 'public' AND viewname LIKE 'vw_%'");
    if (empty($vistas)) {
        echo "❌ NO se encontraron vistas\n";
    } else {
        foreach ($vistas as $v) {
            echo "✅ " . $v->viewname . "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DE VERIFICACIÓN ===\n";
