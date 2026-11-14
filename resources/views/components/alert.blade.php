@props(['type' => 'info', 'message' => ''])

@php
    $colors = [
        'success' => 'bg-green-100 border-green-400 text-green-800',
        'error'   => 'bg-red-100 border-red-400 text-red-800',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-800',
        'info'    => 'bg-blue-100 border-blue-400 text-blue-800',
    ];
    $icons = [
        'success' => '✅', 'error' => '❌', 'warning' => '⚠️', 'info' => 'ℹ️',
    ];
@endphp

<div class="border-l-4 px-4 py-3 rounded mb-3 {{ $colors[$type] ?? $colors['info'] }}">
    <div class="flex items-center gap-2">
        <span>{{ $icons[$type] ?? 'ℹ️' }}</span>
        <span class="text-sm">{{ $message ?: $slot }}</span>
    </div>
</div>