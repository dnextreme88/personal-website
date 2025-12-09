<div class="mb-8 md:mb-0 md:h-[350px] lg:h-[425px]">
    <div class="p-4 mt-4 mb-2 bg-gray-300/50 dark:bg-gray-600/50 shadow-2 shadow-md shadow-gray-500 rounded-md">
        <div class="flex justify-between items-center mb-4">
            <button wire:click="switch_to_previous_month" class="text-xl px-2 py-1 transition duration-200 text-blue-800 dark:text-blue-200 hover:text-gray-200 dark:hover:text-blue-400 hover:bg-blue-800" title="Go to previous month" aria-label="Go to previous month">&larr;</button>

            <h2 class="text-gray-800 dark:text-gray-200 font-semibold text-lg">{{ $current_month_year }}</h2>

            <button wire:click="switch_to_next_month" class="text-xl px-2 py-1 transition duration-200 text-blue-800 dark:text-blue-200 hover:text-gray-200 dark:hover:text-blue-400 hover:bg-blue-800" title="Go to next month" aria-label="Go to next month">&rarr;</button>
        </div>

        <div class="grid grid-cols-7 gap-2 text-center">
            @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="text-gray-800 dark:text-gray-200 font-semibold md:text-sm">{{ $day }}</div>
            @endforeach

            {{-- Show previous month's days --}}
            @for ($i = $first_day_of_month - 1; $i >= 0; $i--)
                <div class="text-gray-800 dark:text-gray-200 p-1 opacity-50 dark:opacity-25 md:p-0 lg:p-2">{{ $previous_month_days - $i }}</div>
            @endfor

            @foreach ($days_in_month as $carbon_instance)
                <div
                    wire:click="view_posts_on_date('{{ $carbon_instance->format('Y-m-d') }}')"
                    class="border cursor-pointer transition duration-200 p-1 hover:bg-gray-300 dark:hover:bg-gray-600 md:p-0 lg:p-2
                        @if ($current_day == $carbon_instance->day && $current_month == \Carbon\Carbon::now()->month)
                            font-bold text-gray-800 dark:text-gray-200 bg-gray-300 dark:bg-gray-600

                            @if ($selected_date && $selected_date != $carbon_instance->format('Y-m-d'))
                                border-transparent
                            @else
                                border-gray-600 dark:border-gray-300
                            @endif
                        @elseif (in_array($carbon_instance->format('Y-m-d'), $unique_dates->toArray()))
                            font-bold text-blue-800 dark:text-blue-200 underline

                            @if ($selected_date == $carbon_instance->format('Y-m-d'))
                                bg-gray-300 dark:bg-gray-600 border-gray-600 dark:border-gray-300
                            @else
                                border-transparent
                            @endif
                        @else
                            text-gray-800 dark:text-gray-200 border-transparent
                        @endif

                        @if ($selected_date == $carbon_instance->format('Y-m-d'))
                            bg-gray-300 dark:bg-gray-600 border-gray-800 dark:border-gray-200
                        @endif
                    "
                    title="View posts for this date"
                >
                    {{ $carbon_instance->day }}
                </div>
            @endforeach
        </div>
    </div>
</div>
