@props([
    'href',
    'label',
])

{{-- Text link set in brackets, e.g. [ About me ]. A quieter sibling of x-button-call-to-action. --}}
<a
    wire:navigate
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'btn-bracket']) }}
>
    <span aria-hidden="true">[&nbsp;</span>{{ $label }}<span aria-hidden="true">&nbsp;]</span>
</a>
