<div>
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <section class="h-screen px-6 bg-gradient-to-r from-white to-blue-300 pt-36 pb-80 dark:from-slate-400 dark:bg-gradient-to-br dark:to-blue-600 sm:py-32 lg:px-8">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-5xl font-semibold tracking-tight text-gray-800 dark:text-gray-100 sm:text-7xl" aria-label="Welcome header">Welcome!</h2>

            <p class="mt-8 text-lg font-medium text-gray-600 text-pretty dark:text-gray-100 sm:text-xl/8" aria-label="Welcome description">My name is Jeanne Kevin T. Decena and welcome to my personal website! Here you can find all the goodies I've been doing in my life - from my blog and to my portfolio. Feel free to look around! If you need some web development professional, look no further and contact me right away!</p>
        </div>
    </section>

    <x-slot name="footer">
        <x-social-links-and-copyright />
    </x-slot>
</div>
