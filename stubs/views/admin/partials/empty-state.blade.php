<div class="flex flex-col items-center gap-5 text-center max-w-sm mx-auto py-16 px-4">

        {{-- Claude-style stacked icon --}}
        <div class="relative size-16 mb-2">
            <div class="[&_svg]:pointer-events-none [&_svg]:shrink-0 flex size-16 shrink-0 items-center justify-center rounded-xl border bg-card text-foreground before:pointer-events-none before:absolute before:inset-0 before:rounded-[calc(var(--radius-md)-1px)] before:shadow-[0_1px_--theme(--color-black/4%)] dark:before:shadow-[0_-1px_--theme(--color-white/6%)] pointer-events-none absolute bottom-px origin-bottom-left -translate-x-1 -rotate-6 scale-90 shadow-none"></div>
            <div class="[&_svg]:pointer-events-none [&_svg]:shrink-0 flex size-16 shrink-0 items-center justify-center rounded-xl border bg-card text-foreground before:pointer-events-none before:absolute before:inset-0 before:rounded-[calc(var(--radius-md)-1px)] before:shadow-[0_1px_--theme(--color-black/4%)] dark:before:shadow-[0_-1px_--theme(--color-white/6%)] pointer-events-none absolute bottom-px origin-bottom-right translate-x-1 rotate-6 scale-90 shadow-none"></div>
            <div class="[&_svg]:pointer-events-none [&_svg]:shrink-0 relative flex size-16 shrink-0 items-center justify-center rounded-xl border bg-card text-foreground shadow-sm before:pointer-events-none before:absolute before:inset-0 before:rounded-[calc(var(--radius-md)-1px)] before:shadow-[0_1px_--theme(--color-black/4%)] dark:before:shadow-[0_-1px_--theme(--color-white/6%)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 12H16L14 15H10L8 12H2"/>
                    <path d="M5.45 5.11L2 12V18A2 2 0 0 0 4 20H20A2 2 0 0 0 22 18V12L18.55 5.11A2 2 0 0 0 16.76 4H7.24A2 2 0 0 0 5.45 5.11Z"/>
                </svg>
            </div>
        </div>

        <div class="space-y-1.5">
            <h2 class="text-base font-semibold tracking-tight">{{ $title }}</h2>
            @if($description ?? null)
                <p class="text-sm text-muted-foreground leading-relaxed">{{ $description }}</p>
            @endif
        </div>

</div>
