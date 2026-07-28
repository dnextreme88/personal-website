<div class="grid grid-cols-1 gap-8">
    @if ($is_filtered || $posts->count() > 0)
        <form wire:submit="search_posts" class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:gap-6 sm:py-4">
            <x-forms.input-text class="w-full" for="search_query" placeholder_text="Search the blog..." title_text="Search posts" />

            <x-forms.button-submit bevels="tr br" x-on:click.prevent="$wire.call('search_posts')" class="min-w-40">
                <span wire:loading.flex wire:target="search_posts">
                    <x-loading-indicator
                        :loader_color_bg="'fill-gray-200 dark:fill-gray-800'"
                        :loader_color_spin="'fill-gray-200 dark:fill-gray-800'"
                        :show_text="true"
                        :size="4"
                        :text="'Searching'"
                        :text_color="'text-gray-200 dark:text-gray-800'"
                    />
                </span>

                <span wire:loading.remove wire:target="search_posts" class="font-loader">Search</span>
            </x-forms.button-submit>
        </form>
    @endif

    <div wire:loading.flex>
        <x-loading-indicator
            :loader_color_bg="'border-neon-cyan'"
            :loader_color_spin="'border-neon-cyan'"
            :show_text="true"
            :size="4"
            :text="'Loading posts...'"
            :text_classes="'text-lg tracking-widest'"
            :text_color="'text-cyan-800 dark:text-cyan-200'"
        />
    </div>

    <div wire:loading.remove class="space-y-8">
        @if ($is_filtered && $posts->total() >= 1)
            <p class="mb-4 text-green-800 dark:text-green-200">Your search returned {{ $posts->total() }} {{ $posts->total() == 1 ? 'result' : 'results' }}.</p>
        @endif

        @forelse ($posts as $post)
            <article class="flex w-full flex-col items-start justify-between sm:w-[calc(100%-130px-1.5rem)]">
                <div class="flex items-center gap-x-2">
                    <time class="text-sm text-gray-500 dark:text-gray-400" datetime="{{ $post->date_published }}">{{ Carbon\Carbon::parse($post->date_published)->format('F j, Y') }}</time>

                    <span class="text-sm text-fuchsia-800 dark:text-fuchsia-200">/</span>

                    <span class="text-xs relative rounded-full bg-gray-200 border border-gray-300 dark:bg-gray-700 px-3 py-1 font-medium text-gray-800 dark:text-gray-200">{{ $post->category->name }}</span>
                </div>

                <div class="relative">
                    <h2 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-gray-200 hover:text-cyan-800 dark:hover:text-cyan-200 transition duration-200">
                        <a wire:navigate href="{{ route('blog.post.detail', ['id' => $post->id, 'slug' => $post->slug]) }}">{{ $post->title }}</a>
                    </h2>

                    @if (str_word_count($post->description) > 80)
                        <div class="w-full max-w-none mt-3 indent-3 text-justify line-clamp-5 text-gray-800 dark:text-gray-200 prose dark:prose-invert">{!! Markdown::parse($post->description) !!}</div>

                        <a wire:navigate class="inline-block mt-3 transition duration-200 text-cyan-800 dark:text-cyan-200 hover:text-cyan-600 dark:hover:text-cyan-400 hover:underline" href="{{ route('blog.post.detail', ['id' => $post->id, 'slug' => $post->slug]) }}" title="Click me to read more">Read more</a>
                    @else
                        <div class="w-full max-w-none mt-3 indent-2 text-justify text-gray-800 dark:text-gray-200 prose dark:prose-invert">{!! Markdown::parse($post->description) !!}</div>
                    @endif
                </div>
            </article>
        @empty
            <p class="text-red-600 dark:text-red-300">{{ $is_filtered ? 'Your search returned no results.' : 'You have no posts!' }}</p>
        @endforelse
    </div>

    {{ $posts->withQueryString()->links(data: ['scrollTo' => false]) }}
</div>
