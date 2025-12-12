@props([
    'content' => null,
    'is_current' => false,
])

<div class="flex gap-6 md:contents">
    {{-- Left side: vertical bar with a centered circle on it --}}
    <div class="relative transition translate-y-32 opacity-0 intersect-once intersect:translate-y-0 intersect:opacity-100 md:mx-auto [transition:translate_2s,opacity_3s]">
        <div class="h-full w-6 flex items-center justify-center">
            <div class="h-full w-1 {{ $is_current ? 'bg-blue-400 dark:bg-blue-700' : 'bg-gray-400 dark:bg-gray-600' }}"></div>
        </div>

        <div class="size-6 absolute top-1/2 -mt-2 rounded-full {{ $is_current ? 'bg-blue-300 dark:bg-blue-600 animate-pulse' : 'bg-gray-600' }}"></div>
    </div>

    {{-- Right side: content --}}
    @if (isset($content))
        <div class="p-4 border-b-2 my-4 mr-auto w-full transition translate-y-32 opacity-0 intersect-once intersect:translate-y-0 intersect:opacity-100 [transition:translate_2s,opacity_3s] {{ $is_current ? 'border-b-blue-300 dark:border-b-blue-600' : 'border-b-2 border-b-gray-600' }}">
            {{ $content }}
        </div>
    @endif
</div>
