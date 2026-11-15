@extends('layouts.app')

@section('title', 'Editar solicitud')

@section('content')
<x-card title="Editar solicitud #{{ $solicitud->id }}" class="max-w-2xl">
    <form method="POST" action="{{ route('solicitudes.update', $solicitud) }}" class="space-y-4">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha requerida *</label>
                <input type="date" name="fecha_requerida"
                       value="{{ old('fecha_requerida', $solicitud->fecha_requerida?->toDateString()) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('fecha_requerida') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad de equipos *</label>
                <input type="number" name="cantidad" min="1"
                       value="{{ old('cantidad', $solicitud->cantidad) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('cantidad') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo *</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="tipo" value="prestamo"
                           @checked(old('tipo', $solicitud->tipo) === 'prestamo')>
                    <span class="text-sm">Préstamo</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="tipo" value="asignacion"
                           @checked(old('tipo', $solicitud->tipo) === 'asignacion')>
                    <span class="text-sm">Asignación</span>
                </label>
            </div>
            @error('tipo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Fecha fin / devolución <span class="text-xs text-gray-500">(obligatoria si es préstamo)</span>
            </label>
            <input type="date" name="fecha_fin"
                   value="{{ old('fecha_fin', $solicitud->fecha_fin?->toDateString()) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('fecha_fin') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2 pt-2">
            <x-button type="submit" variant="warning">Actualizar</x-button>
            <x-button :href="route('solicitudes.index')" variant="secondary">Cancelar</x-button>
        </div>
    </form>
</x-card>
@endsection