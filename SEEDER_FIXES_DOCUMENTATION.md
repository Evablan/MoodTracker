# Documentación: Correcciones de Seeders y Migraciones

## Resumen de Problemas Solucionados

Este documento describe los problemas encontrados y las soluciones implementadas durante el desarrollo del sistema MoodTracker.

---

## 1. Error en DemoDataSeeder - Columna 'key' no encontrada

### Problema
```
SQLSTATE[42703]: Undefined column: 7 ERROR: no existe la columna «key»
LINE 1: select "id" from "companies" where "key" = $1 limit 1
```

### Causa
El seeder `DemoDataSeeder` intentaba buscar una empresa usando:
```php
$companyId = DB::table('companies')->where('key', 'acme')->value('id')
```

Pero la tabla `companies` no tiene una columna `key`, sino que tiene:
- `name` (nombre de la empresa)
- `slug` (identificador único)

### Solución
Cambiamos la consulta para usar la columna correcta:
```php
// ANTES (incorrecto)
$companyId = DB::table('companies')->where('key', 'acme')->value('id')

// DESPUÉS (correcto)
$companyId = DB::table('companies')->where('slug', 'democorp')->value('id')
```

### Archivos Modificados
- `database/seeders/DemoDataSeeder.php` (línea 24)

---

## 2. Error de Restricción NOT NULL en user_id

### Problema
```
SQLSTATE[23502]: Not null violation: 7 ERROR: el valor nulo en la columna «user_id» 
de la relación «mood_entries» viola la restricción de no nulo
```

### Causa
La tabla `mood_entries` tenía la columna `user_id` definida como obligatoria:
```php
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
```

Pero el seeder intentaba crear entradas anónimas:
```php
'user_id' => null, // anónimo
```

### Solución
Creamos una nueva migración para permitir valores nulos en `user_id`:

**Archivo:** `database/migrations/2025_10_18_152640_allow_null_user_id_in_mood_entries.php`

```php
public function up(): void
{
    Schema::table('mood_entries', function (Blueprint $table) {
        // Permitir que user_id sea nulo para entradas anónimas
        $table->foreignId('user_id')->nullable()->change();
    });
}
```

### Razón de la Decisión
Permitir entradas anónimas es importante para:
- Proteger la privacidad de los usuarios
- Facilitar la adopción del sistema
- Cumplir con regulaciones de privacidad

---

## 3. Error de Índice Duplicado en Migración

### Problema
```
SQLSTATE[42P07]: Duplicate table: 7 ERROR: la relación «uq_questions_key» ya existe
```

### Causa
La migración `2025_10_22_174855_create_align_questions_and_answers_indexes.php` intentaba crear un índice único que ya existía.

### Solución
Agregamos una verificación antes de crear el índice:
```php
// Verificar si el índice ya existe antes de crearlo
if (!Schema::hasIndex('questions', 'uq_questions_key')) {
    Schema::table('questions', function (Blueprint $table) {
        $table->unique('key', 'uq_questions_key');
    });
}
```

### Archivos Modificados
- `database/migrations/2025_10_22_174855_create_align_questions_and_answers_indexes.php`

---

## 4. Creación del QuestionsAlignUiSeeder

### Propósito
Alinear las preguntas del sistema con la nueva interfaz de usuario.

### Funcionalidad
1. **Crea 4 nuevas preguntas globales:**
   - `q_energy_motivation` - "Energía y motivación de hoy"
   - `q_flow_focus` - "Flujo y enfoque"
   - `q_social_support` - "Apoyo y reconocimiento"
   - `q_future_outlook` - "¿Cómo ves los próximos días?"

2. **Desactiva preguntas antiguas:**
   - `q_intensity` y `q_need_support` → `is_active = false`
   - `q_trigger` → desactivada y sin emoción asociada

### Características Técnicas
- Usa `upsert()` para evitar duplicados
- Preguntas globales (`company_id = null`, `emotion_id = null`)
- Tipo `scale` con valores de 1 a 5
- Orden específico para la UI (`sort_order`)

### Archivo Creado
- `database/seeders/QuestionsAlignUiSeeder.php`

---

## 5. Resultado Final

### Estado de la Base de Datos
- ✅ 500 entradas demo generadas correctamente
- ✅ Entradas anónimas permitidas
- ✅ 4 nuevas preguntas alineadas con la UI
- ✅ Preguntas antiguas desactivadas (no eliminadas)
- ✅ Índices optimizados para rendimiento

### Comandos Ejecutados
```bash
# Corregir migración de user_id
php artisan migrate

# Ejecutar seeder de alineación de preguntas
php artisan db:seed --class=QuestionsAlignUiSeeder

# Generar datos demo completos
php artisan migrate:fresh --seed
```

---

## Lecciones Aprendidas

### 1. Verificación de Esquemas
- Siempre verificar la estructura real de las tablas antes de escribir consultas
- Usar `Schema::hasColumn()` y `Schema::hasIndex()` para evitar errores

### 2. Migraciones Idempotentes
- Las migraciones deben poder ejecutarse múltiples veces sin errores
- Usar verificaciones condicionales cuando sea necesario

### 3. Seeders Robustos
- Usar `upsert()` en lugar de `insert()` para evitar duplicados
- Incluir verificaciones de existencia de datos dependientes

### 4. Documentación
- Documentar cambios importantes para futuras referencias
- Explicar el razonamiento detrás de las decisiones técnicas

---

## Archivos de Configuración

### Variables de Entorno
```env
# Número de entradas demo a generar (opcional)
DEMO_TOTAL_ENTRIES=500
```

### Estructura de Preguntas
```php
// Estructura esperada para preguntas globales
[
    'key'         => 'q_energy_motivation',
    'prompt'      => 'Energía y motivación de hoy',
    'type'        => 'scale',
    'min_value'   => 1,
    'max_value'   => 5,
    'sort_order'  => 1,
]
```

---

*Documentación generada el: 18 de octubre de 2025*
*Proyecto: MoodTracker - Sistema de seguimiento de estado de ánimo*
