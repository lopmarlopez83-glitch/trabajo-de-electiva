<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Identidad verificada. Ahora puedes establecer tu nueva contraseña.
    </div>

    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Nueva contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                          name="password_confirmation" required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>Restablecer contraseña</x-primary-button>
        </div>
    </form>
</x-guest-layout>