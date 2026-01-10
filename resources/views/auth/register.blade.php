<x-guest-layout>
    <x-slot name="title">Crear Cuenta</x-slot>

    <h2 class="text-2xl font-bold text-white mb-6 text-center">Crear Cuenta</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                Nombre completo
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full input-custom rounded-xl px-4 py-3 text-white placeholder-slate-500"
                placeholder="Tu nombre">
            @error('name')
            <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                Correo electrónico
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
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
                placeholder="Mínimo 8 caracteres">
            @error('password')
            <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                Confirmar contraseña
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full input-custom rounded-xl px-4 py-3 text-white placeholder-slate-500"
                placeholder="Repite tu contraseña">
            @error('password_confirmation')
            <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full btn-primary py-3 rounded-xl text-white font-semibold transition-opacity">
            Crear Cuenta
        </button>
    </form>

    <!-- Login Link -->
    <div class="mt-6 text-center">
        <p class="text-slate-400">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">
                Inicia sesión
            </a>
        </p>
    </div>
</x-guest-layout>