@props([
    // Map of target id => link label, e.g. ['about' => 'About']. Put each id on the
    // section's heading: links jump to it and the active link follows the headings.
    'sections' => [],
    // How close (in px) a heading must come to the bar's bottom edge before its link
    // becomes active. 80 = once the heading's top is within 80px below the bar. Lower
    // it to switch later (closer to the bar), raise it to switch earlier.
    'active_offset' => 80,
    // Optional id of a section (e.g. the hero). When set, the bar is fixed below the site
    // header and only shown once that section has scrolled up under the bar: it fades in
    // as the section's bottom edge passes the bar's own bottom edge (so the next section
    // is what sits under the bar), and fades out again when the visitor scrolls back
    // into it. Without it the bar is sticky and always shown.
    'reveal_after' => null,
    // With reveal_after: how many pixels early the bar appears. 0 = exactly when the
    // section's bottom edge reaches the bar; 100 = when it is still 100px below the bar.
    'reveal_lead' => 0,
])

{{-- In-page navigation below the site header (app-layout publishes --site-header-h).
     The link of the heading most recently scrolled past is marked active. --}}
<nav
    aria-label="Page sections"
    x-data="{
        active: '{{ array_key_first($sections) }}',
        shown: {{ $reveal_after ? 'false' : 'true' }},
        ids: @js(array_keys($sections)),

        {{-- The active link is the last heading whose top has passed the line a little
             below the bar. At the very bottom of the page the last link wins, since a
             short final section may never reach the line. --}}
        pickActive() {
            const header = document.getElementById('site-header');
            const line = (header?.offsetHeight ?? 0) + this.$el.offsetHeight + {{ (int) $active_offset }};
            const atBottom = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 2;

            let current = this.ids[0];

            for (const id of this.ids) {
                const heading = document.getElementById(id);

                if (heading && heading.getBoundingClientRect().top <= line) {
                    current = id;
                }
            }

            this.active = atBottom ? this.ids[this.ids.length - 1] : current;
        },
    }"
    {{-- Runs on every scroll event: it only reads a few heading positions, and a
         requestAnimationFrame throttle would stall in background tabs. --}}
    x-on:scroll.window.passive="pickActive()"
    x-on:resize.window="pickActive()"
    x-init="
        pickActive();

        {{-- Content that loads late (images, posts) moves the headings without a scroll
             event, so check again whenever the page changes height. --}}
        new ResizeObserver(() => pickActive()).observe(document.body);

        @if ($reveal_after)
            const revealAfter = document.getElementById('{{ $reveal_after }}');

            if (revealAfter) {
                const header = document.getElementById('site-header');
                let revealObserver = null;

                {{-- The trigger line is the bar's bottom edge (header height + bar height),
                     moved down by reveal_lead. The section counts as gone once its bottom
                     edge is above that line. Rebuilt when the header changes height. --}}
                const watchSection = () => {
                    revealObserver?.disconnect();

                    const line = (header?.offsetHeight ?? 0) + $el.offsetHeight + {{ (int) $reveal_lead }};

                    revealObserver = new IntersectionObserver(([entry]) => {
                        shown = ! entry.isIntersecting;
                    }, { rootMargin: `-${line}px 0px 0px 0px` });

                    revealObserver.observe(revealAfter);
                };

                watchSection();
                header && new ResizeObserver(watchSection).observe(header);
            } else {
                shown = true;
            }
        @endif
    "
    @if ($reveal_after)
        {{-- Object syntax so Alpine also removes the static hidden classes when shown. --}}
        x-bind:class="{ 'invisible opacity-0 -translate-y-2': ! shown, 'visible opacity-100 translate-y-0': shown }"
    @endif
    {{ $attributes->merge(['class' => ($reveal_after
        ? 'fixed inset-x-0 top-(--site-header-h,0px) invisible opacity-0 -translate-y-2 transition-[opacity,translate,visibility] duration-300 ease-out motion-reduce:transition-none'
        : 'sticky top-(--site-header-h,0px)').' z-30 bg-gray-200 dark:bg-gray-800']) }}
>
    <ul class="mx-auto flex max-w-7xl gap-6 overflow-x-auto whitespace-nowrap px-6 py-3 lg:px-8">
        @foreach ($sections as $id => $label)
            <li>
                <a
                    href="#{{ $id }}"
                    x-bind:class="active === '{{ $id }}' ? 'text-cyan-600 dark:text-neon-cyan' : 'text-gray-600 dark:text-gray-400'"
                    x-bind:aria-current="active === '{{ $id }}' ? 'true' : null"
                    class="anchor-link font-subtext text-sm font-medium uppercase tracking-[0.15em] hover:text-cyan-800 dark:text-gray-400 dark:hover:text-neon-cyan focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-cyan-700 dark:focus-visible:outline-neon-cyan"
                >{{ $label }}</a>
            </li>
        @endforeach
    </ul>
</nav>
