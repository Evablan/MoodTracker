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

- **Formulario principal**: `http://localhost:8000/moods/create`
- **Página de inicio**: `http://localhost:8000/`
- **Cambio de idioma**: `http://localhost:8000/lang/{locale}` (es|en|fr)

### Acceso al formulario

1. Navegar a `/moods/create`
2. Completar las 3 secciones del formulario:
   - Calidad del trabajo (escala 1-10)
   - Selección de emoción (5 opciones disponibles)
   - Preguntas dinámicas según emoción seleccionada
   - Causa de la emoción (trabajo/personal/ambos)

## ✨ Características Implementadas

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
- [ ] Implementar almacenamiento en base de datos
- [ ] Sistema de autenticación de usuarios
- [ ] Dashboard con gráficos de analytics
- [ ] Exportación de reportes
- [ ] API REST para integraciones
- [ ] Notificaciones por email

### Mejoras Técnicas
- [ ] Tests automatizados (PHPUnit)
- [ ] Optimización de performance
- [ ] Implementación de cache
- [ ] Dockerización del proyecto
- [ ] CI/CD pipeline

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
