<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Welcome to my Blog!</x-slot>

    <div class="p-4">
        <h2 class="text-4xl font-semibold mb-3 text-gray-800 dark:text-gray-200">{{ $post->title }}</h2>

        <span class="text-xs relative rounded-full bg-gray-200 border border-gray-300 dark:bg-gray-700 px-3 py-1 font-medium text-gray-800 dark:text-gray-200">{{ $post->category->name }}</span>
        <span class="text-xs relative rounded-full bg-gray-200 border border-gray-300 dark:bg-gray-700 px-3 py-1 font-medium text-gray-800 dark:text-gray-200">{{ $post->reading_time }} min read</span>

        <p class="mt-4 text-gray-600 dark:text-gray-400 text-lg">Published on <span class="font-semibold">{{ \Carbon\Carbon::parse($post->date_published)->format('D, M j, Y') }}</span></p>
        <p class="mt-4 text-gray-600 dark:text-gray-400 text-xs sm:text-sm">Posted by {{ $post->user->name }} on <span class="font-semibold">{{ \Carbon\Carbon::parse($post->created_at)->format('D, M j, Y \a\t g:i A') }}</span></p>

        @if ($post->created_at != $post->updated_at)
            <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">Updated on <span class="font-semibold">{{ \Carbon\Carbon::parse($post->updated_at)->format('D, M j, Y \a\t g:i A') }}</span></p>
        @endif

        <div class="mt-5 dark:text-gray-400 prose prose-xl lg:prose-lg dark:prose-invert max-w-none">{!! Markdown::parse($post->description) !!}</div>

        <div class="border-t border-t-gray-400 mt-4 pt-6 grid grid-cols-1 sm:grid-cols-2 gap-8">
            @if ($previous)
                <a wire:navigate href="{{ route('blog.post.detail', ['id' => $previous->id, 'slug' => $previous->slug]) }}" class="group sm:col-start-1 block rounded-lg border border-gray-300 dark:border-gray-700 p-4 transition duration-200 hover:border-gray-400 hover:bg-gray-100 dark:hover:border-gray-500 dark:hover:bg-gray-800">
                    <span class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">&larr; Previous</span>
                    <span class="mt-2 block text-lg font-semibold text-blue-900 dark:text-blue-200 group-hover:text-blue-700 dark:group-hover:text-blue-100">{{ $previous->title }}</span>
                </a>
            @endif

            @if ($next)
                <a wire:navigate href="{{ route('blog.post.detail', ['id' => $next->id, 'slug' => $next->slug]) }}" class="group sm:col-start-2 block rounded-lg border border-gray-300 dark:border-gray-700 p-4 text-right transition duration-200 hover:border-gray-400 hover:bg-gray-100 dark:hover:border-gray-500 dark:hover:bg-gray-800">
                    <span class="flex items-center justify-end gap-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Next &rarr;</span>
                    <span class="mt-2 block text-lg font-semibold text-blue-900 dark:text-blue-200 group-hover:text-blue-700 dark:group-hover:text-blue-100">{{ $next->title }}</span>
                </a>
            @endif
        </div>

        <div class="mt-2">
            <a wire:navigate class="text-blue-800 dark:text-blue-200 hover:text-blue-600 dark:hover:text-blue-400" href="{{ route('blog.index') }}">&larr; Back to Blog</a>
        </div>

        @if ($related->isNotEmpty())
            <div class="border-t border-t-gray-400 mt-4 pt-6 md:pt-12">
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Related Posts</h3>

                <ul class="mt-2 space-y-2 pl-4">
                    @foreach ($related as $related_post)
                        <li class="flex items-start gap-2">
                            <span aria-hidden="true" class="text-blue-800 dark:text-blue-200">&rarr;</span>
                            <a wire:navigate class="text-blue-800 dark:text-blue-200 hover:text-blue-600 dark:hover:text-blue-400" href="{{ route('blog.post.detail', ['id' => $related_post->id, 'slug' => $related_post->slug]) }}">{{ $related_post->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
