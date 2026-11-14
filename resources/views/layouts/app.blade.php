<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'SigedTec') }} — @yield('title', 'Panel')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex">

            {{-- Sidebar --}}
            @include('layouts.partials.sidebar')

            {{-- Contenido --}}
            <div class="flex-1 flex flex-col">

                {{-- Navbar superior --}}
                @include('layouts.partials.navbar')

                {{-- Mensajes flash --}}
                <div class="px-6 pt-4">
                    @if (session('success'))
                        <x-alert type="success" :message="session('success')" />
                    @endif
                    @if (session('error'))
                        <x-alert type="error" :message="session('error')" />
                    @endif
                </div>

                {{-- Contenido principal --}}
                <main class="flex-1 p-6">
                    @yield('content')
                </main>

                {{-- Footer --}}
                <footer class="text-center text-xs text-gray-500 py-3">
                    SigedTec &copy; {{ date('Y') }} — Sistema de Gestión de Inventario y Despacho Tecnológico
                </footer>
            </div>
        </div>
    </body>
</html>