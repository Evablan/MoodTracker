# MoodTracker Enterprise

## 📊 Descripción del Proyecto

**MoodTracker** es una aplicación web empresarial desarrollada en Laravel para el seguimiento y análisis del estado emocional de empleados. Permite registrar emociones, evaluar la calidad del trabajo y generar insights sobre el bienestar laboral.

## 🚀 Instalación

### Prerrequisitos
- PHP 8.2+
- Composer
- Node.js 18+
- NPM
- PostgreSQL 16+ (incluye pgAdmin 4 y Command Line Tools)

> Nota: La base de datos del proyecto es ahora **PostgreSQL**, no **SQLite**.

### Comandos de instalación

```bash
# Clonar dependencias PHP
composer install

# Instalar dependencias Node.js
npm install

# Configurar archivo de entorno
cp .env.example .env
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Compilar assets en desarrollo
npm run dev

# Levantar servidor Laravel
php artisan serve
```

### Configurar PostgreSQL (Windows/XAMPP)

1. **Instalar PostgreSQL** (incluye *Server*, *pgAdmin 4* y *Command Line Tools*).
2. **Crear usuario y base** (con `psql`):
   ```sql
   CREATE ROLE moodtracker_user WITH LOGIN PASSWORD 'TuPasswordFuerte';
   CREATE DATABASE moodtracker_dev OWNER moodtracker_user ENCODING 'UTF8';
   GRANT ALL PRIVILEGES ON DATABASE moodtracker_dev TO moodtracker_user;
   ```

3. **Habilitar driver PHP para PostgreSQL (CLI)**
   - Edita tu `php.ini` de CLI (ver ruta con `php --ini`) y asegúrate de:
     ```ini
     extension=pdo_pgsql
     extension=pgsql
     ```
   - Si PHP no encuentra `libpq.dll`, añade `C:\\Program Files\\PostgreSQL\\18\\bin` al `PATH`
     o copia `libpq.dll` a tu carpeta de PHP (XAMPP).

4. **Configurar .env**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=moodtracker_dev
   DB_USERNAME=moodtracker_user
   DB_PASSWORD=TuPasswordFuerte

   # Para evitar usar BD en el caché local de desarrollo
   CACHE_DRIVER=file
   SESSION_DRIVER=file
   QUEUE_CONNECTION=sync
   ```

5. **Limpiar cachés de Laravel**
   ```bash
   php artisan config:clear
   php artisan optimize:clear
   ```

### Migraciones (PostgreSQL)

- Crear todas las tablas desde cero:
  ```bash
  php artisan migrate:fresh
  ```

- Verificar tablas:
  ```bash
  php artisan tinker
  >>> DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname='public' ORDER BY 1");
  >>> exit
  ```

- o con `psql`:
  ```bash
  psql -h 127.0.0.1 -U moodtracker_user -d moodtracker_dev -c "\\dt"
  ```

> **Nota sobre el orden de migraciones**  
> Laravel ejecuta las migraciones por **orden alfabético**. Si la migración de `users` se ejecuta antes de `companies`, fallará la FK.  
> Renombra los archivos para forzar el orden correcto:
> ```
> database/migrations/0001_01_00_000000_create_companies_table.php
> database/migrations/0001_01_00_000001_create_departments_table.php
> database/migrations/0001_01_01_000000_create_users_table.php
> ```
> Luego ejecuta:
> ```bash
> php artisan migrate:fresh
> ```

## 📖 Uso

### Rutas principales

- **Login**: `http://localhost:8000/login` (email/password o Google OAuth)
- **Formulario principal**: `http://localhost:8000/moods/create` (requiere autenticación y consentimiento para empleados)
- **Dashboard**: `http://localhost:8000/dashboard` (requiere autenticación, solo para admin/rrhh)
- **Consentimiento**: `http://localhost:8000/consent` (solo para empleados sin consentimiento)
- **Página de inicio**: `http://localhost:8000/`
- **Cambio de idioma**: `http://localhost:8000/lang/{locale}` (es|en|fr)

### Acceso al formulario

1. **Iniciar sesión** en `/login`:
   - **Opción 1**: Login tradicional con email y contraseña
   - **Opción 2**: Login con Google OAuth (botón "Continuar con Google")
