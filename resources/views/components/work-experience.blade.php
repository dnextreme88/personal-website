@props([
    'duration' => '',
    'employment' => '',
    'experiences' => [],
    'position' => '',
    'tech_stacks' => [],
])

<div class="bg-gray-200 dark:bg-gray-800 p-6 rounded-lg shadow">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-2">
        <h2 class="col-span-2 font-semibold text-2xl mb-2 text-gray-800 dark:text-gray-200 lg:text-base">{{ $employment }}</h2>

        <h3 class="text-gray-600 dark:text-gray-400">{{ $position }}</h3>
        <span class="text-gray-500 dark:text-gray-400 md:justify-self-end">{{ $duration }}</span>
    </div>

    @if (count($experiences) > 0)
        <ul class="list-disc pl-6 mt-3 text-gray-700 dark:text-gray-300 space-y-1">
            @foreach ($experiences as $experience)
                <li>{{ $experience }}</li>
            @endforeach
        </ul>
    @endif

    @if (count($tech_stacks) > 0)
        <ul class="flex items-center ml-2 mt-3 space-x-4">
            @foreach ($tech_stacks as $stack => $color_classes)
                <li class="flex items-center gap-2">
                    <div class="size-3 rounded-full {{ $color_classes }}" aria-hidden="true" aria-label="{{ $stack }} round color"></div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300" aria-hidden="true" aria-label="{{ $stack }}">{{ $stack }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
