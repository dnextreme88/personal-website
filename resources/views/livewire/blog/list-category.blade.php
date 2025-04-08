<div>
    <div class="relative">
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-gray-400"></div>
        </div>

        <div class="relative flex justify-center md:justify-start">
            <span class="bg-gray-100 dark:bg-gray-900 text-lg font-semibold text-gray-800 dark:text-gray-200 px-4 md:pl-0 md:pr-6">Categories</span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3 mt-4 mb-6 border border-b-gray-400 border-t-0 border-x-0 px-2 pb-3">
        @forelse ($categories as $category)
            @php
                $is_current_category = url()->current() == url('/blog/categories/' .$category->slug. '/posts');
            @endphp

            <span class="flex justify-between items-baseline gap-2 {{ $is_current_category ? 'bg-blue-100 dark:bg-gray-700 border-l-4 border-blue-400 dark:border-blue-300 pl-3 py-2' : '' }}">
                <a wire:navigate class="transition duration-200 text-blue-800 dark:text-blue-200 hover:text-blue-600 dark:hover:text-blue-400 md:truncate {{ !$is_current_category ? 'hover:underline' : '' }}" title="View posts on category {{ $category->name }}" href="{{ route('blog.categories.post.list', ['slug' => $category->slug]) }}">{{ $category->name }}</a>

                <small class="mr-3 text-gray-800 dark:text-gray-200">({{ $category->posts_count }})</small>
            </span>
        @empty
            <p class="text-red-600 dark:text-red-300">You have no categories!</p>
        @endforelse
    </div>
</div>
