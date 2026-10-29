<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Dropping Areas</x-slot>

    {{-- Transit route map: one station per dropping area, oldest first. The rail fills on scroll
         (resources/js/dropping-areas-route.js); beacon keyframes in effects.css; station states in app.css. --}}
    <div
        x-data="droppingAreasRoute()"
        x-on:scroll.window.passive="queueUpdate()"
        x-on:resize.window="queueUpdate()"
    >
        <p class="font-subtext text-xl text-gray-500 dark:text-gray-300">A list of Dropping Areas over the years</p>

        @if (count($stations) > 0)
            <div class="relative mt-10 sm:mt-14">
                {{-- Rail track and the scroll-driven fill. Left on a phone, centre on desktop. --}}
                <div aria-hidden="true" class="absolute inset-y-0 left-4.5 w-1 bg-gray-300 dark:bg-gray-700 lg:left-[calc(50%-2px)]"></div>
                <div x-ref="railFill" aria-hidden="true" class="route-rail-fill absolute top-0 left-4.5 w-1 h-0 lg:left-[calc(50%-2px)]"></div>

                <ol x-ref="route" class="relative grid gap-16 pt-60 pb-52 lg:gap-22">
                    @foreach ($stations as $station)
                        @if ($station['is_transfer'])
                            <li class="relative z-10 flex items-center gap-2.5 ml-1 font-loader text-xs tracking-widest uppercase text-fuchsia-700 dark:text-neon-magenta lg:justify-center lg:ml-0">
                                <span aria-hidden="true" class="grid place-items-center size-8 shrink-0 text-sm rounded-full border-2 border-dashed border-current bg-gray-100 dark:bg-gray-900">&#x21C4;</span>
                                Transfer &rarr; {{ $station['address'] }}
                            </li>
                        @endif

                        <li @class([
                            'route-station relative grid grid-cols-[40px_minmax(0,1fr)] gap-3.5 items-start lg:grid-cols-[minmax(0,1fr)_40px_minmax(0,1fr)]',
                            'is-current' => $station['is_current'],
                        ])>
                            <span aria-hidden="true" class="route-node relative z-10 mt-6 ml-2 size-6 rounded-full border-[3px] bg-gray-100 dark:bg-gray-900 lg:col-start-2 lg:row-start-1 lg:mx-auto"></span>

                            <article @class([
                                'route-card relative grid grid-cols-1 bg-white border border-gray-300 dark:bg-gray-800/60 dark:border-gray-700 lg:row-start-1',
                                'sm:grid-cols-[minmax(0,220px)_minmax(0,1fr)]' => $station['url'],
                                'lg:col-start-1' => $loop->odd,
                                'lg:col-start-3' => $loop->even,
                            ])>
                                {{-- The whole card opens the preview: this button covers the card, above the photo and the text --}}
                                @if ($station['url'])
                                    <button
                                        x-on:click="openPreview(@js($station['url']))"
                                        type="button"
                                        title="Open full preview"
                                        aria-label="Open full preview: {{ $station['tag'] }} {{ $station['unit'] }}"
                                        class="absolute inset-0 z-10 cursor-zoom-in focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cyan-600 dark:focus-visible:outline-neon-cyan"
                                    ></button>
                                @endif

                                {{-- On a phone the photo keeps a 4:3 shape; beside the text it stretches to the card's full height.
                                     A station without a photo shows the text only, at full width --}}
                                @if ($station['url'])
                                    <div class="route-photo relative aspect-4/3 overflow-clip bg-gray-950 sm:aspect-auto sm:min-h-40">
                                        <img
                                            src="{{ $station['url'] }}"
                                            alt="Dropping area {{ $station['tag'] }}: {{ $station['unit'] }}"
                                            loading="lazy"
                                            class="absolute inset-0 size-full object-cover"
                                        />
                                    </div>
                                @endif

                                <div class="grid content-start gap-2.5 min-w-0 px-4 py-3.5">
                                    <div class="flex flex-wrap items-center justify-between gap-x-3 gap-y-2">
                                        <span @class([
                                            'font-loader text-2xl tracking-wider',
                                            'text-fuchsia-700 dark:text-neon-magenta' => $station['is_current'],
                                            'text-cyan-700 dark:text-neon-cyan' => !$station['is_current'],
                                        ])>{{ $station['tag'] }}</span>

                                        @if ($station['is_current'])
                                            <x-badge variant="none" class="text-xs uppercase tracking-widest text-green-700 border-green-700 shadow-[0_0_4px_rgba(21,128,61,0.35)] dark:text-green-300 dark:border-green-400 dark:shadow-[0_0_6px_rgba(74,222,128,0.45)]">
                                                <span aria-hidden="true" class="inline-block mr-1.5 size-2 rounded-full bg-current align-[1px]"></span>Current
                                            </x-badge>
                                        @else
                                            <x-badge variant="none" class="text-xs uppercase tracking-widest text-gray-500 border-gray-400 dark:text-gray-400 dark:border-gray-600">Decommissioned</x-badge>
                                        @endif
                                    </div>

                                    <h3 class="m-0 font-heading text-lg text-gray-900 dark:text-gray-100 text-balance">
                                        @if ($station['address'])
                                            {{ $station['address'] }} &middot;
                                        @endif
                                        {{ $station['unit'] }}
                                    </h3>

                                    <dl class="grid grid-cols-[auto_minmax(0,1fr)] items-baseline gap-x-3.5 gap-y-1 m-0 text-sm">
                                        <dt class="font-loader text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400">Opens</dt>
                                        <dd class="m-0 text-gray-800 dark:text-gray-200">{{ $station['opens_at'] ?? '—' }}</dd>
                                        <dt class="font-loader text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400">Closes</dt>
                                        <dd class="m-0 text-gray-800 dark:text-gray-200">{{ $station['closes_at'] ?? '—' }}</dd>
                                        <dt class="font-loader text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400">Date</dt>
                                        <dd class="m-0 text-gray-800 dark:text-gray-200">{{ $station['date'] ?? '—' }}</dd>
                                        @if ($station['note'])
                                            <dt class="font-loader text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400">Note</dt>
                                            <dd class="m-0 text-gray-800 dark:text-gray-200">{{ $station['note'] }}</dd>
                                        @endif
                                    </dl>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ol>
            </div>
        @else
            <p class="mt-8 px-2 text-red-800 dark:text-red-200">There are no dropping areas.</p>
        @endif

        <x-modal-image-preview data-img-src="imagePreviewSrc" />
    </div>
</div>

{{-- Custom scripts --}}
@push('scripts')
    @vite(['resources/js/dropping-areas-route.js'])
@endpush
