<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Welcome to my Blog!</x-slot>

    <div class="p-4">
        <h2 class="text-4xl font-semibold mb-3 text-gray-800 dark:text-gray-200">{{ $post->title }}</h2>

        <span class="text-xs relative z-10 rounded-full bg-gray-200 border border-gray-300 dark:bg-gray-700 px-3 py-1 font-medium text-gray-800 dark:text-gray-200">{{ $post->category->name }}</span>

        <p class="mt-4 text-gray-600 dark:text-gray-400 text-xs sm:text-sm">Posted by {{ $post->user->name }} on <span class="font-semibold">{{ \Carbon\Carbon::parse($post->created_at)->format('D, M j, Y \a\t g:i A') }}</span></p>

        @if ($post->created_at != $post->updated_at)
            <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">Updated on <span class="font-semibold">{{ \Carbon\Carbon::parse($post->updated_at)->format('D, M j, Y \a\t g:i A') }}</span></p>
        @endif

        <div class="mt-5 dark:text-gray-400">{!! Markdown::parse($post->description) !!}</div>

        <div class="border-t border-t-gray-400 mt-4 pt-2 flex flex-row justify-between items-center">
            <p><a wire:navigate class="text-blue-800 dark:text-blue-200 hover:text-blue-600 dark:hover:text-blue-400" href="{{ route('blog.index') }}">&larr; Back to Blog</a></p>
        </div>
    </div>
</div>