2. **Consentimiento** (solo empleados): Si es tu primera vez, acepta los términos y condiciones
3. Navegar a `/moods/create` (empleados) o `/dashboard` (admin/rrhh)
4. Completar las 3 secciones del formulario:
   - Calidad del trabajo (escala 1-10)
   - Selección de emoción (5 opciones disponibles)
   - Preguntas dinámicas según emoción seleccionada
   - Causa de la emoción (trabajo/personal/ambos)

## ✨ Características Implementadas

### 🔐 Sistema de Autenticación y Consentimiento
- **Doble método de login**: Email/contraseña tradicional y Google OAuth
- **Autenticación con Google**: Integración completa con Laravel Socialite
- **Sistema de consentimiento obligatorio**: Solo para empleados, admins pueden acceder sin consentimiento
- **Redirección inteligente**: Según el rol del usuario (employee → formulario, admin/rrhh → dashboard)
- **Middleware de protección**: `EnsureUserConsented` protege rutas que requieren consentimiento
- **Manejo de errores OAuth**: Fallback automático para `InvalidStateException`
- **Roles de usuario**: employee, hr_admin, admin, manager con permisos diferenciados

**Documentación completa**: Ver `docs/AUTENTICACION_GOOGLE_CONSENTIMIENTO.md`

### 🌍 Sistema de Internacionalización
- **3 idiomas soportados**: Español, Inglés, Francés
- **Middleware centralizado** para detección automática de idioma
- **Selector visual** en header con banderas
- **Archivos de traducción** organizados por grupos
- **Persistencia** de idioma en sesión

### 🎨 Interfaz de Usuario
- **Logo corporativo** con degradado SVG inline (azul→violeta→coral)
- **Componente logo-lockup** (`<x-logo-lockup>`) con símbolo + texto
- **Símbolo analytics** con forma de onda característica
- **Diseño responsive** con Tailwind CSS v4
- **Barra de progreso** visual con 2 pasos
- **Selección visual** de opciones con colores
- **Efectos hover** y transiciones suaves
- **Header sticky** con selector de idiomas

### 📋 Formulario Inteligente
- **Validación en tiempo real** con JavaScript
- **Preguntas dinámicas** según emoción seleccionada
- **Persistencia de datos** con `old()` helper
- **Botón inteligente** que se habilita al completar campos
- **Saludo personalizado** según hora del día con iconos
- **Emoción dinámica** mostrada en pregunta de causa

### 👥 Sistema de Roles y Permisos
- **4 roles implementados**: super_admin, hr_admin, manager, employee
- **Asignación automática**: Usuario admin configurado con rol hr_admin
- **Soporte multiempresa**: Roles específicos por empresa
- **Validación de integridad**: Triggers PostgreSQL para validación de datos
- **Datos demo**: 500 entradas generadas con respuestas válidas

**Configuración de Usuario Admin:**
```env
ADMIN_EMAIL=evablancomart@gmail.com
```

### 🔒 Sistema de Validación Automática
- **Triggers de PostgreSQL** para validación a nivel de base de datos
- **Validación de respuestas** según tipo de pregunta (scale/bool/select)
- **Protección de datos históricos** contra modificaciones no autorizadas
- **Integridad garantizada** con constraints y foreign keys
- **Validación transparente** sin código adicional requerido

### 📊 Sistema de Reporting Optimizado
- **Vistas pre-construidas** para consultas complejas
- **`vw_mood_entries_clean`** - Entradas con nombres legibles
- **`vw_answers_clean`** - Respuestas filtradas para análisis
- **Índices optimizados** para consultas de alto rendimiento
- **Joins pre-calculados** para dashboard admin

### 🛡️ Seguridad y Integridad
- **Constraints de integridad** aplicados en todas las tablas
- **Foreign keys** con cascada para consistencia
- **Validación de rangos** (work_quality 1-10)
- **Índices únicos** para prevenir duplicados
- **Superusuario configurado** con permisos completos

### Ver datos en PostgreSQL
- Con `psql`:
  ```bash
  psql -h 127.0.0.1 -U moodtracker_user -d moodtracker_dev
  \dt                 -- lista tablas
  \d+ mood_entries    -- columnas/índices
  SELECT COUNT(*) FROM mood_entries;
  \q
  ```

- Con pgAdmin 4: Databases › moodtracker_dev › Schemas › public › Tables.

