# Prompt para Cursor – Actualización de README.md (MoodTracker)

Contexto: Este es un proyecto Laravel llamado **MoodTracker**.  
Ya hemos configurado:
- Tailwind CSS con Vite
- Sistema de traducciones en `resources/lang`
- Controladores y vistas iniciales para el registro de estados de ánimo
- Conexión a base de datos con migraciones de usuarios y moods

Objetivo:
Quiero que mantengas actualizado el archivo **README.md** en la raíz del proyecto, siguiendo esta estructura:   

1. **Título y descripción del proyecto** (breve, estilo SaaS).
2. **Instalación** (comandos para clonar, instalar dependencias con Composer y npm, configurar `.env`, correr migraciones y levantar Vite + Artisan).
3. **Uso** (cómo acceder al login/dashboard, ejemplos de rutas).
4. **Características implementadas** (lista clara, actualízala cada vez que avancemos).
5. **Tecnologías utilizadas** (Laravel, Tailwind, Chart.js, etc.).
6. **Próximas mejoras / Roadmap** (rellénalo con lo que vayamos discutiendo).
7. **Licencia** (MIT por defecto, salvo que especifique otra).

Reglas:
- Solo documenta lo que ya existe en el proyecto (no inventes).
- Si agregamos algo nuevo (ej. gráficos con Chart.js, selector de idioma, exportación a PDF), añádelo en **Características** y en el **Roadmap** si aún no está terminado.
- Mantén el Markdown limpio, con títulos (`##`) y ejemplos de código cuando sea útil.
- No borres lo que ya hay en el README, solo añade o modifica según corresponda.
- Cuando no haya nada nuevo, responde simplemente:  
  `"No hay cambios para el README hoy."`
