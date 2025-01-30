<div class="grid grid-cols-1 gap-8">
    @if ($is_filtered || $posts->count() > 0)
        <form wire:submit="search_posts" class="flex justify-between gap-6 py-4">
            <input wire:model="search_query" class="w-[90%]" type="text" placeholder="Search the blog..." title="Search the blog..." aria-placeholder="Search the blog..." />

            <button type="submit" class="flex items-center gap-1 px-4 py-2 text-gray-800 transition duration-300 bg-green-300 cursor-pointer dark:bg-green-600 dark:text-gray-200 hover:bg-green-500 dark:hover:bg-green-700 hover:text-gray-100 dark:hover:text-gray-200">
                <svg wire:loading.remove wire:target="search_posts" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4" aria-label="Search icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />\
                    <title>Search icon</title>
                </svg>
                    
                <span wire:loading.flex wire:target="search_posts">
                    <x-loading-indicator
                        :loader_color_bg="'fill-gray-200'"
                        :loader_color_spin="'fill-gray-200'"
                        :showText="false"
                        :size="4"
                    />

                    <span class="ml-2">Searching</span>
                </span>

                <span wire:loading.remove wire:target="search_posts">Search</span>
            </button>
        </form>
    @endif

    @if ($is_filtered && $posts->total() >= 1)
        <p class="text-green-800 dark:text-green-200">Your search returned {{ $posts->total() }} {{ $posts->total() == 1 ? 'result' : 'results' }}.</p>
    @endif

    @forelse ($posts as $post)
        <article class="flex max-w-2xl flex-col items-start justify-between">
            <div class="flex items-center gap-x-2">
                <time class="text-sm text-gray-500 dark:text-gray-400" datetime="{{ $post->date_published }}">{{ Carbon\Carbon::parse($post->date_published)->format('F j, Y') }}</time>

                <span class="text-sm text-gray-800 dark:text-gray-200">/</span>

                <span class="text-xs relative rounded-full bg-gray-200 border border-gray-300 dark:bg-gray-700 px-3 py-1 font-medium text-gray-800 dark:text-gray-200">{{ $post->category->name }}</span>
            </div>

            <div class="relative">
                <h2 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-gray-200 hover:text-blue-800 dark:hover:text-blue-200 transition duration-200">
                    <a wire:navigate href="{{ route('blog.post.detail', ['id' => $post->id, 'slug' => $post->slug]) }}">{{ $post->title }}</a>
                </h2>

                @if (str_word_count($post->description) > 80)
                    <div class="mt-3 indent-3 text-justify line-clamp-5 text-gray-800 dark:text-gray-200">{!! Markdown::parse($post->description) !!}</div>

                    <a wire:navigate class="inline-block mt-3 transition duration-200 text-blue-800 dark:text-blue-200 hover:text-blue-600 dark:hover:text-blue-400 hover:underline" href="{{ route('blog.post.detail', ['id' => $post->id, 'slug' => $post->slug]) }}" title="Click me to read more">Read more</a>
                @else
                    <div class="mt-3 indent-2 text-justify text-gray-800 dark:text-gray-200">{!! Markdown::parse($post->description) !!}</div>
                @endif
            </div>
        </article>
    @empty
        <p class="text-red-600 dark:text-red-300">{{ $is_filtered ? 'Your search returned no results.' : 'You have no posts!' }}</p>
    @endforelse

    {{ $posts->withQueryString()->links(data: ['scrollTo' => false]) }}
</div>
