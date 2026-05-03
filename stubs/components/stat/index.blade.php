@props([
    'label'  => '',
    'value'  => '',
    'icon'   => null,
    'trend'  => null,   {{-- '+12%' | '-3%' --}}
    'desc'   => null,
    'color'  => null,   {{-- null | success | warning | destructive | info --}}
])

@php
$iconColors = [
    null          => 'bg-primary/10 text-primary',
    'success'     => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
    'warning'     => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400',
    'destructive' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
    'info'        => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
];
$trendPositive = $trend && str_starts_with($trend, '+');
$trendNegative = $trend && str_starts_with($trend, '-');
@endphp

<div {{ $attributes->class(['rounded-2xl border bg-card p-5 flex items-start gap-4']) }}>
    @if($icon)
        <div class="shrink-0 rounded-xl p-2.5 {{ $iconColors[$color] ?? $iconColors[null] }}">
            <x-icon :name="$icon" class="size-5" />
        </div>
    @endif

    <div class="flex-1 min-w-0">
        <div class="text-sm text-muted-foreground mb-1">{{ $label }}</div>
        <div class="text-2xl font-bold tracking-tight text-foreground">{{ $value }}</div>
        @if($trend || $desc)
            <div class="flex items-center gap-2 mt-1.5 text-xs">
                @if($trend)
                    <span class="{{ $trendPositive ? 'text-green-600 dark:text-green-400' : ($trendNegative ? 'text-red-500' : 'text-muted-foreground') }} font-medium">
                        {{ $trend }}
                    </span>
                @endif
                @if($desc)
                    <span class="text-muted-foreground">{{ $desc }}</span>
                @endif
            </div>
        @endif
        {{ $slot }}
    </div>
</div>
