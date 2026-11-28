@php
    // Unique per copy: several strips share the page, and a repeated SVG pattern id is
    // invalid HTML (the copies could all resolve to the first pattern).
    $pattern_id = 'blocky-background-'.\Illuminate\Support\Str::uuid();
@endphp

{{-- Blocky background: a 240px decorative strip (faded grid with two filled squares) placed
     between homepage sections. --}}
<div {{ $attributes->merge(['class' => 'relative isolate -z-10 h-[240px]']) }}>
    <div class="absolute inset-x-0 top-1/2 -z-10 flex -translate-y-1/2 justify-center overflow-hidden mask-[radial-gradient(75%_60%_at_50%_60%,white,transparent)] mt-30 sm:mt-0">
        <svg aria-hidden="true" class="h-160 w-7xl flex-none stroke-gray-200 dark:stroke-white/10">
            <defs>
                <pattern id="{{ $pattern_id }}" width="200" height="200" x="50%" y="25%" patternUnits="userSpaceOnUse" patternTransform="translate(-100 0)">
                    <path d="M.5 200V.5H200" fill="none" />
                </pattern>
            </defs>

            <svg x="50%" y="25%" class="overflow-visible fill-gray-300 dark:fill-gray-700">
                <path d="M-300 0h201v201h-201Z M300 200h201v201h-201Z" stroke-width="0" />
            </svg>

            <rect width="100%" height="100%" fill="url(#{{ $pattern_id }})" stroke-width="0" />
        </svg>
    </div>
</div>
