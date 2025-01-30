<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Welcome to my Blog!</x-slot>

    <h2 class="mb-2 text-3xl text-gray-800 dark:text-gray-200">{{ $category->name }}</h2>

    @if ($category->description)
        <p class="text-xl text-gray-500 dark:text-gray-400">{{ $category->description }}</p>
    @endif

    <div class="my-8 grid grid-cols-1 md:grid-cols-3">
        <div class="col-span-2 mb-6 md:mr-6">
            <livewire:blog.list-post :posts="$posts" />
        </div>

        <div>
            <livewire:blog.list-category />
        </div>
    </div>
</div>
