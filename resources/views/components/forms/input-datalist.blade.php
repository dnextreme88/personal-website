@props([
    'elements',
    'elements_label',
    'label' => null,
    'text_placeholder',
    'wire_model' => null
])

<div class="grid items-center">
    @if ($label)
        <label class="text-gray-600 dark:text-gray-200 me-2">{{ $label }}</label>
    @endif

    <input
        @if ($wire_model) wire:model="{{ $wire_model }}" @endif
        class="py-2 px-3 bg-white border border-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-400"
        list="{{ $elements_label }}"
        placeholder="{{ $text_placeholder }}"
    />
    <datalist id="{{ $elements_label }}">
        @if ($elements)
            <option value="SHOW ALL"></option>

            @foreach ($elements as $element)
                <option value="{{ $element }}">{{ $element }}</option>
            @endforeach
        @endif
    </datalist>
</div>
