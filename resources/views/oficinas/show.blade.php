@extends('layouts.app')

@section('title', 'Detalle de oficina')

@section('content')
<x-card title="Oficina: {{ $oficina->nombre }}" class="max-w-xl">
    <dl class="space-y-2 text-sm">
        <div class="flex justify-between border-b py-2">
            <dt class="text-gray-600">ID</dt>
            <dd class="font-medium">{{ $oficina->id }}</dd>
        </div>
        <div class="flex justify-between border-b py-2">
            <dt class="text-gray-600">Nombre</dt>
            <dd class="font-medium">{{ $oficina->nombre }}</dd>
        </div>
        <div class="flex justify-between border-b py-2">
            <dt class="text-gray-600">Ubicación</dt>
            <dd class="font-medium">{{ $oficina->ubicacion ?? '—' }}</dd>
        </div>
        <div class="flex justify-between border-b py-2">
            <dt class="text-gray-600">Usuarios asociados</dt>
            <dd class="font-medium">{{ $oficina->users_count ?? $oficina->users->count() }}</dd>
        </div>
        <div class="flex justify-between py-2">
            <dt class="text-gray-600">Solicitudes</dt>
            <dd class="font-medium">{{ $oficina->solicitudes_count ?? $oficina->solicitudes->count() }}</dd>
        </div>
    </dl>

    <div class="flex gap-2 mt-6">
        <x-button :href="route('oficinas.edit', $oficina)" variant="warning">Editar</x-button>
        <x-button :href="route('oficinas.index')" variant="secondary">Volver</x-button>
    </div>
</x-card>
@endsection