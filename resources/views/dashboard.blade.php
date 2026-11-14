@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php $user = auth()->user(); @endphp

@if ($user->esAdmin())
    {{-- ==================== DASHBOARD ADMIN ==================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-card>
            <p class="text-xs text-gray-500 uppercase">Equipos disponibles</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $disponibles }}</p>
        </x-card>
        <x-card>
            <p class="text-xs text-gray-500 uppercase">En mantenimiento</p>
            <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $mantenimiento }}</p>
        </x-card>
        <x-card>
            <p class="text-xs text-gray-500 uppercase">Prestados</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $prestados }}</p>
        </x-card>
        <x-card>
            <p class="text-xs text-gray-500 uppercase">Asignados</p>
            <p class="text-3xl font-bold text-purple-600 mt-1">{{ $asignados }}</p>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <x-card>
            <p class="text-xs text-gray-500 uppercase">Total equipos</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalEquipos }}</p>
        </x-card>
        <x-card>
            <p class="text-xs text-gray-500 uppercase">Total solicitudes</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalSolicitudes }}</p>
        </x-card>
        <x-card>
            <p class="text-xs text-gray-500 uppercase">Solicitudes pendientes</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $pendientes }}</p>
        </x-card>
    </div>

    <x-card title="Últimas solicitudes pendientes">
        @if ($ultimasPendientes->isEmpty())
            <p class="text-sm text-gray-500">No hay solicitudes pendientes. 🎉</p>
        @else
            <x-table :headers="['#', 'Solicitante', 'Oficina', 'Tipo', 'Cantidad', 'Fecha']">
                @foreach ($ultimasPendientes as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">{{ $s->id }}</td>
                        <td class="px-4 py-3">{{ $s->user->nombre_completo }}</td>
                        <td class="px-4 py-3">{{ $s->oficina->nombre }}</td>
                        <td class="px-4 py-3 capitalize">{{ $s->tipo }}</td>
                        <td class="px-4 py-3">{{ $s->cantidad }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </x-table>
            <div class="mt-4">
                <x-button :href="route('solicitudes.index')" variant="secondary">Ver todas las solicitudes</x-button>
            </div>
        @endif
    </x-card>

@else
    {{-- ==================== DASHBOARD SOLICITANTE ==================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-card>
            <p class="text-xs text-gray-500 uppercase">Mis pendientes</p>
            <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $misPendientes }}</p>
        </x-card>
        <x-card>
            <p class="text-xs text-gray-500 uppercase">Mis aprobadas</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $misAprobadas }}</p>
        </x-card>
        <x-card>
            <p class="text-xs text-gray-500 uppercase">Mis rechazadas</p>
            <p class="text-3xl font-bold text-red-600 mt-1">{{ $misRechazadas }}</p>
        </x-card>
    </div>

    <x-card title="Mis últimas solicitudes">
        @if ($misSolicitudes->isEmpty())
            <p class="text-sm text-gray-500">Aún no has creado solicitudes.</p>
            <div class="mt-3">
                <x-button :href="route('solicitudes.create')">+ Nueva solicitud</x-button>
            </div>
        @else
            <x-table :headers="['#', 'Tipo', 'Cantidad', 'Estado', 'Fecha']">
                @foreach ($misSolicitudes as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $s->id }}</td>
                        <td class="px-4 py-3 capitalize">{{ $s->tipo }}</td>
                        <td class="px-4 py-3">{{ $s->cantidad }}</td>
                        <td class="px-4 py-3">
                            <x-badge :color="match($s->status) {
                                'pendiente' => 'yellow',
                                'aprobada'  => 'green',
                                'rechazada' => 'red',
                                default     => 'gray',
                            }">{{ ucfirst($s->status) }}</x-badge>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $s->fecha_solicitud->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </x-table>
            <div class="mt-4">
                <x-button :href="route('solicitudes.index')" variant="secondary">Ver todas</x-button>
            </div>
        @endif
    </x-card>
@endif
@endsection