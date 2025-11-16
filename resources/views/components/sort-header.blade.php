@php
    $sort = request('sort');
    $direction = request('direction', 'asc');
    $newDirection = $direction === 'asc' ? 'desc' : 'asc';
    $isActive = $sort === $column;
    $icon = '';
    if ($isActive) {
        $icon = $direction === 'asc' ? '↑' : '↓';
    }
@endphp

<a href="{{ request()->fullUrlWithQuery(['sort' => $column, 'direction' => $newDirection]) }}"
    class="flex items-center gap-2 {{ $isActive ? 'text-blue-600 font-semibold' : 'text-gray-500 hover:text-gray-700' }} transition">
    <span>{{ $label }}</span>
    @if ($isActive)
        <span class="text-blue-600">{{ $icon }}</span>
    @else
        <span class="text-gray-400">⇅</span>
    @endif
</a>
