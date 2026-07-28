@props([
    'as' => 'button',
    'text' => null,
    'subtext' => null,
    'show_subtext' => false,
    'is_disabled' => false,
])

@php
    if ($text === null || $text === '') {
        throw new \InvalidArgumentException('The [text] prop is required for <x-button-next-previous>.');
    }

    // Decode entities so screen readers announce real characters (e.g. "← Previous").
    // When a subtext is shown, fold it in for context (e.g. "← Previous: Poem #1"),
    // gated on show_subtext so the accessible name matches the visible content.
    $label = trim(html_entity_decode($text));

    if ($show_subtext && filled($subtext)) {
        $label .= ': '.$subtext;
    }

    // Only these three tags are supported; anything else falls back to a button.
    $tag = in_array($as, ['a', 'button', 'span'], true) ? $as : 'button';

    // is_disabled drives the muted look independently of the tag, so a disabled
    // <button> (e.g. the desktop pager on the first/last page) can be muted too.
    // Coerce so both is_disabled="true" (string) and :is_disabled="true" (bool) work.
    $disabled = filter_var($is_disabled, FILTER_VALIDATE_BOOLEAN);

    // Enabled anchors/buttons wear the cyan→magenta card-rectangle border; the
    // disabled state stays muted and borderless so it clearly reads as inactive.
    $base = $disabled
        ? 'border border-gray-300 dark:border-gray-600 bg-gray-400 dark:bg-gray-600 relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 cursor-not-allowed'
        : 'card-rectangle group block p-4 transition duration-200 hover:bg-cyan-200 dark:hover:bg-cyan-800 cursor-pointer';
@endphp

@if ($tag === 'a')
    <a {{ $attributes->merge(['class' => $base]) }} aria-label="{{ $label }}">
        <span class="block text-xs font-semibold uppercase tracking-wider text-gray-800 dark:text-gray-200 font-subtext">{!! $text !!}</span>

        @if ($show_subtext)
            <span class="mt-2 block text-lg font-semibold text-cyan-900 dark:text-cyan-200 group-hover:text-cyan-700 dark:group-hover:text-cyan-100">{{ $subtext }}</span>
        @endif
    </a>
@elseif ($tag === 'span')
    <span {{ $attributes->merge(['class' => $base]) }} aria-label="{{ $label }}">
        <span class="block text-xs font-semibold uppercase tracking-wider text-gray-800 dark:text-gray-200 font-subtext">{!! $text !!}</span>

        @if ($show_subtext)
            <span class="mt-2 block text-lg font-semibold text-cyan-900 dark:text-cyan-200 group-hover:text-cyan-700 dark:group-hover:text-cyan-100">{{ $subtext }}</span>
        @endif
    </span>
@else
    <button type="{{ $attributes->get('type', 'button') }}" {{ $attributes->except('type')->merge(['class' => $base]) }} aria-label="{{ $label }}">
        <span class="block text-xs font-semibold uppercase tracking-wider text-gray-800 dark:text-gray-200 font-subtext">{!! $text !!}</span>

        @if ($show_subtext)
            <span class="mt-2 block text-lg font-semibold text-cyan-900 dark:text-cyan-200 group-hover:text-cyan-700 dark:group-hover:text-cyan-100">{{ $subtext }}</span>
        @endif
    </button>
@endif
