{{-- Toast Stack --}}
{{-- Usage from Livewire: $this->dispatch('toast', message: 'Saved!', type: 'success') --}}
{{-- Usage from Alpine: $dispatch('toast', { message: 'Saved!', type: 'success' }) --}}

<div
    x-data="{
        toasts: [],
        add(message, type = 'default', duration = 4000) {
            const id = Date.now();
            this.toasts.push({ id, message, type, duration });
            setTimeout(() => this.remove(id), duration);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    @toast.window="add($event.detail.message ?? $event.detail[0]?.message, $event.detail.type ?? $event.detail[0]?.type)"
    class="fixed bottom-4 right-4 z-[9999] flex flex-col gap-2 w-80 pointer-events-none"
    aria-live="polite"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            :class="{
                'bg-background border border-border text-foreground': toast.type === 'default' || !toast.type,
                'bg-green-50 border border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300': toast.type === 'success',
                'bg-red-50 border border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300': toast.type === 'error',
                'bg-yellow-50 border border-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-300': toast.type === 'warning',
                'bg-blue-50 border border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-300': toast.type === 'info',
            }"
            class="pointer-events-auto relative flex items-start gap-3 rounded-2xl px-4 py-3 shadow-lg text-sm overflow-hidden"
        >
            {{-- Icon --}}
            <span class="mt-0.5 shrink-0">
                <template x-if="toast.type === 'success'">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </template>
                <template x-if="toast.type === 'warning'">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </template>
                <template x-if="toast.type === 'info' || !toast.type || toast.type === 'default'">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </span>

            <span x-text="toast.message" class="flex-1 leading-snug"></span>

            <button @click="remove(toast.id)" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Progress bar --}}
            <div
                class="absolute bottom-0 left-0 h-0.5 w-full"
                :class="{
                    'bg-foreground/15': toast.type === 'default' || !toast.type,
                    'bg-green-500/30': toast.type === 'success',
                    'bg-red-500/30': toast.type === 'error',
                    'bg-yellow-500/30': toast.type === 'warning',
                    'bg-blue-500/30': toast.type === 'info',
                }"
            >
                <div
                    class="h-full origin-left"
                    :class="{
                        'bg-foreground/30': toast.type === 'default' || !toast.type,
                        'bg-green-500/60': toast.type === 'success',
                        'bg-red-500/60': toast.type === 'error',
                        'bg-yellow-500/60': toast.type === 'warning',
                        'bg-blue-500/60': toast.type === 'info',
                    }"
                    :style="`animation: toast-shrink ${toast.duration}ms linear forwards`"
                ></div>
            </div>
        </div>
    </template>
</div>
