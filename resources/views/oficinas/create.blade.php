@extends('layouts.app')

@section('title', 'Nueva oficina')

@section('content')
<x-card title="Registrar nueva oficina" class="max-w-xl mx-auto">
    <form method="POST" action="{{ route('oficinas.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('nombre') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
            <input type="text" name="ubicacion" value="{{ old('ubicacion') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('ubicacion') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2 pt-2">
            <x-button type="submit">Guardar</x-button>
            <x-button :href="route('oficinas.index')" variant="secondary">Cancelar</x-button>
        </div>
    </form>
</x-card>
@endsection