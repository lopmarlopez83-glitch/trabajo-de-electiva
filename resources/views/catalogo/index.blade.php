@extends('layouts.app')

@section('title', 'Catálogo')

@section('content')
<div x-data="{ tab: '{{ request('tab', 'modelos') }}' }" class="space-y-4">

    {{-- Tabs --}}
    <div class="flex gap-2 border-b border-gray-200">
        <button @click="tab = 'categorias'"
                :class="tab === 'categorias' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 text-sm font-medium transition">
            Categorías ({{ $categorias->count() }})
        </button>
        <button @click="tab = 'marcas'"
                :class="tab === 'marcas' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 text-sm font-medium transition">
            Marcas ({{ $marcas->count() }})
        </button>
        <button @click="tab = 'modelos'"
                :class="tab === 'modelos' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 text-sm font-medium transition">
            Modelos
        </button>
    </div>

    {{-- ==================== TAB CATEGORÍAS ==================== --}}
    <div x-show="tab === 'categorias'" x-cloak>
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Categorías</h2>
            <x-button :href="route('catalogo.categorias.create')">+ Nueva categoría</x-button>
        </div>

        @if ($categorias->isEmpty())
            <x-card><p class="text-sm text-gray-500">No hay categorías aún.</p></x-card>
        @else
            <x-table :headers="['#', 'Nombre', 'Descripción', 'Modelos', 'Acciones']">
                @foreach ($categorias as $cat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">{{ $cat->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $cat->nombre }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $cat->descripcion ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <x-badge color="indigo">{{ $cat->modelos_count }}</x-badge>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('catalogo.categorias.edit', $cat) }}" class="text-yellow-600 hover:underline text-sm">Editar</a>
                                <form method="POST" action="{{ route('catalogo.categorias.destroy', $cat) }}"
                                      onsubmit="return confirm('¿Eliminar la categoría {{ $cat->nombre }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @endif
    </div>

    {{-- ==================== TAB MARCAS ==================== --}}
    <div x-show="tab === 'marcas'" x-cloak>
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Marcas</h2>
            <x-button :href="route('catalogo.marcas.create')">+ Nueva marca</x-button>
        </div>

        @if ($marcas->isEmpty())
            <x-card><p class="text-sm text-gray-500">No hay marcas aún.</p></x-card>
        @else
            <x-table :headers="['#', 'Nombre', 'Modelos', 'Acciones']">
                @foreach ($marcas as $marca)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">{{ $marca->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $marca->nombre }}</td>
                        <td class="px-4 py-3">
                            <x-badge color="purple">{{ $marca->modelos_count }}</x-badge>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('catalogo.marcas.edit', $marca) }}" class="text-yellow-600 hover:underline text-sm">Editar</a>
                                <form method="POST" action="{{ route('catalogo.marcas.destroy', $marca) }}"
                                      onsubmit="return confirm('¿Eliminar la marca {{ $marca->nombre }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @endif
    </div>

    {{-- ==================== TAB MODELOS ==================== --}}
    <div x-show="tab === 'modelos'" x-cloak>
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Modelos</h2>
            <x-button :href="route('catalogo.modelos.create')">+ Nuevo modelo</x-button>
        </div>

        @if ($modelos->isEmpty())
            <x-card><p class="text-sm text-gray-500">No hay modelos aún.</p></x-card>
        @else
            <x-table :headers="['#', 'Modelo', 'Marca', 'Categoría', 'Acciones']">
                @foreach ($modelos as $modelo)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">{{ $modelo->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $modelo->nombre }}</td>
                        <td class="px-4 py-3">{{ $modelo->marca->nombre ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <x-badge color="indigo">{{ $modelo->categoria->nombre ?? '—' }}</x-badge>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('catalogo.modelos.edit', $modelo) }}" class="text-yellow-600 hover:underline text-sm">Editar</a>
                                <form method="POST" action="{{ route('catalogo.modelos.destroy', $modelo) }}"
                                      onsubmit="return confirm('¿Eliminar el modelo {{ $modelo->nombre }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>
            <div class="mt-4">{{ $modelos->links() }}</div>
        @endif
    </div>

</div>
@endsection