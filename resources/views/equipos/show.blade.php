@extends('layouts.app')

@section('title', 'Detalle de equipo')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Info del equipo --}}
    <x-card title="Información del equipo" class="lg:col-span-2">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Serial</dt>
                <dd class="font-mono font-medium">{{ $equipo->serial }}</dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Marca</dt>
                <dd class="font-medium">{{ $equipo->modelo->marca->nombre ?? '—' }}</dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Modelo</dt>
                <dd class="font-medium">{{ $equipo->modelo->nombre ?? '—' }}</dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Categoría</dt>
                <dd><x-badge color="indigo">{{ $equipo->modelo->categoria->nombre ?? '—' }}</x-badge></dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Status</dt>
                <dd>
                    <x-badge :color="match($equipo->status) {
                        'disponible'       => 'green',
                        'en_mantenimiento' => 'yellow',
                        'prestado'         => 'blue',
                        'asignado'         => 'purple',
                        default            => 'gray',
                    }">{{ ucfirst(str_replace('_', ' ', $equipo->status)) }}</x-badge>
                </dd>
            </div>

            {{-- Especificaciones condicionales --}}
            @if ($equipo->requiereEspecificaciones())
                <div class="border-b pb-2">
                    <dt class="text-gray-500 text-xs uppercase">CPU</dt>
                    <dd class="font-medium">{{ $equipo->cpu ?? '—' }}</dd>
                </div>
                <div class="border-b pb-2">
                    <dt class="text-gray-500 text-xs uppercase">RAM</dt>
                    <dd class="font-medium">{{ $equipo->ram ?? '—' }}</dd>
                </div>
                <div class="border-b pb-2">
                    <dt class="text-gray-500 text-xs uppercase">Almacenamiento</dt>
                    <dd class="font-medium">{{ $equipo->almacenamiento ?? '—' }}</dd>
                </div>
            @endif

            <div class="sm:col-span-2">
                <dt class="text-gray-500 text-xs uppercase">Observaciones</dt>
                <dd class="text-gray-700">{{ $equipo->observaciones ?? '—' }}</dd>
            </div>
        </dl>

        <div class="flex gap-2 mt-6">
            <x-button :href="route('equipos.edit', $equipo)" variant="warning">Editar</x-button>
            <x-button :href="route('equipos.index')" variant="secondary">Volver</x-button>
        </div>
    </x-card>

    {{-- Historial de entregas --}}
    <x-card title="Historial de entregas">
        @if ($equipo->entregas->isEmpty())
            <p class="text-sm text-gray-500">Sin entregas registradas.</p>
        @else
            <ul class="space-y-3 text-sm">
                @foreach ($equipo->entregas as $entrega)
                    <li class="border-b pb-2">
                        <p class="font-medium">
                            {{ $entrega->user->nombre_completo ?? '—' }}
                            <x-badge :color="$entrega->tipo === 'prestamo' ? 'blue' : 'purple'" class="ml-1">
                                {{ ucfirst($entrega->tipo) }}
                            </x-badge>
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $entrega->oficina->nombre ?? '—' }} · 
                            {{ $entrega->fecha_entrega?->format('d/m/Y') ?? '—' }}
                        </p>
                        <p class="text-xs">
                            <x-badge :color="$entrega->status === 'activa' ? 'yellow' : 'green'">
                                {{ ucfirst($entrega->status) }}
                            </x-badge>
                        </p>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-card>

</div>
@endsection