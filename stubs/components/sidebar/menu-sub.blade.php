@props(['class' => ''])

<ul
    x-show="submenuOpen && $data.state === 'expanded'"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    data-slot="sidebar-menu-sub"
    data-sidebar="menu-sub"
    class="relative before:absolute before:left-4.5 before:top-0 before:bottom-8 before:w-[2px] before:bg-muted flex min-w-0 translate-x-px flex-col gap-1 pl-8 py-2 {{ $class }}"
>
    {{ $slot }}
</ul>
