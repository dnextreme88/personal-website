@props([
    'elements',
    'label' => null,
    'use_enum_values' => false,
    'wire_model' => null
])

<div class="grid items-center">
    @if ($label)
        <label class="text-gray-600 dark:text-gray-200 me-2">{{ $label }}</label>
    @endif

    <div class="relative">
        <select
            @if ($wire_model) wire:model="{{ $wire_model }}" @endif
            class="w-full py-2 px-3 pr-8 leading-tight bg-white border border-gray-500 rounded-none shadow appearance-none focus:outline-none"
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
</div>
