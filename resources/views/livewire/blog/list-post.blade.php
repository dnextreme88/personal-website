<div class="grid grid-cols-1 gap-5">
    @forelse ($posts as $post)
        <article class="flex max-w-2xl flex-col items-start justify-between">
            <div class="flex items-center gap-x-2">
                <time class="text-sm text-gray-500 dark:text-gray-400" datetime="{{ $post->date_published }}">{{ Carbon\Carbon::parse($post->date_published)->format('F j, Y') }}</time>

                <span class="text-sm text-gray-800 dark:text-gray-200">/</span>

                <span class="text-xs relative z-10 rounded-full bg-gray-200 border border-gray-300 dark:bg-gray-700 px-3 py-1 font-medium text-gray-800 dark:text-gray-200">{{ $post->category->name }}</span>
            </div>

            <div class="relative">
                <h2 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-gray-200 hover:text-blue-800 dark:hover:text-blue-200 transition duration-200">
                    <a wire:navigate href="{{ route('blog.post.detail', ['id' => $post->id, 'slug' => $post->slug]) }}">{{ $post->title }}</a>
                </h2>

                @if (str_word_count($post->description) > 80)
                    <div class="mt-3 indent-3 text-justify line-clamp-5 text-gray-800 dark:text-gray-200">{!! Markdown::parse($post->description) !!}</div>

                    <a wire:navigate class="inline-block mb-4 mt-2 transition duration-200 text-blue-800 dark:text-blue-200 hover:text-blue-600 dark:hover:text-blue-400 hover:underline" href="{{ route('blog.post.detail', ['id' => $post->id, 'slug' => $post->slug]) }}" title="Click me to read more">Read more</a>
                @else
                    <div class="mt-3 indent-2 text-justify text-gray-800 dark:text-gray-200">{!! Markdown::parse($post->description) !!}</div>
                @endif
            </div>
        </article>
    @empty
        <p class="text-red-600 dark:text-red-300">You have no posts!</p>
    @endforelse
</div>
