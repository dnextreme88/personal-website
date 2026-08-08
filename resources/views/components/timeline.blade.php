@props([
    'content' => null,
    'is_current' => false,
])

<div class="flex gap-6 md:contents">
    {{-- Left side: vertical bar with a centered circle on it --}}
    <div class="relative transition translate-y-32 opacity-0 intersect-once intersect:translate-y-0 intersect:opacity-100 md:mx-auto [transition:translate_2s,opacity_3s]">
        <div class="h-full w-6 flex items-center justify-center">
            <div class="h-full w-1 {{ $is_current ? 'bg-neon-cyan/50' : 'bg-fuchsia-300 dark:bg-fuchsia-600' }}"></div>
        </div>

        <div class="size-6 absolute top-1/2 -mt-2 rounded-full {{ $is_current ? 'bg-neon-cyan animate-pulse shadow-[0_0_12px_#00f0ff]' : 'bg-fuchsia-400' }}"></div>
    </div>

    {{-- Right side: content --}}
    @if (isset($content))
        <div class="p-4 border-b-2 my-4 mr-auto w-full transition translate-y-32 opacity-0 intersect-once intersect:translate-y-0 intersect:opacity-100 [transition:translate_2s,opacity_3s] {{ $is_current ? 'border-b-neon-cyan' : 'border-b-2 border-b-neon-magenta' }}">
            {{ $content }}
        </div>
    @endif
</div>
