@extends('layouts.app')

@section('title', 'Equipos')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold text-gray-800">Listado de Equipos</h2>
    <x-button :href="route('equipos.create')">+ Nuevo equipo</x-button>
</div>

@if ($equipos->isEmpty())
    <x-card><p class="text-sm text-gray-500">No hay equipos registrados aún.</p></x-card>
@else
    <x-table :headers="['#', 'Serial', 'Modelo', 'Marca', 'Categoría', 'Status', 'Acciones']">
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
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('equipos.show', $equipo) }}" class="text-blue-600 hover:underline text-sm">Ver</a>
                        <a href="{{ route('equipos.edit', $equipo) }}" class="text-yellow-600 hover:underline text-sm">Editar</a>
                        @if (in_array($equipo->status, ['disponible', 'en_mantenimiento']))
                            <form method="POST" action="{{ route('equipos.destroy', $equipo) }}"
                                  onsubmit="return confirm('¿Dar de baja el equipo {{ $equipo->serial }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">Eliminar</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </x-table>

    <div class="mt-4">{{ $equipos->links() }}</div>
@endif
@endsection