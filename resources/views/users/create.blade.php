@extends('layouts.app')

@section('title', 'Nuevo usuario')

@section('content')
<x-card title="Registrar nuevo usuario" class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('users.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombres *</label>
            <input type="text" name="nombres" value="{{ old('nombres') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('nombres') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Apellidos *</label>
            <input type="text" name="apellidos" value="{{ old('apellidos') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('apellidos') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Cédula *</label>
    <input type="text" name="cedula" value="{{ old('cedula') }}"
           inputmode="numeric"
           pattern="\d{5,10}"
           minlength="5"
           maxlength="10"
           title="Solo números, entre 5 y 10 dígitos"
           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
           required>
    @error('cedula') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo *</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Oficina *</label>
            <select name="oficina_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="">— Seleccione —</option>
                @foreach ($oficinas as $oficina)
                    <option value="{{ $oficina->id }}" @selected(old('oficina_id') == $oficina->id)>
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
                <option value="solicitante" @selected(old('role') === 'solicitante')>Solicitante</option>
                <option value="admin" @selected(old('role') === 'admin')>Administrador</option>
            </select>
            @error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
            <input type="password" name="password"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña *</label>
            <input type="password" name="password_confirmation"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>

        <div class="md:col-span-2 flex gap-2 pt-2">
            <x-button type="submit">Guardar</x-button>
            <x-button :href="route('users.index')" variant="secondary">Cancelar</x-button>
        </div>
    </form>
</x-card>
@endsection