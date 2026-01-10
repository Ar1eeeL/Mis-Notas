<x-guest-layout>
    <x-slot name="title">Iniciar Sesión</x-slot>

    <!-- Session Status -->
    @if (session('status'))
    <div class="mb-4 text-sm text-emerald-400 text-center">
        {{ session('status') }}
    </div>
    @endif

    <h2 class="text-2xl font-bold text-white mb-6 text-center">Iniciar Sesión</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
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

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                Contraseña
            </label>
            <input id="password" type="password" name="password" required
                class="w-full input-custom rounded-xl px-4 py-3 text-white placeholder-slate-500"
                placeholder="••••••••">
            @error('password')
            <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-600 bg-slate-700 text-indigo-500 focus:ring-indigo-500">
                <span class="text-sm text-slate-400">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-indigo-400 hover:text-indigo-300">
                ¿Olvidaste tu contraseña?
            </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full btn-primary py-3 rounded-xl text-white font-semibold transition-opacity">
            Iniciar Sesión
        </button>
    </form>

    <!-- Register Link -->
    <div class="mt-6 text-center">
        <p class="text-slate-400">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">
                Regístrate aquí
            </a>
        </p>
    </div>
</x-guest-layout>