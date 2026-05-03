@props([
    'value'   => 0,    {{-- 0-100 --}}
    'max'     => 100,
    'label'   => null,
    'color'   => null, {{-- null=primary | success | warning | destructive --}}
    'size'    => 'md', {{-- sm | md | lg --}}
    'animate' => false,
])

@php
$percent = min(100, max(0, ($value / $max) * 100));
$colors = [
    null          => 'bg-primary',
    'success'     => 'bg-green-500',
    'warning'     => 'bg-yellow-500',
    'destructive' => 'bg-destructive',
    'info'        => 'bg-blue-500',
];
$sizes = ['sm' => 'h-1', 'md' => 'h-2', 'lg' => 'h-3'];
@endphp

<div {{ $attributes }}>
    @if($label)
        <div class="flex justify-between text-xs text-muted-foreground mb-1.5">
            <span>{{ $label }}</span>
            <span>{{ round($percent) }}%</span>
        </div>
    @endif
    <div class="w-full overflow-hidden rounded-full bg-secondary {{ $sizes[$size] ?? $sizes['md'] }}">
        <div
            class="h-full rounded-full transition-all duration-500 ease-out {{ $colors[$color] ?? $colors[null] }} {{ $animate ? 'animate-pulse' : '' }}"
            style="width: {{ $percent }}%"
            role="progressbar"
            aria-valuenow="{{ $value }}"
            aria-valuemin="0"
            aria-valuemax="{{ $max }}"
        ></div>
    </div>
</div>
