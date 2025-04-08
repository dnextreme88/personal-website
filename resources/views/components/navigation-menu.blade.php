<div x-data="{ open: false }">
    {{-- View for desktop screens --}}
    <div class="p-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center shrink-0">
                <x-app-logo />
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-2 lg:gap-4">
                <x-dark-mode-toggle>
                    <x-slot name="left_side">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 fill-blue-300 dark:fill-transparent dark:text-blue-400" aria-label="Toggle light mode">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                            <title>Toggle light mode</title>
                        </svg>
                    </x-slot>

                    <x-slot name="right_side">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 fill-white" aria-label="Toggle dark mode">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                            <title>Toggle dark mode</title>
                        </svg>
                    </x-slot>
                </x-dark-mode-toggle>

                <a wire:navigate class="text-gray-900 transition duration-100 dark:text-gray-100 hover:text-blue-800 dark:hover:text-blue-200 hover:scale-105" href="{{ route('archives') }}">Archives</a>

                <a wire:navigate class="text-gray-900 transition duration-100 dark:text-gray-100 hover:text-blue-800 dark:hover:text-blue-200 hover:scale-105" href="{{ route('blog.index') }}">Blog</a>

                <x-button-contact-me />
            </div>

            {{-- Hamburger to open Responsive Navigation Menu --}}
            <div class="flex items-center me-2 sm:hidden">
                <button x-on:click="open = !open" x-bind:class="{'focus:rotate-90': open}" class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400" aria-controls="responsive-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path x-bind:class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-bind:class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Responsive Navigation Menu --}}
    <div x-bind:class="{'flex flex-col space-y-2 space-x-1 pb-5': open, 'hidden sm:hidden': !open}" class="hidden sm:hidden responsive-nav">
        <x-dark-mode-toggle class="sm:hidden">
            <x-slot name="left_side">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 fill-blue-300 dark:fill-transparent dark:text-blue-400" aria-label="Toggle light mode">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                    <title>Toggle light mode</title>
                </svg>
            </x-slot>

            <x-slot name="right_side">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 fill-white" aria-label="Toggle dark mode">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    <title>Toggle dark mode</title>
                </svg>
            </x-slot>
        </x-dark-mode-toggle>

        <div class="pb-2 sm:hidden">
            <x-responsive-nav-link :active="request()->routeIs('archives')" wire:navigate href="{{ route('archives') }}" aria-label="Archives link">Archives</x-responsive-nav-link>
        </div>

        <div class="pb-2 sm:hidden">
            <x-responsive-nav-link :active="request()->routeIs('blog.index')" wire:navigate href="{{ route('blog.index') }}" aria-label="Blog link">Blog</x-responsive-nav-link>
        </div>

        <div class="pt-2 pb-3 ps-3 pe-4 sm:hidden">
            <x-button-contact-me />
        </div>
    </div>
</div>
