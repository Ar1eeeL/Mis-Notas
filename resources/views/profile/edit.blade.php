@extends('layouts.notes')

@section('title', 'Mi Perfil')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <header class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Mi Perfil</h1>
            <p class="mt-1 text-slate-400">Gestiona tu cuenta y conexiones</p>
        </header>

        <div class="space-y-6">
            <!-- Telegram Section -->
            <section class="glass-card rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-sky-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .37z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">Telegram</h2>
                        <p class="text-sm text-slate-400">Recibe recordatorios de tus notas</p>
                    </div>
                </div>

                <div id="telegram-status">
                    @if(Auth::user()->telegram_chat_id)
                    <!-- Linked State -->
                    <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-medium text-emerald-300">Cuenta vinculada</p>
                                <p class="text-sm text-slate-400">Tus recordatorios se enviarán a Telegram</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="testTelegram()" class="px-4 py-2 rounded-xl bg-sky-500/20 text-sky-300 hover:bg-sky-500/30 transition-colors">
                            Enviar mensaje de prueba
                        </button>
                        <button onclick="unlinkTelegram()" class="px-4 py-2 rounded-xl border border-rose-500/30 text-rose-400 hover:bg-rose-500/10 transition-colors">
                            Desvincular
                        </button>
                    </div>
                    @else
                    <!-- Not Linked State -->
                    <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="font-medium text-amber-300">Telegram no vinculado</p>
                                <p class="text-sm text-slate-400">Vincula tu cuenta para recibir recordatorios</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <p class="text-slate-300">Sigue estos pasos para vincular tu Telegram:</p>
                        <ol class="list-decimal list-inside space-y-2 text-slate-400 text-sm">
                            <li>Abre Telegram y busca <a href="https://t.me/{{ config('services.telegram.bot_username') }}" target="_blank" class="text-indigo-400 hover:underline">{{ '@' . config('services.telegram.bot_username') }}</a></li>
                            <li>Haz clic en "Generar código" abajo</li>
                            <li>Envía el código al bot</li>
                        </ol>

                        <div id="link-code-container" class="hidden">
                            <!-- QR Code -->
                            <div class="flex flex-col items-center gap-4 p-4 bg-slate-800 rounded-xl">
                                <p class="text-sm text-slate-300">Escanea con tu celular:</p>
                                <div id="qr-code" class="bg-white p-3 rounded-xl"></div>

                                <div class="flex items-center gap-2 w-full">
                                    <div class="flex-1 border-t border-slate-600"></div>
                                    <span class="text-xs text-slate-500">o copia el código</span>
                                    <div class="flex-1 border-t border-slate-600"></div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="text-xl font-mono font-bold text-white tracking-wider" id="link-code"></span>
                                    <button onclick="copyCode()" class="px-3 py-1 rounded-lg bg-indigo-500/20 text-indigo-300 hover:bg-indigo-500/30 text-sm">
                                        Copiar
                                    </button>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-slate-500 text-center">El código expira en 10 minutos</p>
                        </div>

                        <button onclick="generateCode()" id="generate-btn" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                            Generar código de vinculación
                        </button>
                    </div>
                    @endif
                </div>
            </section>

            <!-- Profile Information -->
            <section class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Información del perfil</h2>
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required
                            class="w-full input-glass rounded-xl px-4 py-3 text-white">
                        @error('name')
                        <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                            class="w-full input-glass rounded-xl px-4 py-3 text-white">
                        @error('email')
                        <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary px-6 py-3 rounded-xl text-white font-medium">
                        Guardar cambios
                    </button>
                </form>
            </section>

            <!-- Back to Notes -->
            <div class="text-center">
                <a href="{{ route('notes.index') }}" class="text-indigo-400 hover:text-indigo-300">
                    ← Volver a mis notas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    const BOT_USERNAME = '{{ config("services.telegram.bot_username") }}';
    let qrCodeInstance = null;

    async function generateCode() {
        const btn = document.getElementById('generate-btn');
        btn.disabled = true;
        btn.textContent = 'Generando...';

        try {
            const response = await fetch('/telegram/generate-code', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                const code = data.code;
                const deepLink = `https://t.me/${BOT_USERNAME}?start=${code}`;

                // Mostrar código
                document.getElementById('link-code').textContent = code;
                document.getElementById('link-code-container').classList.remove('hidden');

                // Generar QR
                const qrContainer = document.getElementById('qr-code');
                qrContainer.innerHTML = ''; // Limpiar QR anterior

                qrCodeInstance = new QRCode(qrContainer, {
                    text: deepLink,
                    width: 180,
                    height: 180,
                    colorDark: "#1e1b4b",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });

                btn.textContent = 'Generar nuevo código';
                showToast('¡Escanea el QR o copia el código!', 'success');
            }
        } catch (error) {
            showToast('Error al generar código', 'error');
        }

        btn.disabled = false;
    }

    function copyCode() {
        const code = document.getElementById('link-code').textContent;
        navigator.clipboard.writeText(code);
        showToast('Código copiado', 'success');
    }

    async function testTelegram() {
        try {
            const response = await fetch('/telegram/test', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            showToast(data.message, data.success ? 'success' : 'error');
        } catch (error) {
            showToast('Error al enviar mensaje', 'error');
        }
    }

    async function unlinkTelegram() {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: "Dejarás de recibir recordatorios por Telegram",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, desvincular',
            cancelButtonText: 'Cancelar',
            background: '#1e293b',
            color: '#fff',
            customClass: {
                confirmButton: 'px-4 py-2 bg-gradient-to-br from-indigo-500 to-purple-600 hover:opacity-90 text-white rounded-xl transition-all font-medium',
                cancelButton: 'px-4 py-2 border border-slate-600 hover:bg-slate-700 text-slate-300 rounded-xl transition-colors font-medium',
                popup: 'border border-slate-700 rounded-2xl',
                actions: 'gap-3'
            },
            buttonsStyling: false
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch('/telegram/unlink', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            if (data.success) {
                showToast(data.message, 'success');
                location.reload();
            }
        } catch (error) {
            showToast('Error al desvincular', 'error');
        }
    }
</script>
@endpush