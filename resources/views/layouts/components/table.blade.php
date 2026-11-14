@props(['headers' => []])

<div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        @if (count($headers))
            <thead class="bg-gray-50">
                <tr>
                    @foreach ($headers as $header)
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-gray-100">
            {{ $slot }}
        </tbody>
    </table>
</div>