@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
<x-card title="Editar usuario: {{ $user->nombre_completo }}" class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('users.update', $user) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Oficina *</label>
            <select name="oficina_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @foreach ($oficinas as $oficina)
                    <option value="{{ $oficina->id }}" @selected(old('oficina_id', $user->oficina_id) == $oficina->id)>
                        {{ $oficina->nombre }}
                    </option>
                @endforeach
            </select>
            @error('oficina_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rol *</label>
            <select name="role"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="solicitante" @selected(old('role', $user->role) === 'solicitante')>Solicitante</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Administrador</option>
            </select>
            @error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
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

        <div class="md:col-span-2 flex gap-2 pt-2">
            <x-button type="submit" variant="warning">Actualizar</x-button>
            <x-button :href="route('users.index')" variant="secondary">Cancelar</x-button>
        </div>
    </form>
</x-card>
@endsection