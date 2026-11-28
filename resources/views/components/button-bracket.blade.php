@props([
    'href',
    'label',
])

{{-- Text link set in brackets, e.g. [ About me ]. A quieter sibling of x-button-call-to-action. --}}
<a
    wire:navigate
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'btn-bracket font-loader inline-block text-sm font-medium uppercase tracking-wider text-cyan-800 hover:text-neon-magenta-dim dark:text-neon-cyan dark:hover:text-neon-magenta focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-cyan-700 dark:focus-visible:outline-neon-cyan']) }}
>
    <span aria-hidden="true">[&nbsp;</span>{{ $label }}<span aria-hidden="true">&nbsp;]</span>
</a>
