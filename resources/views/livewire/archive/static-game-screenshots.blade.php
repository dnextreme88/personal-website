<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Game Screenshots</x-slot>

    @if (count($screenshots) > 0)
        @php
            $first = $screenshots[0];
            $count = str_pad(count($screenshots), 2, '0', STR_PAD_LEFT);
        @endphp

        {{-- Filmstrip gallery: featured frame (glitch swap + HUD readout + ambient glow) over a scrolling thumbnail strip.
             Behaviour lives in resources/js/game-screenshots-gallery.js; keyframes in effects.css; thumbnail states in app.css. --}}
        <div
            x-data="gameScreenshotsGallery(@js($screenshots))"
            x-on:keydown.window="onKey($event)"
            class="mt-8 grid grid-cols-1 gap-3 sm:mt-12"
        >
            {{-- Featured frame --}}
            <div class="relative isolate p-1.5">
                {{-- Ambient glow: a blurred copy of the active screenshot tints the space behind the frame --}}
                <div
                    aria-hidden="true"
                    class="absolute inset-x-[2%] inset-y-[4%] -z-10 bg-cover bg-center blur-3xl saturate-150 opacity-40 dark:opacity-60"
                    x-bind:style="current ? { backgroundImage: 'url(' + encodeURI(current.url) + ')' } : {}"
                    style="background-image: url('{{ $first['url'] }}')"
                ></div>

                <div
                    x-ref="frame"
                    x-bind:class="{ 'is-glitching': isGlitching }"
                    class="screenshot-frame relative aspect-video max-w-full overflow-clip bg-gray-950 border border-cyan-800/50 dark:border-neon-cyan/40 shadow-[0_0_24px_rgba(14,116,144,0.3)] dark:shadow-[0_0_28px_rgba(0,240,255,0.35)]"
                >
                    {{-- Skeleton until the first image has loaded --}}
                    <div
                        x-show="!isFrameLoaded"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0 z-10 content-center skeleton-loader-lg"
                    >
                        <x-loading-indicator
                            :loader_color_bg="'border-neon-cyan'"
                            :loader_color_spin="'border-neon-cyan'"
                            :show_text="true"
                            :size="4"
                            :text="'Loading...'"
                            :text_classes="'text-xl tracking-widest'"
                            :text_color="'text-neon-cyan'"
                        />
                    </div>

                    <img
                        x-ref="frameImage"
                        x-on:click="openPreview()"
                        x-on:load="isFrameLoaded = true"
                        x-bind:src="current?.url"
                        x-bind:alt="current?.filename"
                        src="{{ $first['url'] }}"
                        alt="{{ $first['filename'] }}"
                        title="Open full preview"
                        class="screenshot-frame-image absolute inset-0 cursor-zoom-in object-contain size-full"
                    />

                    {{-- Glitch ghost layers (cyan / magenta); only visible while .is-glitching runs --}}
                    <div
                        aria-hidden="true"
                        class="screenshot-ghost screenshot-ghost-cyan"
                        x-bind:style="current ? { backgroundImage: 'url(' + encodeURI(current.url) + ')' } : {}"
                    ></div>
                    <div
                        aria-hidden="true"
                        class="screenshot-ghost screenshot-ghost-magenta"
                        x-bind:style="current ? { backgroundImage: 'url(' + encodeURI(current.url) + ')' } : {}"
                    ></div>

                    <button
                        x-on:click="prev()"
                        type="button"
                        class="absolute left-2 top-1/2 z-20 -translate-y-1/2 grid place-items-center w-10 h-12 font-loader text-2xl leading-none text-cyan-50 bg-gray-950/55 border border-neon-cyan/40 hover:bg-neon-cyan/20 hover:border-neon-cyan transition-colors duration-150 cursor-pointer"
                        title="Previous screenshot"
                        aria-label="Previous screenshot"
                    >&lsaquo;</button>
                    <button
                        x-on:click="next()"
                        type="button"
                        class="absolute right-2 top-1/2 z-20 -translate-y-1/2 grid place-items-center w-10 h-12 font-loader text-2xl leading-none text-cyan-50 bg-gray-950/55 border border-neon-cyan/40 hover:bg-neon-cyan/20 hover:border-neon-cyan transition-colors duration-150 cursor-pointer"
                        title="Next screenshot"
                        aria-label="Next screenshot"
                    >&rsaquo;</button>

                    {{-- HUD data readout (decodes on every change) --}}
                    <div
                        aria-live="polite"
                        class="absolute inset-x-0 bottom-0 z-20 flex flex-wrap justify-between gap-x-4 gap-y-1 px-14 pt-6 pb-2 font-loader text-xs tracking-widest tabular-nums text-cyan-200 bg-linear-to-t from-gray-950/85 to-transparent pointer-events-none"
                    >
                        <span x-text="readoutLeft">IMG_01 / {{ $count }} // {{ $first['game_label'] }}</span>
                        <span x-text="readoutRight" class="text-fuchsia-300">{{ $first['date'] ?? 'NO DATE' }}</span>
                    </div>
                </div>
            </div>

            {{-- Progress rail: one segment per screenshot, directly under the frame so it reads as
                 position, not as a second scrollbar. Seen = cyan, current = magenta glow, ahead = faint. --}}
            <div aria-hidden="true" class="flex gap-0.5 px-1.5">
                @foreach ($screenshots as $screenshot)
                    <span
                        x-bind:class="index === {{ $loop->index }}
                            ? 'bg-fuchsia-600 dark:bg-neon-magenta shadow-[0_0_8px_rgba(192,38,211,0.6)] dark:shadow-[0_0_10px_rgba(255,0,229,0.8)]'
                            : (index > {{ $loop->index }} ? 'bg-cyan-700 dark:bg-neon-cyan' : 'bg-cyan-800/15 dark:bg-neon-cyan/15')"
                        class="h-1.5 flex-1 transition-colors duration-300"
                    ></span>
                @endforeach
            </div>

            <p
                x-text="current?.filename"
                class="m-0 font-subtext text-sm text-gray-500 dark:text-gray-400 break-all"
            >{{ $first['filename'] }}</p>

            {{-- Filmstrip --}}
            <div>
                <div
                    x-ref="strip"
                    role="list"
                    aria-label="Screenshots"
                    class="filmstrip relative flex gap-2 overflow-x-auto px-1 pt-2.5 pb-3"
                >
                    @foreach ($screenshots as $screenshot)
                        <button
                            x-on:click="show({{ $loop->index }})"
                            x-bind:aria-current="index === {{ $loop->index }} ? 'true' : 'false'"
                            type="button"
                            role="listitem"
                            aria-current="{{ $loop->first ? 'true' : 'false' }}"
                            aria-label="Show screenshot {{ $loop->iteration }}: {{ $screenshot['filename'] }}"
                            title="{{ $screenshot['filename'] }}"
                            class="filmstrip-thumb relative shrink-0 w-28 sm:w-32 aspect-video overflow-clip bg-gray-950 border border-cyan-800/40 dark:border-neon-cyan/25 cursor-pointer"
                        >
                            <img
                                src="{{ $screenshot['url'] }}"
                                alt=""
                                loading="lazy"
                                class="size-full object-cover pointer-events-none"
                            />
                            <span class="absolute left-1 bottom-0.5 font-loader text-[11px] text-cyan-50 [text-shadow:0_0_4px_#000]">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <p class="m-0 font-subtext text-xs text-gray-500 dark:text-gray-400">&larr; &rarr; browse &middot; Home / End jump &middot; click the image for full size</p>

            <x-modal-image-preview data-img-src="imagePreviewSrc" />
        </div>
    @else
        <p class="mt-8 px-2 text-red-800 dark:text-red-200">There are no screenshots.</p>
    @endif
</div>

{{-- Custom scripts --}}
@push('scripts')
    @vite(['resources/js/game-screenshots-gallery.js'])
@endpush