### 🎯 Experiencia de Usuario
- **Formulario centrado** con diseño profesional
- **Bloques separados** para mejor organización
- **Escalas explicativas** posicionadas correctamente
- **Feedback visual** inmediato en selecciones
- **Navegación intuitiva** entre secciones

### 🔧 Arquitectura Técnica
- **Layout modular** con sistema de herencia Blade
- **Middleware personalizado** para idiomas
- **Controladores organizados** por funcionalidad
- **JavaScript optimizado** con event listeners
- **Assets compilados** con Vite

## 🧭 Registro de Cambios (Docs de Proyecto)

### Actualizaciones backend (Oct 2025)

- Nuevo almacén de configuración `settings` con helper `setting($key, $default)`.
- Tablas y modelos de `alerts` y `audit_logs` con relaciones y factories.
- Factories realistas para Company/Department/User/Alert/AuditLog/Setting.
- Seeder `SettingsSeeder` con: `anon_threshold=5`, `iec_alert_threshold=60`, `send_window="Mon 17:00-18:00"`.
- Documentación detallada: ver `docs/MOODTRACKER_BACKEND_CHANGES.md`.

Uso rápido:
```php
// Leer configuración
$min = setting('anon_threshold', 5);

// Generar datos demo en Tinker
\App\Models\User::factory()->create();
\App\Models\Alert::factory()->create();
\App\Models\AuditLog::factory()->create();
\App\Models\Setting::factory()->create();
```

### Migración del JS del formulario a Vite

- Fecha: 2025-09-25
- Objetivo: Integrar `mood.form.js` al pipeline de Vite para mejorar performance, cache busting y orden de carga.

Pasos realizados:
1. Mover el archivo de `public/js/mood.form.js` a `resources/js/mood.form.js`.
2. Importar el script desde `resources/js/app.js`:
   ```js
   import './mood.form';
   ```
3. Exponer las preguntas en una variable global desde Blade para que el JS (módulo ESM) pueda leerlas:
   ```blade
   <script>
       window.emotionQuestions = @json(__('moods.questions'));
       console.log('Preguntas cargadas:', window.emotionQuestions);
   </script>
   ```
4. Eliminar la inclusión directa via `asset('js/mood.form.js')` en `resources/views/moods/create.blade.php`.
5. Confirmar que el layout incluye Vite:
   ```blade
   @vite(['resources/css/app.css', 'resources/js/app.js'])
   ```

Comandos:
```bash
npm install        # ya instalado previamente
npm run dev        # desarrollo con HMR
# o
npm run build      # build de producción
```

Verificación manual:
- Abrir `/moods/create` y comprobar en la consola "Preguntas cargadas".
- Seleccionar una emoción y validar que se actualizan las preguntas (q1..q4).
- Completar todas las respuestas y verificar que el botón "Enviar" se habilita.

Motivación técnica:
- Minificación y tree-shaking automáticos.
- Cache busting por hashed filenames.
- Gestión de dependencias y orden de carga desde `app.js`.
- Mejor DX con HMR en desarrollo.

### Sistema de Autenticación Google OAuth y Consentimiento (2025-11-03)
- **Autenticación con Google**: Implementación completa con Laravel Socialite
- **Login tradicional**: Formulario de email/contraseña añadido a la vista de login
- **Sistema de consentimiento**: Obligatorio para empleados, opcional para admins
- **Middleware de protección**: `EnsureUserConsented` verifica consentimiento antes de acceder a rutas protegidas
- **Redirección por roles**: Empleados → formulario, Admins/RRHH → dashboard
- **Migración de consentimiento**: Añadidos campos `consent_at` y `role` a tabla `users`
- **Manejo de errores OAuth**: Fallback con `stateless()` para `InvalidStateException`
- **Configuración OAuth**: Variables de entorno y configuración en `config/services.php`
- **Controladores creados**: `GoogleController` y `ConsentController`
- **Vista de consentimiento**: Formulario informativo con términos y condiciones
- **Documentación completa**: `docs/AUTENTICACION_GOOGLE_CONSENTIMIENTO.md` con guía detallada

**Configuración requerida**:
```env
GOOGLE_CLIENT_ID=tu_client_id
GOOGLE_CLIENT_SECRET=tu_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/callback/google
```

**Usuarios de prueba**:
- Empleado: `eva@democorp.test` / `secret123`
- Admin: `evablancomart@gmail.com` / `secret123`

