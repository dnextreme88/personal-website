<div>
    <x-slot name="nav_menu">
        <div x-data="{ open: false }">
            <div class="p-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center shrink-0">
                        <x-app-logo />
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:gap-2 lg:gap-4">
                        <p>Menu links here</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <section class="h-screen px-6 bg-gradient-to-r from-white to-blue-200 pt-36 pb-80 dark:to-blue-600 sm:py-32 lg:px-8">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-5xl font-semibold tracking-tight text-gray-800 dark:text-gray-200 sm:text-7xl">Welcome!</h2>

            <p class="mt-8 text-lg font-medium text-gray-600 text-pretty dark:text-gray-400 sm:text-xl/8">My name is Jeanne Kevin T. Decena and welcome to my personal website! Here you can find all the goodies I've been doing in my life - from my blog and to my portfolio. Feel free to look around! If you need some web development professional, look no further and contact me right away!</p>
        </div>
    </section>
</div>
