@props([
    'class' => '',
])

<div {{ $attributes->class([
    'animate-pulse rounded-md bg-muted',
    $class,
]) }}></div>
