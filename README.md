# Mis Notas

Aplicación Full Stack de gestión de notas desarrollada en Laravel 11. Cuenta con un diseño moderno (Glassmorphism) e integración bidireccional con Telegram: permite recibir recordatorios automáticos e interactuar con la base de datos (marcar notas como completadas) directamente desde el chat del bot.

## 🚀 Características Principales

*   **Integración con Telegram:**
    *   **Recordatorios Automáticos:** Recibe alertas en tu móvil cuando una nota vence.
    *   **Interactividad:** Marca notas como "Completadas" directamente desde el chat de Telegram usando botones interactivos, sin abrir la web.
    *   **Comandos del Bot:** Usa `/notas` para ver tus pendientes y `/estado` para verificar tu conexión.
*   **Diseño Moderno:** Interfaz de usuario "Glassmorphism" (efecto cristal) con modo oscuro, optimizada para una experiencia visual premium.
*   **Gestión de Tareas:**
    *   Creación, edición y eliminación de notas con AJAX (sin recargas).
    *   Filtros por categoría, prioridad y estado.
    *   Buscador en tiempo real.
*   **Seguridad:** Autenticación robusta y vinculación segura de cuenta de Telegram mediante códigos únicos generados dinámicamente.

## 🛠️ Tecnologías Utilizadas

*   **Backend:** Laravel 11 (PHP 8.2+)
*   **Frontend:** Blade Templates, Tailwind CSS, JavaScript Vanilla (ES6+)
*   **Base de Datos:** MySQL / SQLite
*   **Servicios Externos:** Telegram Bot API
*   **Herramientas:** Vite, SweetAlert2

## 📦 Instalación y Configuración

1.  **Clonar el repositorio**
    ```bash
    git clone https://github.com/tu-usuario/mis-notas.git
    cd mis-notas
    ```

2.  **Instalar dependencias**
    ```bash
    composer install
    npm install
    ```

3.  **Configurar entorno**
    *   Copia el archivo de ejemplo: `cp .env.example .env`
    *   Configura tu base de datos en el archivo `.env`.
    *   Agrega tu Token del Bot de Telegram:
        ```ini
        TELEGRAM_BOT_TOKEN=tu_token_aqui
        TELEGRAM_BOT_USERNAME=nombre_de_tu_bot
        ```

4.  **Generar clave y migraciones**
    ```bash
    php artisan key:generate
    php artisan migrate
    ```

5.  **Ejecutar la aplicación**
    Necesitarás 3 terminales para una funcionalidad completa en local:

    *   **Terminal 1 (Servidor Web):**
        ```bash
        php artisan serve
        ```
    *   **Terminal 2 (Compilación de Assets):**
        ```bash
        npm run dev
        ```
    *   **Terminal 3 (Procesos en Segundo Plano):**
        *   Para recordatorios: `php artisan schedule:work`
        *   Para escuchar al bot (solo local): `php artisan telegram:listen`

## 👨‍💻 Autor

Proyecto desarrollado como parte de mi portafolio profesional.
