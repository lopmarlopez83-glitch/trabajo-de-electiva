@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')
<x-card title="Mi perfil" class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('perfil.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombres *</label>
            <input type="text" name="nombres" value="{{ old('nombres', $user->nombres) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('nombres') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Apellidos *</label>
            <input type="text" name="apellidos" value="{{ old('apellidos', $user->apellidos) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('apellidos') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cédula (no editable)</label>
            <input type="text" value="{{ $user->cedula }}" disabled
                   class="w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo (no editable)</label>
            <input type="email" value="{{ $user->email }}" disabled
                   class="w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm">
        </div>

        {{-- ✅ Oficina: siempre solo lectura (el admin la edita desde /users/{id}/edit) --}}
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Oficina</label>
            <input type="text" value="{{ $user->oficina->nombre ?? '—' }}" disabled
                   class="w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm">
            <p class="text-xs text-gray-500 mt-1">La oficina la gestiona el administrador.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña (opcional)</label>
            <input type="password" name="password"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
            <input type="password" name="password_confirmation"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="md:col-span-2">
            <x-button type="submit">Guardar cambios</x-button>
        </div>
    </form>
</x-card>
@endsection