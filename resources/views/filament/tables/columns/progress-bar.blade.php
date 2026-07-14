@php
    $progress = $getState();
@endphp

@if ($progress)
    @php
        $pct = max(0, min(100, (float) ($progress['progress'] ?? 0)));
        $fill = ($progress['color'] ?? 'primary') === 'warning' ? '#f59e0b' : '#2563eb';
    @endphp
    <div style="width:100%;background-color:#e5e7eb;border-radius:9999px;height:10px;overflow:hidden;">
        <div style="height:10px;border-radius:9999px;background-color:{{ $fill }};width:{{ $pct }}%;transition:width .3s ease;"></div>
    </div>
    <div class="text-sm mt-1">
        {{ number_format($pct, 0) }}% complete
    </div>
@else
    <div class="text-sm text-gray-500">
        No data available
    </div>
@endif
