@props([
    'default' => null,
    'class'   => '',
])

<div
    x-data="{ activeTab: '{{ $default }}' }"
    {{ $attributes->class([$class]) }}
>{{ $slot }}</div>
