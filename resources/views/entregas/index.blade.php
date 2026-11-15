@extends('layouts.app')

@section('title', 'Entregas')

@section('content')
@php $user = auth()->user(); @endphp

{{-- Resumen --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <x-card>
        <p class="text-xs text-gray-500 uppercase">Préstamos activos</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $resumen['prestamos_activos'] }}</p>
    </x-card>
    <x-card>
        <p class="text-xs text-gray-500 uppercase">Asignaciones activas</p>
        <p class="text-2xl font-bold text-purple-600 mt-1">{{ $resumen['asignaciones_activas'] }}</p>
    </x-card>
    <x-card>
        <p class="text-xs text-gray-500 uppercase">Devueltas</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $resumen['devueltas'] }}</p>
    </x-card>
</div>

{{-- Filtros --}}
<x-card class="mb-4">
    <form method="GET" action="{{ route('entregas.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Tipo</label>
            <select name="tipo"
                    class="border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">— Todos —</option>
                <option value="prestamo" @selected(request('tipo') === 'prestamo')>Préstamo</option>
                <option value="asignacion" @selected(request('tipo') === 'asignacion')>Asignación</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
            <select name="status"
                    class="border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">— Todos —</option>
                <option value="activa" @selected(request('status') === 'activa')>Activa</option>
                <option value="devuelta" @selected(request('status') === 'devuelta')>Devuelta</option>
            </select>
        </div>

        <div class="flex gap-2">
            <x-button type="submit">Filtrar</x-button>
            <x-button :href="route('entregas.index')" variant="secondary">Limpiar</x-button>
        </div>
    </form>
</x-card>

{{-- Tabla --}}
@if ($entregas->isEmpty())
    <x-card><p class="text-sm text-gray-500">No hay entregas registradas.</p></x-card>
@else
    <x-table :headers="['#', 'Serial', 'Equipo', 'Tipo', 'Solicitante', 'Oficina', 'F. entrega', 'Status', 'Acciones']">
        @foreach ($entregas as $entrega)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-600">{{ $entrega->id }}</td>
                <td class="px-4 py-3 font-mono text-xs">{{ $entrega->equipo->serial ?? '—' }}</td>
                <td class="px-4 py-3">
                    {{ $entrega->equipo->modelo->marca->nombre ?? '—' }}
                    {{ $entrega->equipo->modelo->nombre ?? '' }}
                </td>
                <td class="px-4 py-3">
                    <x-badge :color="$entrega->tipo === 'prestamo' ? 'blue' : 'purple'">
                        {{ ucfirst($entrega->tipo) }}
                    </x-badge>
                </td>
                <td class="px-4 py-3">{{ $entrega->user->nombre_completo ?? '—' }}</td>
                <td class="px-4 py-3">{{ $entrega->oficina->nombre ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $entrega->fecha_entrega?->format('d/m/Y H:i') ?? '—' }}</td>
                <td class="px-4 py-3">
                    <x-badge :color="$entrega->status === 'activa' ? 'yellow' : 'green'">
                        {{ ucfirst($entrega->status) }}
                    </x-badge>
                </td>
                <td class="px-4 py-3">
                    @if ($user->esAdmin() && $entrega->status === 'activa')
                        <form method="POST" action="{{ route('entregas.devolver', $entrega) }}"
                              onsubmit="return confirm('¿Registrar devolución del equipo {{ $entrega->equipo->serial }}?')">
                            @csrf @method('PUT')
                            <button type="submit" class="text-green-600 hover:underline text-sm">Devolver</button>
                        </form>
                    @else
                        <span class="text-xs text-gray-400">—</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </x-table>

    <div class="mt-4">{{ $entregas->links() }}</div>
@endif
@endsection