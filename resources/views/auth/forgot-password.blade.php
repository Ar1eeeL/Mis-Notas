<x-guest-layout>
    <x-slot name="title">Recuperar Contraseña</x-slot>

    <h2 class="text-2xl font-bold text-white mb-4 text-center">Recuperar Contraseña</h2>

    <p class="text-sm text-slate-400 mb-6 text-center">
        Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
    </p>

    <!-- Session Status -->
    @if (session('status'))
    <div class="mb-4 text-sm text-emerald-400 text-center">
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                Correo electrónico
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full input-custom rounded-xl px-4 py-3 text-white placeholder-slate-500"
                placeholder="tu@email.com">
            @error('email')
            <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full btn-primary py-3 rounded-xl text-white font-semibold transition-opacity">
            Enviar enlace de recuperación
        </button>
    </form>

    <!-- Back to Login -->
    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm text-indigo-400 hover:text-indigo-300">
            ← Volver al inicio de sesión
        </a>
    </div>
</x-guest-layout>