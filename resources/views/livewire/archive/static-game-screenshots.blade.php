<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Game Screenshots</x-slot>

    <div x-data="{
            isImagePreviewModalOpen: false,
            imagePreviewSrc: ''
        }"
    >
        <div class="mx-auto mt-8 grid max-w-2xl auto-rows-fr grid-cols-1 gap-8 sm:mt-12 lg:mx-0 lg:max-w-none lg:grid-cols-3">
            @forelse ($screenshots as $screenshot)
                <figure class="overflow-hidden rounded-lg bg-gray-200/50 outline outline-gray-300/30 shadow-sm dark:divide-white/10 dark:bg-gray-800/50 dark:shadow-none dark:outline dark:-outline-offset-1 dark:outline-white/30 card-rectangle">
                    <div class="relative px-3 py-5">
                        <img
                            x-on:click="imagePreviewSrc = '{{ $screenshot['url'] }}'; isImagePreviewModalOpen = true"
                            src="{{ $screenshot['url'] }}"
                            class="inset-0 cursor-pointer object-cover size-full min-h-[300px] aspect-square lg:aspect-auto"
                            alt="{{ $screenshot['filename'] }} background"
                            title="{{ $screenshot['filename'] }} background"
                            loading="lazy"
                            aria-label="{{ $screenshot['filename'] }} background"
                        />

                        <span
                            x-on:click="imagePreviewSrc = '{{ $screenshot['url'] }}'; isImagePreviewModalOpen = true"
                            class="absolute cursor-pointer right-0 top-0 text-white text-4xl bg-gray-900/50 hover:bg-gray-500 transition duration-150 py-1 px-2 mx-3 my-5"
                            title="Open full preview modal"
                            aria-label="Open full preview modal"
                        >
                            &#x2197;
                        </span>
                    </div>

                    <figcaption class="bg-gray-50 p-4 max-w-full md:truncate md:max-w-[100vw] text-gray-500 dark:text-gray-300 sm:px-6 dark:bg-gray-800">{{ $screenshot['filename'] }}</figcaption>
                </figure>
            @empty
                <p class="col-span-1 px-2 text-red-800 dark:text-red-200 md:col-span-2 xl:col-span-3">There are no screenshots.</p>
            @endforelse
        </div>

        <x-modal-image-preview data-img-src="imagePreviewSrc" />
    </div>
</div>
