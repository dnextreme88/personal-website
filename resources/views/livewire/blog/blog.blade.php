<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Welcome to my Blog!</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="col-span-2 mb-6 md:mr-6">
            <livewire:blog.list-post />
        </div>

        <div>
            <livewire:calendar />

            <livewire:blog.list-category />
        </div>
    </div>
</div>
