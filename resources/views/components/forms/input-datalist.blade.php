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
        x-data
        x-on:change="$dispatch('filter-changed', { key: '{{ $wire_model }}', value: $event.target.value })"
        x-on:form-reset.window="$el.value = ''; $event.target.value = '';"
        class="py-2 px-3 bg-white border border-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-400"
        list="{{ $elements_label }}"
        placeholder="{{ $text_placeholder }}"
    />
    <datalist id="{{ $elements_label }}">
        @if ($elements)
            <option value=""></option>

            @foreach ($elements as $element)
                <option value="{{ $element }}">{{ $element }}</option>
            @endforeach
        @endif
    </datalist>
</div>