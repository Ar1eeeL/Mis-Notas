<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Notas') - Notificaciones</title>
    <meta name="description" content="Sistema de notas con recordatorios por Telegram">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%236366f1%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><path d=%22M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z%22></path><polyline points=%2214 2 14 8 20 8%22></polyline><line x1=%2216%22 y1=%2213%22 x2=%228%22 y2=%2213%22></line><line x1=%2216%22 y1=%2217%22 x2=%228%22 y2=%2217%22></line><polyline points=%2210 9 9 9 8 9%22></polyline></svg>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite -->
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --bg-card-hover: #334155;
            --border-color: #334155;
        }

        * {
            font-family: 'Inter', system-ui, sans-serif;
        }

        body {
            background: var(--bg-dark);
            min-height: 100vh;
        }

        .glass {
            background: rgba(30, 41, 59, 0.95);
            border: 1px solid var(--border-color);
        }

        .glass-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .glass-card:hover {
            background: var(--bg-card-hover);
            border-color: #475569;
        }

        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: opacity 0.2s ease;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .input-glass {
            background: rgba(51, 65, 85, 0.5);
            border: 1px solid var(--border-color);
            transition: border-color 0.2s ease;
        }

        .input-glass:focus {
            background: rgba(51, 65, 85, 0.7);
            border-color: var(--primary);
            outline: none;
        }

        .priority-low {
            border-left: 3px solid #10b981;
        }

        .priority-normal {
            border-left: 3px solid #0ea5e9;
        }

        .priority-high {
            border-left: 3px solid #f59e0b;
        }

        .priority-urgent {
            border-left: 3px solid #f43f5e;
        }

        .modal-backdrop {
            background: rgba(0, 0, 0, 0.8);
        }

        .note-completed {
            opacity: 0.6;
        }

        .note-completed .note-title {
            text-decoration: line-through;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>

    @stack('styles')
</head>

<body class="text-white antialiased">
    <!-- Navigation -->
    <nav class="bg-slate-900/80 border-b border-slate-800 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('notes.index') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">Mis Notas</span>
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    <!-- User dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 px-4 py-2 rounded-xl hover:bg-slate-800 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-medium">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm text-slate-300 hidden sm:block">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 overflow-hidden">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 text-sm text-rose-400 hover:bg-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Background -->
    <div class="fixed inset-0 pointer-events-none bg-gradient-to-br from-indigo-950/30 via-transparent to-purple-950/20"></div>

    <div class="relative z-10">
        @yield('content')
    </div>

    <script>
        window.axios = window.axios || {};
        window.axios.defaults = window.axios.defaults || {};
        window.axios.defaults.headers = window.axios.defaults.headers || {};
        window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

        // SweetAlert2 Toast definition
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#1e293b',
            color: '#fff',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        function showToast(message, type = 'success') {
            Toast.fire({
                icon: type,
                title: message
            });
        }

        // Show flash messages if any
        window.onload = function() {
            @if(session('success'))
            showToast("{{ session('success') }}", 'success');
            @endif
            @if(session('error'))
            showToast("{{ session('error') }}", 'error');
            @endif
        }
    </script>

    @stack('scripts')
</body>

</html>