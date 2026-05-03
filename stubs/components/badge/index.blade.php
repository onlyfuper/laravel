@props([
    'variant' => 'default', {{-- default | secondary | destructive | success | warning | outline --}}
    'size'    => 'md',       {{-- sm | md --}}
])

@php
$variants = [
    'default'     => 'bg-primary text-primary-foreground',
    'secondary'   => 'bg-secondary text-secondary-foreground',
    'destructive' => 'bg-destructive text-destructive-foreground',
    'success'     => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
    'warning'     => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
    'info'        => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
    'outline'     => 'border border-border text-foreground bg-transparent',
];
$sizes = [
    'sm' => 'px-1.5 py-0.5 text-[10px]',
    'md' => 'px-2.5 py-0.5 text-xs',
];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center gap-1 rounded-full font-medium',
    $variants[$variant] ?? $variants['default'],
    $sizes[$size] ?? $sizes['md'],
]) }}>{{ $slot }}</span>
