@extends('layouts.app')

@section('title', 'Nueva marca')

@section('content')
<x-card title="Registrar nueva marca" class="max-w-xl">
    <form method="POST" action="{{ route('catalogo.marcas.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('nombre') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2 pt-2">
            <x-button type="submit">Guardar</x-button>
            <x-button :href="route('catalogo.index', ['tab' => 'marcas'])" variant="secondary">Cancelar</x-button>
        </div>
    </form>
</x-card>
@endsection