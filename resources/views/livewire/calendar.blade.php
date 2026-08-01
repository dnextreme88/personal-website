<div class="mb-8 md:mb-0 md:h-[350px] lg:h-[425px]">
    <div class="p-4 mt-4 mb-2 bg-gray-200/50 dark:bg-gray-600/50 shadow-2 shadow-md shadow-gray-500 rounded-md card-rectangle">
        <div class="flex justify-between items-center mb-4">
            <x-button-next-previous
                as="button"
                text="&lt;"
                wire:click="switch_to_previous_month"
                title="Go to previous month"
                aria-label="Go to previous month"
            />
            
            <h2 class="text-gray-800 dark:text-gray-200 font-semibold text-lg">{{ $current_month_year }}</h2>
            
            <x-button-next-previous
                as="button"
                text="&gt;"
                wire:click="switch_to_next_month"
                title="Go to next month"
                aria-label="Go to next month"
            />
        </div>

        <div class="grid grid-cols-7 gap-2 text-center">
            @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="text-gray-800 dark:text-gray-200 font-semibold md:text-sm font-subtext">{{ $day }}</div>
            @endforeach

            {{-- Show previous month's days --}}
            @for ($i = $first_day_of_month - 1; $i >= 0; $i--)
                <div class="text-gray-800 dark:text-gray-200 p-1 opacity-50 dark:opacity-25 md:p-0 lg:p-2">{{ $previous_month_days - $i }}</div>
            @endfor

            @foreach ($days_in_month as $carbon_instance)
                @php
                    $is_current_date = $current_year == \Carbon\Carbon::now()->year &&
                        $current_month == \Carbon\Carbon::now()->month &&
                        $current_day == $carbon_instance->day;
                    $is_date_have_posts = in_array($carbon_instance->format('Y-m-d'), $unique_dates->toArray());
                @endphp

                <div
                    class="group relative p-1 md:p-0 lg:p-2
                        @if ($is_current_date)
                            font-bold text-gray-800 dark:text-gray-200
                        @elseif ($is_date_have_posts)
                            cursor-pointer font-bold text-cyan-800 dark:text-cyan-200

                            @if ($selected_date == $carbon_instance->format('Y-m-d'))
                                border bg-gray-300 dark:bg-gray-600 border-gray-600 dark:border-gray-300
                            @else
                                underline
                            @endif
                        @else
                            text-gray-800 dark:text-gray-200 hover:cursor-not-allowed
                        @endif
                    "
                    @if ($is_date_have_posts)
                        wire:click="view_posts_on_date('{{ $carbon_instance->format('Y-m-d') }}')"
                        title="View posts for {{ $carbon_instance->format('F j, Y') }}"
                    @endif
                >
                    {{--
                    Current day → a neon-magenta marching-ants border (always shown, it
                    replaces the old card-rectangle highlight). Otherwise, show the cyan
                    hover border-tracer on any non-selected date that has posts.
                    --}}
                    @if ($is_current_date)
                        <svg class="absolute inset-0 w-full h-full overflow-visible" viewBox="0 0 44 44">
                            <rect class="marching-border fill-none stroke-neon-magenta stroke-[1.5]" x="0.75" y="0.75" width="42.5" height="42.5" rx="3.25" pathLength="100" />
                        </svg>
                    @elseif ($selected_date != $carbon_instance->format('Y-m-d') && $is_date_have_posts)
                        <svg class="absolute inset-0 w-full h-full overflow-visible" viewBox="0 0 44 44">
                            <rect class="border-tracer fill-none stroke-cyan-600 dark:stroke-cyan-300 stroke-[1.5]" x="0.75" y="0.75" width="42.5" height="42.5" rx="3.25" />
                        </svg>
                    @endif
                    {{ $carbon_instance->day }}
                </div>
            @endforeach
        </div>
    </div>
</div>
