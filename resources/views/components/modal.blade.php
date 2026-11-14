cat > resources/views/components/modal.blade.php << 'EOF'
@props(['id' => 'modal', 'title' => ''])

<div x-data="{ open: false }" x-on:open-modal-{{ $id }}.window="open = true" x-show="open" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div @click.away="open = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">{{ $title }}</h3>
            <button @click="open = false" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
        </div>
        {{ $slot }}
    </div>
</div>
EOF