### Configuración PostgreSQL y migraciones (2025-10-17)
- Migración de SQLite → PostgreSQL.
- Creación de usuario y DB: `moodtracker_user` / `moodtracker_dev`.
- Activación de `pdo_pgsql`/`pgsql` en PHP-CLI.
- Ajuste de `.env` y cachés.
- Corrección del orden de migraciones (`companies` → `departments` → `users` ...).
- Verificación de tablas vía `psql`/Tinker.

### Corrección de validación y seeders (2025-10-18)
- **Problema resuelto**: Error "Emotion o cause no válidos" por inconsistencias en claves de traducción.
- **Corrección de claves**: Unificación de claves de emociones en archivos de traducción (es/en/fr).
  - `heureux` → `happy`
  - `neutre` → `neutral` 
  - `frustre` → `frustrated`
  - `tendu` → `tense`
  - `calme` → `calm`
- **Corrección de QuestionSeeder**: Cambio de `q_need_support` de `type='bool'` a `type='scale'` (1-5).
- **Solución de seeders**: Corrección de dependencias entre `companies` → `departments` → `users`.
- **Limpieza de caché**: Comandos para resolver problemas de caché en traducciones.

### Sistema de Roles y Permisos (2025-10-24)
- **Sistema completo de roles**: Implementación de 4 roles (super_admin, hr_admin, manager, employee)
- **Tabla pivot role_user**: Soporte multiempresa con foreign keys y constraints
- **Asignación automática**: Usuario admin configurado con rol hr_admin
- **Triggers corregidos**: Validación mejorada para diferentes tipos de preguntas
- **Seeders optimizados**: Lógica condicional según tipo de pregunta (scale, bool, select)
- **Datos demo generados**: 500 entradas de mood con respuestas válidas
- **Sistema de validación**: Triggers funcionando correctamente con integridad de datos
- **Documentación completa**: `SISTEMA_ROLES_IMPLEMENTATION_DOCUMENTATION.md` con proceso detallado

### Implementación de Triggers y Vistas (2025-10-22)
- **Sistema de validación automática**: Implementación de triggers PostgreSQL para validación de respuestas.
- **Triggers creados**:
  - `validate_answer_vs_question()` - Valida respuestas según tipo de pregunta
  - `prevent_legacy_q_trigger()` - Protege datos históricos de preguntas obsoletas
- **Vistas de reporting optimizadas**:
  - `vw_mood_entries_clean` - Entradas con nombres legibles para dashboard
  - `vw_answers_clean` - Respuestas filtradas para análisis
- **Constraints e índices**: Optimización completa de la base de datos para alto rendimiento.
- **Superusuario configurado**: Usuario `postgres` con permisos completos para triggers.
- **Resolución de problemas**: Corrección de sintaxis PostgreSQL con Laravel (`$$` → `$func$`).
- **Script de verificación**: `check_triggers.php` para monitoreo del sistema.
- **Documentación completa**: `TRIGGERS_IMPLEMENTATION_DOCUMENTATION.md` con proceso detallado.

## 🛠️ Tecnologías Utilizadas

### Backend
- **Laravel 12.28.1** - Framework PHP
- **PHP 8.2+** - Lenguaje de programación
- **PostgreSQL 16+** - Base de datos

### Frontend
- **Tailwind CSS v4.1.13** - Framework CSS
- **Vite 7.1.5** - Build tool y desarrollo
- **JavaScript ES6+** - Interactividad
- **Blade Templates** - Motor de plantillas

### Herramientas de Desarrollo
- **NPM** - Gestión de dependencias frontend
- **Composer** - Gestión de dependencias PHP
- **Autoprefixer** - Compatibilidad CSS
- **PostCSS** - Procesamiento CSS

## 🎯 Próximas Mejoras

### Funcionalidades Pendientes
- [x] ~~Implementar almacenamiento en base de datos~~ ✅ **COMPLETADO**
- [x] ~~Sistema de autenticación de usuarios~~ ✅ **COMPLETADO**
- [ ] Dashboard con gráficos de analytics
- [ ] Exportación de reportes
- [ ] API REST para integraciones
- [ ] Notificaciones por email

