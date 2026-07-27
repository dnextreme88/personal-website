<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Welcome to my Blog!</x-slot>

    <div class="p-4">
        <h2 class="text-4xl font-semibold mb-3 text-gray-800 dark:text-gray-200">{{ $post->title }}</h2>

        <span class="text-xs relative rounded-full bg-gray-200 border border-gray-300 dark:bg-gray-700 px-3 py-1 font-medium text-gray-800 dark:text-gray-200 font-subtext">{{ $post->category->name }}</span>
        <span class="text-xs relative rounded-full bg-gray-200 border border-gray-300 dark:bg-gray-700 px-3 py-1 font-medium text-gray-800 dark:text-gray-200 font-subtext">{{ $post->reading_time }} min read</span>

        <p class="mt-4 text-gray-700 dark:text-gray-300 text-lg font-subtext">Published on <span class="font-semibold">{{ \Carbon\Carbon::parse($post->date_published)->format('D, M j, Y') }}</span></p>
        <p class="mt-4 text-gray-700 dark:text-gray-300 text-xs sm:text-sm font-subtext">Posted by {{ $post->user->name }} on <span class="font-semibold">{{ \Carbon\Carbon::parse($post->created_at)->format('D, M j, Y \a\t g:i A') }}</span></p>

        @if ($post->created_at != $post->updated_at)
            <p class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm font-subtext">Updated on <span class="font-semibold">{{ \Carbon\Carbon::parse($post->updated_at)->format('D, M j, Y \a\t g:i A') }}</span></p>
        @endif

        <div class="mt-5 mb-10 text-gray-800 dark:text-gray-200 prose prose-xl lg:prose-lg dark:prose-invert max-w-none">{!! Markdown::parse($post->description) !!}</div>

        <div class="my-12 md:my-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            @if ($previous)
                <a wire:navigate href="{{ route('blog.post.detail', ['id' => $previous->id, 'slug' => $previous->slug]) }}" class="card-rectangle group block rounded-lg p-4 transition duration-200 hover:bg-gray-200 dark:hover:bg-gray-800 md:col-start-1">
                    <span class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-gray-800 dark:text-gray-200 font-subtext">&larr; Previous</span>
                    <span class="mt-2 block text-lg font-semibold text-cyan-900 dark:text-cyan-200 group-hover:text-cyan-700 dark:group-hover:text-cyan-100">{{ $previous->title }}</span>
                </a>
            @endif

            @if ($next)
                <a wire:navigate href="{{ route('blog.post.detail', ['id' => $next->id, 'slug' => $next->slug]) }}" class="card-rectangle group block rounded-lg p-4 transition duration-200 hover:bg-gray-200 dark:hover:bg-gray-800 md:col-start-2 md:text-right">
                    <span class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-gray-800 dark:text-gray-200 font-subtext md:justify-end">Next &rarr;</span>
                    <span class="mt-2 block text-lg font-semibold text-cyan-900 dark:text-cyan-200 group-hover:text-cyan-700 dark:group-hover:text-cyan-100">{{ $next->title }}</span>
                </a>
            @endif
        </div>

        @if ($related->isNotEmpty())
            <div class="my-12 md:my-6">
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Related Posts</h3>

                <ul class="mt-2 space-y-2 pl-4">
                    @foreach ($related as $related_post)
                        <li class="flex items-start gap-2">
                            <span aria-hidden="true" class="text-fuchsia-800 dark:text-fuchsia-200">&rarr;</span>
                            <a wire:navigate class="text-cyan-800 dark:text-cyan-200 hover:text-cyan-600 dark:hover:text-cyan-400" href="{{ route('blog.post.detail', ['id' => $related_post->id, 'slug' => $related_post->slug]) }}">{{ $related_post->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="border-t border-t-gray-400 flex items-start gap-2 mt-2 py-2">
            <span aria-hidden="true" class="text-fuchsia-800 dark:text-fuchsia-200">&larr;</span>
            <a wire:navigate class="text-cyan-800 dark:text-cyan-200 hover:text-cyan-600 dark:hover:text-cyan-400" href="{{ route('blog.index') }}">Back to Blog</a>
        </div>
    </div>
</div>
