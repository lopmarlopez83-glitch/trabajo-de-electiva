@php $user = auth()->user(); @endphp

<aside class="w-64 bg-slate-900 text-slate-100 min-h-screen flex flex-col">
    <div class="p-4 border-b border-slate-700">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-wide">SigedTec</a>
        <p class="text-xs text-slate-400 mt-1">Gestión de Inventario</p>
    </div>

    <nav class="flex-1 p-3 space-y-1 text-sm">

        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="🏠">
            Dashboard
        </x-nav-link>

        @if ($user->esAdmin())
            <p class="px-3 pt-4 text-xs uppercase text-slate-500">Administración</p>
            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" icon="👥">Usuarios</x-nav-link>
            <x-nav-link :href="route('oficinas.index')" :active="request()->routeIs('oficinas.*')" icon="🏢">Oficinas</x-nav-link>
            <x-nav-link :href="route('catalogo.index')" :active="request()->routeIs('catalogo.*')" icon="🏷️">Catálogo</x-nav-link>
            <x-nav-link :href="route('equipos.index')" :active="request()->routeIs('equipos.*')" icon="💻">Equipos</x-nav-link>
            <x-nav-link :href="route('inventario.index')" :active="request()->routeIs('inventario.*')" icon="📦">Inventario</x-nav-link>
        @endif

        <p class="px-3 pt-4 text-xs uppercase text-slate-500">Operaciones</p>
        <x-nav-link :href="route('solicitudes.index')" :active="request()->routeIs('solicitudes.*')" icon="📝">Solicitudes</x-nav-link>
        <x-nav-link :href="route('entregas.index')" :active="request()->routeIs('entregas.*')" icon="🚚">Entregas</x-nav-link>

        {{-- ✅ Botón "Nueva solicitud" visible para admin y solicitante --}}
        <div class="pt-4">
            <a href="{{ route('solicitudes.create') }}"
               class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg py-2 text-sm font-medium transition">
                + Nueva solicitud
            </a>
        </div>

    </nav>

    <div class="p-3 border-t border-slate-700 text-xs text-slate-400">
        v1.0 — {{ $user->nombre_completo }}
    </div>
</aside>