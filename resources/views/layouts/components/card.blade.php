@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm border border-gray-200 p-5']) }}>
    @if ($title)
        <h3 class="font-semibold text-gray-800 mb-4">{{ $title }}</h3>
    @endif
    {{ $slot }}
</div>