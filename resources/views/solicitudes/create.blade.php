@extends('layouts.app')

@section('title', 'Nueva solicitud')

@section('content')
<x-card title="Crear nueva solicitud" class="max-w-2xl">
    <form method="POST" action="{{ route('solicitudes.store') }}" class="space-y-4">
        @csrf

        {{-- Info del solicitante --}}
        <div class="bg-gray-50 rounded-lg p-3 text-sm">
            <p class="text-gray-600">Solicitante: <strong>{{ $user->nombre_completo }}</strong></p>
        </div>

        {{-- ✅ Selector de oficina: solo admin --}}
        @if ($user->esAdmin())
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
        @else
            <div class="bg-gray-50 rounded-lg p-3 text-sm">
                <p class="text-gray-600">Oficina: <strong>{{ $user->oficina->nombre ?? '—' }}</strong></p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha requerida *</label>
                <input type="date" name="fecha_requerida"
                       value="{{ old('fecha_requerida') }}"
                       min="{{ now()->toDateString() }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('fecha_requerida') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad de equipos *</label>
                <input type="number" name="cantidad" min="1" value="{{ old('cantidad', 1) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('cantidad') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de solicitud *</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="tipo" value="prestamo" @checked(old('tipo') === 'prestamo')>
                    <span class="text-sm">Préstamo (temporal)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="tipo" value="asignacion" @checked(old('tipo') === 'asignacion')>
                    <span class="text-sm">Asignación (semipermanente)</span>
                </label>
            </div>
            @error('tipo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Fecha fin / devolución <span class="text-xs text-gray-500">(obligatoria si es préstamo)</span>
            </label>
            <input type="date" name="fecha_fin"
                   value="{{ old('fecha_fin') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('fecha_fin') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2 pt-2">
            <x-button type="submit">Enviar solicitud</x-button>
            <x-button :href="route('solicitudes.index')" variant="secondary">Cancelar</x-button>
        </div>
    </form>
</x-card>
@endsection