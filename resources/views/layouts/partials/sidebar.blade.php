@php $user = auth()->user(); @endphp

{{-- ✅ Reducido de w-64 a w-52 para hacerlo más angosto --}}
<aside class="w-52 bg-slate-900 text-slate-100 min-h-screen flex flex-col">
    {{-- Header --}}
    <div class="px-4 py-3 border-b border-slate-700">
        <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-wide">SigedTec</a>
        <p class="text-[11px] text-slate-400 mt-0.5">Gestión de Inventario</p>
    </div>

    {{-- Navegación --}}
    <nav class="flex-1 px-2 py-2 space-y-0.5 text-sm">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="🏠">
            Dashboard
        </x-nav-link>

        @if ($user->esAdmin())
            <p class="px-3 pt-3 pb-1 text-[10px] uppercase tracking-wider text-slate-500">Administración</p>
            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" icon="👥">Usuarios</x-nav-link>
            <x-nav-link :href="route('oficinas.index')" :active="request()->routeIs('oficinas.*')" icon="🏢">Oficinas</x-nav-link>
            <x-nav-link :href="route('catalogo.index')" :active="request()->routeIs('catalogo.*')" icon="🏷️">Catálogo</x-nav-link>
            <x-nav-link :href="route('equipos.index')" :active="request()->routeIs('equipos.*')" icon="💻">Equipos</x-nav-link>
            <x-nav-link :href="route('inventario.index')" :active="request()->routeIs('inventario.*')" icon="📦">Inventario</x-nav-link>
        @endif

        <p class="px-3 pt-3 pb-1 text-[10px] uppercase tracking-wider text-slate-500">Operaciones</p>
        <x-nav-link :href="route('solicitudes.index')" :active="request()->routeIs('solicitudes.*')" icon="📝">Solicitudes</x-nav-link>
        <x-nav-link :href="route('entregas.index')" :active="request()->routeIs('entregas.*')" icon="🚚">Entregas</x-nav-link>

        {{-- Botón "Nueva solicitud" solo para solicitante --}}
        @if ($user->esSolicitante())
            
        @endif

    </nav>

    {{-- Footer --}}
    <div class="px-3 py-2 border-t border-slate-700 text-[11px] text-slate-400 truncate">
        v1.0 — {{ $user->nombre_completo }}
    </div>
</aside>