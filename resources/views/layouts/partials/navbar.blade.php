<header class="bg-white shadow-sm border-b">
    <div class="flex items-center justify-between px-6 py-3">
        <h1 class="text-lg font-semibold text-gray-800">
            @yield('title', 'Panel')
        </h1>

        <div class="flex items-center gap-4">
            <div class="text-right text-sm">
                <p class="font-medium text-gray-800">{{ auth()->user()->nombre_completo }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
            </div>

            <a href="{{ route('perfil.edit') }}"
               class="text-sm text-indigo-600 hover:underline">Mi perfil</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-600 hover:underline">Cerrar sesión</button>
            </form>
        </div>
    </div>
</header>