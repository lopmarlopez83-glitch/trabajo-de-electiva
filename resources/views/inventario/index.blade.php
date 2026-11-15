@extends('layouts.app')

@section('title', 'Inventario')

@section('content')
{{-- Resumen --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <x-card>
        <p class="text-xs text-gray-500 uppercase">Disponibles</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $resumen['disponible'] }}</p>
    </x-card>
    <x-card>
        <p class="text-xs text-gray-500 uppercase">En mantenimiento</p>
        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $resumen['en_mantenimiento'] }}</p>
    </x-card>
    <x-card>
        <p class="text-xs text-gray-500 uppercase">Prestados</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $resumen['prestado'] }}</p>
    </x-card>
    <x-card>
        <p class="text-xs text-gray-500 uppercase">Asignados</p>
        <p class="text-2xl font-bold text-purple-600 mt-1">{{ $resumen['asignado'] }}</p>
    </x-card>
</div>

{{-- Filtros --}}
<x-card class="mb-4">
    <form method="GET" action="{{ route('inventario.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            {{-- ✅ Quitamos la mención al código patrimonial --}}
            <label class="block text-xs font-medium text-gray-600 mb-1">Buscar (por serial)</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
            <select name="status"
                    class="border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">— Todos —</option>
                <option value="disponible" @selected(request('status') === 'disponible')>Disponible</option>
                <option value="en_mantenimiento" @selected(request('status') === 'en_mantenimiento')>En mantenimiento</option>
                <option value="prestado" @selected(request('status') === 'prestado')>Prestado</option>
                <option value="asignado" @selected(request('status') === 'asignado')>Asignado</option>
            </select>
        </div>

        <div class="flex gap-2">
            <x-button type="submit">Filtrar</x-button>
            <x-button :href="route('inventario.index')" variant="secondary">Limpiar</x-button>
        </div>
    </form>
</x-card>

{{-- Tabla --}}
@if ($equipos->isEmpty())
    <x-card><p class="text-sm text-gray-500">No se encontraron equipos con esos filtros.</p></x-card>
@else
    {{-- ✅ Coinciden exactamente 6 columnas en el header y 6 en el body --}}
    <x-table :headers="['#', 'Serial', 'Modelo', 'Marca', 'Categoría', 'Status']">
        @foreach ($equipos as $equipo)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-600">{{ $equipo->id }}</td>
                <td class="px-4 py-3 font-mono text-xs">{{ $equipo->serial }}</td>
                <td class="px-4 py-3">{{ $equipo->modelo->nombre ?? '—' }}</td>
                <td class="px-4 py-3">{{ $equipo->modelo->marca->nombre ?? '—' }}</td>
                <td class="px-4 py-3">
                    <x-badge color="indigo">{{ $equipo->modelo->categoria->nombre ?? '—' }}</x-badge>
                </td>
                <td class="px-4 py-3">
                    <x-badge :color="match($equipo->status) {
                        'disponible'       => 'green',
                        'en_mantenimiento' => 'yellow',
                        'prestado'         => 'blue',
                        'asignado'         => 'purple',
                        default            => 'gray',
                    }">{{ ucfirst(str_replace('_', ' ', $equipo->status)) }}</x-badge>
                </td>
            </tr>
        @endforeach
    </x-table>

    <div class="mt-4">{{ $equipos->links() }}</div>
@endif
@endsection