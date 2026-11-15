@extends('layouts.app')

@section('title', 'Nueva solicitud')

@section('content')
<x-card title="Crear nueva solicitud" class="max-w-3xl mx-auto">
    <form method="POST" action="{{ route('solicitudes.store') }}" class="space-y-4">
        @csrf

        {{-- Info del solicitante --}}
        <div class="bg-gray-50 rounded-lg p-3 text-sm">
            <p class="text-gray-600">Solicitante: <strong>{{ $user->nombre_completo }}</strong></p>
            <p class="text-gray-600">Oficina: <strong>{{ $user->oficina->nombre ?? '—' }}</strong></p>
        </div>

        {{-- Selector de categoría --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría de equipo requerida *</label>
            <select name="categoria_id" id="categoria_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="">— Seleccione una categoría —</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
            @error('categoria_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Vista previa de equipos disponibles filtrados por categoría --}}
        <div x-data="equiposFiltrados({{ $equiposDisponibles->toJson() }})" x-init="init()">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Equipos disponibles en esta categoría <span class="text-xs text-gray-500">(informativo)</span>
            </label>
            <div class="border rounded-lg p-3 max-h-48 overflow-y-auto bg-gray-50 text-sm">
                <template x-if="filtrados.length === 0">
                    <p class="text-gray-500 text-xs">Selecciona una categoría para ver los equipos disponibles.</p>
                </template>
                <template x-for="eq in filtrados" :key="eq.id">
                    <div class="flex items-center gap-2 py-1 border-b last:border-b-0">
                        <span class="font-mono text-xs" x-text="eq.serial"></span>
                        <span class="text-gray-500 text-xs">
                            — <span x-text="eq.modelo?.marca?.nombre ?? ''"></span>
                            <span x-text="eq.modelo?.nombre ?? ''"></span>
                        </span>
                    </div>
                </template>
            </div>
            <p class="text-xs text-gray-500 mt-1">
                Al aprobar la solicitud, el administrador asignará los equipos específicos.
            </p>
        </div>

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

        {{-- Tipo de solicitud con Alpine para condicionar fecha_fin --}}
        <div x-data="{ tipo: '{{ old('tipo', 'prestamo') }}' }">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de solicitud *</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="tipo" value="prestamo" x-model="tipo" @checked(old('tipo', 'prestamo') === 'prestamo')>
                    <span class="text-sm">Préstamo (temporal)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="tipo" value="asignacion" x-model="tipo" @checked(old('tipo') === 'asignacion')>
                    <span class="text-sm">Asignación (semipermanente)</span>
                </label>
            </div>
            @error('tipo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror

            {{-- Fecha fin: solo habilitada si tipo = prestamo --}}
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Fecha fin / devolución
                    <span class="text-xs text-gray-500" x-show="tipo === 'prestamo'">(obligatoria para préstamo)</span>
                    <span class="text-xs text-gray-500" x-show="tipo === 'asignacion'" x-cloak>(no aplica para asignación)</span>
                </label>
                <input type="date" name="fecha_fin" id="fecha_fin"
                       value="{{ old('fecha_fin') }}"
                       :disabled="tipo === 'asignacion'"
                       :required="tipo === 'prestamo'"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                       x-bind:class="{ 'opacity-60': tipo === 'asignacion' }">
                @error('fecha_fin') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex gap-2 pt-2">
            <x-button type="submit">Enviar solicitud</x-button>
            <x-button :href="route('solicitudes.index')" variant="secondary">Cancelar</x-button>
        </div>
    </form>
</x-card>

{{-- Alpine: filtra equipos por categoría seleccionada --}}
<script>
function equiposFiltrados(equipos) {
    return {
        equipos,
        filtrados: [],
        init() {
            const select = document.getElementById('categoria_id');
            const update = () => {
                const cat = parseInt(select.value);
                this.filtrados = cat
                    ? this.equipos.filter(e => e.modelo && e.modelo.categoria_id === cat)
                    : [];
            };
            select.addEventListener('change', update);
            update();
        }
    };
}
</script>
@endsection