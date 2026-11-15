@extends('layouts.app')

@section('title', 'Nuevo equipo')

@section('content')
{{-- ✅ Usamos Alpine.js en el formulario para manejar la reactividad --}}
<form method="POST" action="{{ route('equipos.store') }}" 
      class="max-w-2xl mx-auto"
      x-data="{ 
          modeloSeleccionado: '{{ old('modelo_id') }}',
          requiereSpecs() {
              if (!this.modeloSeleccionado) return false;
              const select = document.getElementById('modelo_id');
              const opcion = select.options[select.selectedIndex];
              if(!opcion) return false;
              const cat = opcion.getAttribute('data-categoria') || '';
              return cat.includes('laptop') || cat.includes('portátil') || cat.includes('computadora');
          }
      }">
    <x-card title="Registrar nuevo equipo">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Serial *</label>
                <input type="text" name="serial" value="{{ old('serial') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono" required>
                @error('serial') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Modelo *</label>
                <select name="modelo_id" id="modelo_id" x-model="modeloSeleccionado"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">— Seleccione —</option>
                    @foreach ($modelos as $modelo)
                        <option value="{{ $modelo->id }}" 
                                data-categoria="{{ strtolower($modelo->categoria->nombre ?? '') }}">
                            {{ $modelo->nombre }} — {{ $modelo->marca->nombre ?? '?' }} ({{ $modelo->categoria->nombre ?? '?' }})
                        </option>
                    @endforeach
                </select>
                @error('modelo_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- ✅ Campos condicionales con x-show --}}
            <div x-show="requiereSpecs()" x-cloak class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                <div class="md:col-span-3">
                    <p class="text-sm font-medium text-indigo-700 mb-2">Especificaciones del equipo (Laptop / PC de Mesa)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CPU *</label>
                    <input type="text" name="cpu" value="{{ old('cpu') }}"
                           :required="requiereSpecs()"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('cpu') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">RAM *</label>
                    <input type="text" name="ram" value="{{ old('ram') }}"
                           :required="requiereSpecs()"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('ram') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Almacenamiento *</label>
                    <input type="text" name="almacenamiento" value="{{ old('almacenamiento') }}"
                           :required="requiereSpecs()"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('almacenamiento') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="md:col-span-2">
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
        </div>
    </x-card>
</form>
@endsection