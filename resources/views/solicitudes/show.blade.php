@extends('layouts.app')

@section('title', 'Detalle de solicitud')

@section('content')
@php $user = auth()->user(); @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Info de la solicitud --}}
    <x-card title="Solicitud #{{ $solicitud->id }}" class="lg:col-span-2">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Solicitante</dt>
                <dd class="font-medium">{{ $solicitud->user->nombre_completo ?? '—' }}</dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Oficina</dt>
                <dd class="font-medium">{{ $solicitud->oficina->nombre ?? '—' }}</dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Categoría requerida</dt>
                <dd>
                    <x-badge color="indigo">{{ $solicitud->categoria->nombre ?? '—' }}</x-badge>
                </dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Tipo</dt>
                <dd>
                    <x-badge :color="$solicitud->tipo === 'prestamo' ? 'blue' : 'purple'">
                        {{ ucfirst($solicitud->tipo) }}
                    </x-badge>
                </dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Cantidad</dt>
                <dd class="font-medium">{{ $solicitud->cantidad }}</dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Fecha requerida</dt>
                <dd>{{ $solicitud->fecha_requerida?->format('d/m/Y') ?? '—' }}</dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Fecha fin / devolución</dt>
                <dd>{{ $solicitud->fecha_fin?->format('d/m/Y') ?? '—' }}</dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Status</dt>
                <dd>
                    <x-badge :color="match($solicitud->status) {
                        'pendiente' => 'yellow',
                        'aprobada'  => 'green',
                        'rechazada' => 'red',
                        default     => 'gray',
                    }">{{ ucfirst($solicitud->status) }}</x-badge>
                </dd>
            </div>
            <div class="border-b pb-2">
                <dt class="text-gray-500 text-xs uppercase">Fecha de solicitud</dt>
                <dd>{{ $solicitud->fecha_solicitud?->format('d/m/Y') ?? '—' }}</dd>
            </div>
        </dl>

        @if ($solicitud->motivo_rechazo)
            <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-3 rounded text-sm">
                <p class="font-medium text-red-800">Motivo del rechazo:</p>
                <p class="text-red-700">{{ $solicitud->motivo_rechazo }}</p>
            </div>
        @endif

        {{-- Botones: Volver + Cancelar (solicitante) + Eliminar (admin) --}}
        <div class="flex gap-2 mt-6 flex-wrap">
            <x-button :href="route('solicitudes.index')" variant="secondary">Volver</x-button>

            @if ($user->esSolicitante() && $solicitud->user_id === $user->id && $solicitud->esEditable())
                <form method="POST" action="{{ route('solicitudes.destroy', $solicitud) }}"
                      onsubmit="return confirm('¿Cancelar esta solicitud?')">
                    @csrf @method('DELETE')
                    <x-button type="submit" variant="danger">Cancelar solicitud</x-button>
                </form>
            @endif

            @if ($user->esAdmin() && $solicitud->status !== 'aprobada')
                <form method="POST" action="{{ route('solicitudes.destroy', $solicitud) }}"
                      onsubmit="return confirm('¿Eliminar esta solicitud?')">
                    @csrf @method('DELETE')
                    <x-button type="submit" variant="danger">Eliminar</x-button>
                </form>
            @endif
        </div>
    </x-card>

    {{-- Acciones del admin --}}
    @if ($user->esAdmin() && $solicitud->status === 'pendiente')
        <x-card title="Aprobar solicitud">
            @if ($equiposDisponibles->isEmpty())
                <p class="text-sm text-gray-500">
                    No hay equipos disponibles de la categoría
                    <strong>{{ $solicitud->categoria->nombre ?? '—' }}</strong>.
                </p>
            @else
                <form method="POST" action="{{ route('solicitudes.aprobar', $solicitud) }}" class="space-y-3">
                    @csrf @method('PUT')

                    <p class="text-xs text-gray-500">
                        Selecciona exactamente <strong>{{ $solicitud->cantidad }}</strong> equipo(s)
                        de la categoría <strong>{{ $solicitud->categoria->nombre ?? '—' }}</strong>:
                    </p>

                    <div class="max-h-64 overflow-y-auto border rounded-lg p-2 space-y-1">
                        @foreach ($equiposDisponibles as $equipo)
                            <label class="flex items-center gap-2 text-xs cursor-pointer hover:bg-gray-50 p-1 rounded">
                                <input type="checkbox" name="equipo_ids[]" value="{{ $equipo->id }}">
                                <span class="font-mono">{{ $equipo->serial }}</span>
                                <span class="text-gray-500">
                                    — {{ $equipo->modelo->marca->nombre ?? '?' }}
                                    {{ $equipo->modelo->nombre ?? '?' }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    @error('equipo_ids') <p class="text-xs text-red-600">{{ $message }}</p> @enderror

                    <x-button type="submit" variant="success">Aprobar y asignar</x-button>
                </form>

                <hr class="my-4">

                <form method="POST" action="{{ route('solicitudes.rechazar', $solicitud) }}" class="space-y-3">
                    @csrf @method('PUT')

                    <label class="block text-sm font-medium text-gray-700">Motivo del rechazo *</label>
                    <textarea name="motivo_rechazo" rows="2"
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              required>{{ old('motivo_rechazo') }}</textarea>
                    @error('motivo_rechazo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror

                    <x-button type="submit" variant="danger">Rechazar</x-button>
                </form>
            @endif
        </x-card>
    @endif

</div>

{{-- Equipos asignados --}}
@if ($solicitud->equipos->isNotEmpty())
    <x-card title="Equipos asignados" class="mt-4">
        <x-table :headers="['Serial', 'Código', 'Modelo', 'Marca', 'Categoría']">
            @foreach ($solicitud->equipos as $equipo)
                <tr>
                    <td class="px-4 py-3 font-mono text-xs">{{ $equipo->serial }}</td>
                    <td class="px-4 py-3 font-mono text-xs">{{ $equipo->codigo_patrimonial ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $equipo->modelo->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $equipo->modelo->marca->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <x-badge color="indigo">{{ $equipo->modelo->categoria->nombre ?? '—' }}</x-badge>
                    </td>
                </tr>
            @endforeach
        </x-table>
    </x-card>
@endif
@endsection