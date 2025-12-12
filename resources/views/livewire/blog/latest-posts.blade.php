<div class="mx-auto mt-16 grid max-w-2xl auto-rows-fr grid-cols-1 gap-8 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
    @forelse ($latest_posts as $idx => $post)
        @php
            $transition_delay = ($idx + 1) * 50;
            $bg_gradient = '';

            switch ($idx + 1) {
                case 1:
                    $bg_gradient = 'gradient-gray-1';

                    break;
                case 2:
                    $bg_gradient = 'gradient-gray-2';

                    break;
                case 3:
                    $bg_gradient = 'gradient-gray-3';

                    break;
                default:
                    $bg_gradient = 'gradient-gray-1';

                    break;
            }
        @endphp

        <article class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pt-80 pb-8 sm:pt-48 lg:pt-50 lg:px-4 dark:bg-gray-800 hover:scale-110 translate-y-32 opacity-0 intersect-once intersect:translate-y-0 intersect:opacity-100 intersect:delay-{{ $transition_delay }} transition [transition:scale_0.25s,translate_2s,opacity_3s]">
            {{-- NOTE: Stock images --}}
            {{-- <img src="https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3603&q=80" class="absolute inset-0 -z-10 size-full object-cover" /> --}}
            {{-- <img src="https://images.unsplash.com/photo-1547586696-ea22b4d4235d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80" class="absolute inset-0 -z-10 size-full object-cover" /> --}}
            {{-- <img src="https://images.unsplash.com/photo-1492724441997-5dc865305da7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80" class="absolute inset-0 -z-10 size-full object-cover" /> --}}
            <a wire:navigate href="{{ route('blog.post.detail', ['id' => $post->id, 'slug' => $post->slug]) }}">
                <div class="absolute inset-0 -z-10 size-full bg-linear-to-tl dark:bg-linear-to-r opacity-80 {{ $bg_gradient }}"></div>
                <div class="absolute inset-0 -z-10 bg-linear-to-t from-gray-700 via-gray-800/40 dark:from-black/80 dark:via-black/40"></div>
                <div class="absolute inset-0 -z-10 rounded-2xl inset-ring inset-ring-gray-900/10 dark:inset-ring-white/10"></div>

                <div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm/6">
                    <time class="mr-8 text-sm text-gray-300/50 dark:text-gray-400" datetime="{{ $post->date_published }}">{{ Carbon\Carbon::parse($post->date_published)->format('F j, Y') }}</time>

                    <div class="-ml-4 flex items-center gap-x-3">
                        <svg viewBox="0 0 2 2" class="-ml-0.5 size-0.5 flex-none fill-white/50 dark:fill-gray-300/50">
                            <circle r="1" cx="1" cy="1" />
                        </svg>

                        <div class="flex gap-x-2.5 text-gray-300">{{ $post->category->name }}</div>
                    </div>
                </div>

                <h3 class="mt-3 text-lg/6 font-semibold text-white">{{ $post->title }}</h3>
            </a>
        </article>
    @empty
        <p class="col-span-1 px-2 text-red-800 dark:text-red-200 md:col-span-2 xl:col-span-3">No latest posts found.</p>
    @endforelse
</div>
