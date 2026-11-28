{{-- Section heading in the "live feed" pill style. Only the pill look is built in: pass an
     id, spacing, scroll-mt or animation classes as attributes where the page needs them. --}}
<h2 {{ $attributes->merge(['class' => 'header-section-pill']) }}>
    <span aria-hidden="true">//</span> {{ $slot }}
</h2>