### Mejoras Técnicas
- [ ] Tests automatizados (PHPUnit)
- [x] ~~Optimización de performance~~ ✅ **COMPLETADO** (Índices y vistas)
- [ ] Implementación de cache
- [ ] Dockerización del proyecto
- [ ] CI/CD pipeline

### Nuevas Funcionalidades Implementadas
- [x] **Sistema de validación automática** con triggers PostgreSQL
- [x] **Vistas de reporting optimizadas** para dashboard admin
- [x] **Constraints de integridad** para seguridad de datos
- [x] **Superusuario configurado** con permisos completos
- [x] **Script de verificación** para monitoreo del sistema
- [x] **Documentación completa** del proceso de implementación

---

**Desarrollado con ❤️ para empresas que valoran el bienestar de sus empleados**

## 🆘 Solución de problemas comunes

- **SQLSTATE[HY000] / sqlite / “near CONSTRAINT”**  
  Estás ejecutando migraciones contra **SQLite**. Revisa `.env` y pon `DB_CONNECTION=pgsql`, luego:
  ```bash
  php artisan config:clear && php artisan migrate:fresh
  ```

- **could not find driver (pgsql)**  
  Falta el driver en PHP-CLI. En `php.ini` habilita:
  ```ini
  extension=pdo_pgsql
  extension=pgsql
  ```
  Asegúrate de que PHP encuentre `libpq.dll` (añade `C:\\Program Files\\PostgreSQL\\18\\bin` al `PATH` o copia el DLL a la carpeta de PHP).

- **Undefined table: 7 “companies” al migrar users**  
  Orden incorrecto de migraciones. Renombra los archivos como se explica en la Nota sobre el orden de migraciones y ejecuta:
  ```bash
  php artisan migrate:fresh
  ```

- **Errores al limpiar caché: intenta borrar tabla cache**  
  Si `CACHE_DRIVER=database`, usa:
  ```bash
  php artisan cache:table && php artisan migrate
  ```
  o cambia a:
  ```env
  CACHE_DRIVER=file
  SESSION_DRIVER=file
  ```
  y luego:
  ```bash
  php artisan config:clear
  ```

- **"Emotion o cause no válidos"**  
  Inconsistencia entre claves de traducción y base de datos. Verifica que las claves en `resources/lang/*/moods.php` coincidan con las de la BD:
  ```bash
  php artisan optimize:clear  # Limpiar caché
  ```
  Claves correctas: `happy`, `neutral`, `frustrated`, `tense`, `calm`.

- **"No existe el usuario por defecto"**  
  Los seeders no se ejecutaron correctamente. Ejecuta en orden:
  ```bash
  php artisan migrate:fresh --seed
  ```
  O individualmente:
  ```bash
  php artisan db:seed --class="Database\Seeders\CompanySeeder"
  php artisan db:seed --class="Database\Seeders\DepartmentSeeder"  
  php artisan db:seed --class="Database\Seeders\UserSeeder"
  ```

- **Tablas vacías después de seeders**  
  Verifica que existan las dependencias:
  ```sql
  SELECT id, name, slug FROM public.companies;
  SELECT id, name, company_id FROM public.departments;
  SELECT id, name, email FROM public.users;
  ```

## 🔍 Verificación del Sistema

### Comandos de Verificación Rápida

```bash
# Verificar estado de migraciones
php artisan migrate:status

# Verificar triggers y funciones
php check_triggers.php

# Verificar conexión de base de datos
php artisan tinker --execute="echo 'Usuario: ' . DB::select('SELECT current_user')[0]->current_user;"

# Verificar vistas de reporting
php artisan tinker --execute="DB::select('SELECT viewname FROM pg_views WHERE schemaname = \'public\' AND viewname LIKE \'vw_%\'');"
```

### Script de Verificación Automática

El archivo `check_triggers.php` proporciona verificación completa del sistema:

```bash
php check_triggers.php
```

**Salida esperada:**
```
=== VERIFICACIÓN DE TRIGGERS ===

1. FUNCIONES CREADAS:
✅ prevent_legacy_q_trigger
✅ validate_answer_vs_question

2. TRIGGERS CREADOS:
✅ trg_validate_answer_vs_question
✅ trg_prevent_legacy_q_trigger

3. VISTAS CREADAS:
✅ vw_answers_clean
✅ vw_mood_entries_clean

=== FIN DE VERIFICACIÓN ===
```
