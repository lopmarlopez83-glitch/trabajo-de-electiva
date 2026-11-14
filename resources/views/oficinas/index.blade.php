@extends('layouts.app')

@section('title', 'Oficinas')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold text-gray-800">Listado de Oficinas</h2>
    <x-button :href="route('oficinas.create')">+ Nueva oficina</x-button>
</div>

@if ($oficinas->isEmpty())
    <x-card>
        <p class="text-sm text-gray-500">No hay oficinas registradas aún.</p>
    </x-card>
@else
    <x-table :headers="['#', 'Nombre', 'Ubicación', 'Acciones']">
        @foreach ($oficinas as $oficina)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-600">{{ $oficina->id }}</td>
                <td class="px-4 py-3 font-medium">{{ $oficina->nombre }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $oficina->ubicacion ?? '—' }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('oficinas.show', $oficina) }}" class="text-blue-600 hover:underline text-sm">Ver</a>
                        <a href="{{ route('oficinas.edit', $oficina) }}" class="text-yellow-600 hover:underline text-sm">Editar</a>
                        <form method="POST" action="{{ route('oficinas.destroy', $oficina) }}"
                              onsubmit="return confirm('¿Eliminar la oficina {{ $oficina->nombre }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-table>

    <div class="mt-4">{{ $oficinas->links() }}</div>
@endif
@endsection