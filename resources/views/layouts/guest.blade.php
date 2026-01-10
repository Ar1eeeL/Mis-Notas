<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Autenticación' }} - Mis Notas</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Inter', system-ui, sans-serif;
        }

        body {
            background: #0f172a;
            min-height: 100vh;
        }

        .glass-card {
            background: #1e293b;
            border: 1px solid #334155;
        }

        .input-custom {
            background: rgba(51, 65, 85, 0.5);
            border: 1px solid #334155;
            transition: border-color 0.2s ease;
        }

        .input-custom:focus {
            background: rgba(51, 65, 85, 0.7);
            border-color: #6366f1;
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="text-white antialiased">
    <div class="fixed inset-0 pointer-events-none bg-gradient-to-br from-indigo-950/30 via-transparent to-purple-950/20"></div>

    <div class="relative z-10 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-3xl font-bold gradient-text">Mis Notas</span>
                </a>
                <p class="mt-3 text-slate-400">Organiza tus ideas con recordatorios inteligentes</p>
            </div>

            <!-- Card -->
            <div class="glass-card rounded-2xl p-8">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="text-center mt-6 text-sm text-slate-500">
                Sistema de notas con recordatorios por Telegram
            </p>
        </div>
    </div>
</body>

</html>