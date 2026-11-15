@extends('layouts.app')

@section('title', 'Nuevo equipo')

@section('content')
<x-card title="Registrar nuevo equipo" class="max-w-2xl">
    <form method="POST" action="{{ route('equipos.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Serial *</label>
            <input type="text" name="serial" value="{{ old('serial') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono" required>
            @error('serial') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Código patrimonial</label>
            <input type="text" name="codigo_patrimonial" value="{{ old('codigo_patrimonial') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono">
            @error('codigo_patrimonial') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Modelo *</label>
            <select name="modelo_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="">— Seleccione —</option>
                @foreach ($modelos as $modelo)
                    <option value="{{ $modelo->id }}" @selected(old('modelo_id') == $modelo->id)>
                        {{ $modelo->nombre }} — {{ $modelo->marca->nombre ?? '?' }} ({{ $modelo->categoria->nombre ?? '?' }})
                    </option>
                @endforeach
            </select>
            @error('modelo_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
            <select name="status"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="disponible" @selected(old('status') === 'disponible')>Disponible</option>
                <option value="en_mantenimiento" @selected(old('status') === 'en_mantenimiento')>En mantenimiento</option>
                <option value="prestado" @selected(old('status') === 'prestado')>Prestado</option>
                <option value="asignado" @selected(old('status') === 'asignado')>Asignado</option>
            </select>
            @error('status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
            <textarea name="observaciones" rows="3"
                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observaciones') }}</textarea>
            @error('observaciones') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2 flex gap-2 pt-2">
            <x-button type="submit">Guardar</x-button>
            <x-button :href="route('equipos.index')" variant="secondary">Cancelar</x-button>
        </div>
    </form>
</x-card>
@endsection