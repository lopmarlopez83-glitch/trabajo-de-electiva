@props(['href' => null, 'variant' => 'primary', 'type' => 'button'])

@php
    $base = 'inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition';
    $variants = [
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 text-white',
        'danger'  => 'bg-red-600 hover:bg-red-700 text-white',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white',
    ];
    $class = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</button>
@endif