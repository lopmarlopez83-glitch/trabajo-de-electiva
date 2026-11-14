@php
    $classes = $active
        ? 'bg-indigo-600 text-white'
        : 'text-slate-300 hover:bg-slate-700 hover:text-white';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => "flex items-center gap-2 px-3 py-2 rounded-lg transition $classes"]) }}>
    @if ($icon)<span>{{ $icon }}</span>@endif
    {{ $slot }}
</a>
