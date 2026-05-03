@props(['value'])

<button
    type="button"
    @click="activeTab = '{{ $value }}'"
    :class="activeTab === '{{ $value }}'
        ? 'bg-background text-foreground shadow-sm'
        : 'hover:bg-background/60 hover:text-foreground'"
    {{ $attributes->class(['inline-flex items-center justify-center whitespace-nowrap rounded-lg px-3 py-1.5 text-sm font-medium transition-all focus-visible:outline-none']) }}
>{{ $slot }}</button>
