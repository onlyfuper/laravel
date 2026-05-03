@props([
    'name',
    'class' => '',
    'sprite' => asset('static/sprite/sprite.svg'),
])

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
     {{ $attributes->merge(['class' => $class]) }}>
    <use href="{{ url($sprite) }}#{{ $name }}"></use>
</svg>
