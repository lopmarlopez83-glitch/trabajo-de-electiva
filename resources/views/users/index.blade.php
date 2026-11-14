@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold text-gray-800">Listado de Usuarios</h2>
    <x-button :href="route('users.create')">+ Nuevo usuario</x-button>
</div>

<x-table :headers="['#', 'Nombre', 'Cédula', 'Correo', 'Oficina', 'Rol', 'Acciones']">
    @foreach ($users as $u)
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 text-gray-600">{{ $u->id }}</td>
            <td class="px-4 py-3 font-medium">{{ $u->nombre_completo }}</td>
            <td class="px-4 py-3">{{ $u->cedula }}</td>
            <td class="px-4 py-3 text-gray-600">{{ $u->email }}</td>
            <td class="px-4 py-3">{{ $u->oficina->nombre ?? '—' }}</td>
            <td class="px-4 py-3">
                <x-badge :color="$u->esAdmin() ? 'indigo' : 'gray'">{{ ucfirst($u->role) }}</x-badge>
            </td>
            <td class="px-4 py-3">
                <div class="flex gap-2">
                    <a href="{{ route('users.edit', $u) }}" class="text-yellow-600 hover:underline text-sm">Editar</a>
                    @if ($u->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $u) }}"
                              onsubmit="return confirm('¿Eliminar al usuario {{ $u->nombre_completo }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm">Eliminar</button>
                        </form>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
</x-table>

<div class="mt-4">{{ $users->links() }}</div>
@endsection