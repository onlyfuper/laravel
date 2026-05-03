@props([
    'variant' => 'default', {{-- default | destructive | success | warning | info --}}
    'title'   => null,
    'icon'    => null,
])

@php
$variants = [
    'default'     => 'bg-background border-border text-foreground',
    'destructive' => 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300 [&_[data-alert-icon]]:text-red-500',
    'success'     => 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300 [&_[data-alert-icon]]:text-green-500',
    'warning'     => 'bg-yellow-50 border-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-300 [&_[data-alert-icon]]:text-yellow-500',
    'info'        => 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-300 [&_[data-alert-icon]]:text-blue-500',
];
@endphp

<div {{ $attributes->class([
    'relative w-full rounded-2xl border p-4',
    $variants[$variant] ?? $variants['default'],
]) }} role="alert">
    <div class="flex gap-3">
        @if($icon)
            <span data-alert-icon class="mt-0.5 shrink-0">
                <x-icon :name="$icon" class="size-4" />
            </span>
        @endif
        <div class="flex-1 min-w-0">
            @if($title)
                <div class="font-medium text-sm mb-1">{{ $title }}</div>
            @endif
            <div class="text-sm leading-relaxed">{{ $slot }}</div>
        </div>
    </div>
</div>
