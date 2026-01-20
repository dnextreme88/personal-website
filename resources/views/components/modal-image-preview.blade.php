@props(['dataImgSrc' => ''])

<div
    x-bind:aria-hidden="!isImagePreviewModalOpen"
    x-on:click.self="isImagePreviewModalOpen = false"
    x-on:keydown.escape.window="isImagePreviewModalOpen = false"
    x-show="isImagePreviewModalOpen"
    x-transition
    class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
>
    <img
        x-bind:src="{{ $dataImgSrc }}"
        class="rounded-xl object-contain max-h-[80vh] max-w-[80vw]"
    />

    <button
        x-on:click="isImagePreviewModalOpen = false"
        class="absolute cursor-pointer top-3 right-3 text-white text-4xl leading-none bg-black/20 dark:bg-black/50 hover:bg-gray-800 dark:hover:bg-gray-400/50 transition duration-150 z-1 py-1 px-2"
        title="Close full preview modal"
        aria-label="Close full preview modal"
    >
        &times;
    </button>
</div>
