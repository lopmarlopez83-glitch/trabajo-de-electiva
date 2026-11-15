@extends('layouts.app')

@section('title', 'Solicitudes')

@section('content')
@php $user = auth()->user(); @endphp

<div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold text-gray-800">
        {{ $user->esAdmin() ? 'Todas las solicitudes' : 'Mis solicitudes' }}
    </h2>
    @if ($user->esSolicitante())
        <x-button :href="route('solicitudes.create')">+ Nueva solicitud</x-button>
    @endif
</div>

<x-card class="mb-4">
    <form method="GET" action="{{ route('solicitudes.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
            <select name="status"
                    class="border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">— Todos —</option>
                <option value="pendiente" @selected(request('status') === 'pendiente')>Pendiente</option>
                <option value="aprobada" @selected(request('status') === 'aprobada')>Aprobada</option>
                <option value="rechazada" @selected(request('status') === 'rechazada')>Rechazada</option>
            </select>
        </div>

        <div class="flex gap-2">
            <x-button type="submit">Filtrar</x-button>
            <x-button :href="route('solicitudes.index')" variant="secondary">Limpiar</x-button>
        </div>
    </form>
</x-card>

@if ($solicitudes->isEmpty())
    <x-card><p class="text-sm text-gray-500">No hay solicitudes registradas.</p></x-card>
@else
    <x-table :headers="['#', 'Solicitante', 'Oficina', 'Categoría', 'Tipo', 'Cantidad', 'F. requerida', 'Status', 'Acciones']">
        @foreach ($solicitudes as $s)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-600">{{ $s->id }}</td>
                <td class="px-4 py-3">{{ $s->user->nombre_completo ?? '—' }}</td>
                <td class="px-4 py-3">{{ $s->oficina->nombre ?? '—' }}</td>
                <td class="px-4 py-3">
                    <x-badge color="indigo">{{ $s->categoria->nombre ?? '—' }}</x-badge>
                </td>
                <td class="px-4 py-3">
                    <x-badge :color="$s->tipo === 'prestamo' ? 'blue' : 'purple'">
                        {{ ucfirst($s->tipo) }}
                    </x-badge>
                </td>
                <td class="px-4 py-3 text-center">{{ $s->cantidad }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $s->fecha_requerida?->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3">
                    <x-badge :color="match($s->status) {
                        'pendiente' => 'yellow',
                        'aprobada'  => 'green',
                        'rechazada' => 'red',
                        default     => 'gray',
                    }">{{ ucfirst($s->status) }}</x-badge>
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('solicitudes.show', $s) }}" class="text-blue-600 hover:underline text-sm">Ver</a>

                        {{-- Solicitante: editar/cancelar solo si es dueño y está pendiente --}}
                        @if ($user->esSolicitante() && $s->user_id === $user->id && $s->esEditable())
                            <a href="{{ route('solicitudes.edit', $s) }}" class="text-yellow-600 hover:underline text-sm">Editar</a>
                            <form method="POST" action="{{ route('solicitudes.destroy', $s) }}"
                                  onsubmit="return confirm('¿Cancelar la solicitud #{{ $s->id }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">Cancelar</button>
                            </form>
                        @endif

                        {{-- Admin: eliminar cualquier solicitud excepto aprobada --}}
                        @if ($user->esAdmin() && $s->status !== 'aprobada')
                            <form method="POST" action="{{ route('solicitudes.destroy', $s) }}"
                                  onsubmit="return confirm('¿Eliminar la solicitud #{{ $s->id }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">Eliminar</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </x-table>

    <div class="mt-4">{{ $solicitudes->links() }}</div>
@endif
@endsection