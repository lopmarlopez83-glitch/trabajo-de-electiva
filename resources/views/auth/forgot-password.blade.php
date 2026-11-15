<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Para restablecer tu contraseña, ingresa tu <strong>correo</strong> y tu <strong>cédula</strong>.
    </div>

    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="mb-4 font-medium text-sm text-green-600">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Correo')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="cedula" :value="__('Cédula')" />
            <x-text-input id="cedula" class="block mt-1 w-full" type="text" name="cedula"
                          :value="old('cedula')" required />
            <x-input-error :messages="$errors->get('cedula')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                Volver al login
            </a>
            <x-primary-button class="ms-3">Verificar identidad</x-primary-button>
        </div>
    </form>
</x-guest-layout>