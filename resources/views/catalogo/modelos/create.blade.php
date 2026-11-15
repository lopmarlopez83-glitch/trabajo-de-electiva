@extends('layouts.app')

@section('title', 'Nuevo modelo')

@section('content')
<x-card title="Registrar nuevo modelo" class="max-w-xl mx-auto">
    <form method="POST" action="{{ route('catalogo.modelos.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del modelo *</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('nombre') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Marca *</label>
            <select name="marca_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="">— Seleccione —</option>
                @foreach ($marcas as $marca)
                    <option value="{{ $marca->id }}" @selected(old('marca_id') == $marca->id)>{{ $marca->nombre }}</option>
                @endforeach
            </select>
            @error('marca_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
            <select name="categoria_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="">— Seleccione —</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
            @error('categoria_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2 pt-2">
            <x-button type="submit">Guardar</x-button>
            <x-button :href="route('catalogo.index', ['tab' => 'modelos'])" variant="secondary">Cancelar</x-button>
        </div>
    </form>
</x-card>
@endsection