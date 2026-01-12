<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Archives</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-1">
        <div class="mx-auto mt-8 grid max-w-2xl auto-rows-fr grid-cols-1 gap-8 sm:mt-12 lg:mx-0 lg:max-w-none lg:grid-cols-3">
            <section class="relative isolate flex flex-col justify-end rounded-2xl bg-gray-900 px-8 pt-80 pb-8 sm:pt-48 lg:pt-50 lg:px-4 dark:bg-gray-800 hover:scale-110 transition [transition:scale_0.25s,translate_2s,opacity_3s]">
                <a wire:navigate href="{{ route('archive.sold-items.list') }}">
                    <img
                        src="{{ asset('/images/archive/bg-list-sold-item.webp') }}"
                        class="absolute inset-0 object-cover size-full rounded-2xl -z-10 aspect-square lg:aspect-auto"
                        alt="Sold item background"
                        title="Sold item background"
                        loading="lazy"
                    />
                    <div class="absolute inset-0 -z-10 rounded-2xl size-full bg-linear-to-tl dark:bg-linear-to-r opacity-80 gradient-gray-3"></div>
                    <div class="absolute inset-0 top-[25%] -z-10 rounded-2xl bg-linear-to-t from-gray-700 via-gray-700/50 dark:from-black/ dark:via-black/40"></div>
                    <div class="absolute inset-0 -z-10 rounded-2xl inset-ring inset-ring-gray-900/10 dark:inset-ring-white/10"></div>

                    <h3 class="mt-3 text-lg/6 font-semibold text-white">Sold Items</h3>
                    <h4 class="mt-3 text-base/6 text-gray-300/70 dark:text-gray-400">Collection of products I have sold over the years</h4>
                </a>
            </section>

            <section class="relative isolate flex flex-col justify-end rounded-2xl bg-gray-900 px-8 pt-80 pb-8 sm:pt-48 lg:pt-50 lg:px-4 dark:bg-gray-800 hover:scale-110 transition [transition:scale_0.25s,translate_2s,opacity_3s]">
                <a wire:navigate href="{{ route('archive.game-screenshots.static') }}">
                    <img
                        src="{{ asset('/images/archive/bg-static-game-screenshots.webp') }}"
                        class="absolute inset-0 object-cover size-full rounded-2xl -z-10 aspect-square lg:aspect-auto"
                        alt="Game screenshots background"
                        title="Game screenshots background"
                        loading="lazy"
                    />
                    <div class="absolute inset-0 -z-10 rounded-2xl size-full bg-linear-to-tl dark:bg-linear-to-r opacity-80 gradient-gray-3"></div>
                    <div class="absolute inset-0 top-[25%] -z-10 rounded-2xl bg-linear-to-t from-gray-700 via-gray-700/50 dark:from-black/ dark:via-black/40"></div>
                    <div class="absolute inset-0 -z-10 rounded-2xl inset-ring inset-ring-gray-900/10 dark:inset-ring-white/10"></div>

                    <h3 class="mt-3 text-lg/6 font-semibold text-white">Game Screenshots</h3>
                    <h4 class="mt-3 text-base/6 text-gray-300/70 dark:text-gray-400">Archived images of memorable gaming moments</h4>
                </a>
            </section>
        </div>
    </div>
</div>
