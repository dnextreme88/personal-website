@props([
    'elements',
    'is_animated' => false,
    'label' => null,
    'use_enum_values' => false,
    'wire_model' => null
])

<div class="grid items-center">
    @if ($label)
        <label class="text-gray-600 dark:text-gray-200 me-2">{{ $label }}</label>
    @endif

    @if (!$is_animated)
        <div class="relative">
            <select
                @if ($wire_model) wire:model="{{ $wire_model }}" @endif
                class="cyber-input w-full pr-8 leading-tight shadow appearance-none"
            >
                @if ($elements)
                    <option value="">SHOW ALL</option>

                    @foreach ($elements as $key => $element)
                        @if ($use_enum_values)
                            <option value="{{ $element->value }}">{{ $element->value }}</option>
                        @else
                            @if ($wire_model == 'archive_months_choice')
                                <option value="{{ $key }}">{{ ucfirst($element) }}</option>
                            @else
                                <option value="{{ $element }}">{{ $element }}</option>
                            @endif
                        @endif
                    @endforeach
                @endif
            </select>

            {{-- Custom arrow --}}
            <div class="col-start-2 pointer-events-none absolute right-3 flex items-center text-gray-500 -translate-y-1/2 top-[50%]">
                <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                </svg>
            </div>
        </div>
    @endif

    @if ($is_animated)
        <div
            {{-- x-cloak --}}
            x-data="{
                openChoices: false,
                selected: {
                    label: 'SHOW ALL',
                    value: ''
                }
            }"
            x-on:form-reset.window="selected = { label: 'SHOW ALL', value: '' }; openChoices = false"
            class="relative"
        >
            <button
                x-on:mousedown="openChoices = !openChoices"
                {{-- `!` (important) so the open-state cyan wins over the base border the .cyber-input selector now provides --}}
                {{-- :class="openChoices ? 'border-cyan-400!' : ''" --}}
                type="button"
                class="w-full h-10.5 pr-8 leading-tight shadow appearance-none cursor-pointer text-left cyber-input"
            >
                <span x-text="selected.label" class="block truncate text-gray-800 dark:text-gray-200"></span>
            </button>

            {{-- Custom arrow --}}
            <div
                :class="{ 'rotate-180': openChoices }"
                class="pointer-events-none absolute right-3 flex items-center -translate-y-1/2 top-[50%] transition-transform duration-200 text-neon-magenta"
            >
                <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                </svg>
            </div>

            {{-- Animated dropdown list --}}
            <ul
                x-cloak
                x-show="openChoices"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="absolute z-50 w-full bg-white border border-gray-500 shadow mt-1 rounded-none max-h-60 overflow-y-auto"
            >
                <li
                    x-on:click="selected = { label: 'SHOW ALL', value: '' }; openChoices = false; $dispatch('filter-changed', { key: '{{ $wire_model }}', value: '' })"
                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 transition-colors duration-100"
                    :class="{ 'bg-gray-100 font-medium': selected.value === '' }"
                >
                    SHOW ALL
                </li>

                @if ($elements)
                    @foreach ($elements as $key => $element)
                        @if ($use_enum_values)
                            <li
                                x-on:click="selected = { label: '{{ $element }}', value: '{{ $element }}' }; openChoices = false; $dispatch('filter-changed', { key: '{{ $wire_model }}', value: '{{ $element }}' })"
                                class="px-3 py-2 cursor-pointer hover:bg-gray-100 transition-colors duration-100"
                                :class="{ 'bg-gray-100 font-medium': selected.value === '{{ $element->value }}' }"
                            >
                                {{ $element->value }}
                            </li>
                        @else
                            @if ($wire_model == 'archive_months_choice')
                                <li
                                    x-on:click="selected = { label: '{{ ucfirst($element) }}', value: '{{ $key }}' }; openChoices = false; $dispatch('filter-changed', { key: '{{ $wire_model }}', value: '{{ $key }}' })"
                                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 transition-colors duration-100"
                                    :class="{ 'bg-gray-100 font-medium': selected.value === '{{ $key }}' }"
                                >
                                    {{ ucfirst($element) }}
                                </li>
                            @else
                                <li
                                    x-on:click="selected = { label: '{{ $element }}', value: '{{ $element }}' }; openChoices = false; $dispatch('filter-changed', { key: '{{ $wire_model }}', value: '{{ $element }}' })"
                                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 transition-colors duration-100"
                                    :class="{ 'bg-gray-100 font-medium': selected.value === '{{ $element }}' }"
                                >
                                    {{ $element }}
                                </li>
                            @endif
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    @endif
</div>
