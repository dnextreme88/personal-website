# Cyberpunk / retro-futuristic design pass

> ## ✅ EXECUTED 2026-07-25 — outcome
>
> Built on branch `feature/20260725-cyberpunk-design` (verified end-to-end in the browser;
> **not yet merged**). A full-immersive cyberpunk layer was added over the existing
> blue/indigo/purple base with electric **cyan + magenta** neon accents, everything guarded by
> `prefers-reduced-motion`.
>
> - **Neon foundation** ([resources/css/app.css](../../resources/css/app.css)) — neon color tokens
>   in `@theme` plus reusable utilities: `text-glow`, `neon-border`, `clip-notch`, `btn-call-to-action`,
>   `cyber-link`, `hud-frame`/`hud-corner`, `cyber-grid`, `scanline-overlay`, `glitch`, `hero-flicker`,
>   `blinking-cursor`, and `flicker`/`rise`/`grid-drift`/`cyber-scan` keyframes. A trailing
>   `@media (prefers-reduced-motion: reduce)` block stills every animation and keeps `hero-flicker`
>   visible.
> - **Buttons & nav** — `x-button-call-to-action` (notched face + neon glow via a `drop-shadow` wrapper so the
>   glow survives the clip + hover scanline sweep); `button-contact-me` recomposed onto it keeping its
>   shimmering gradient; `cyber-link` neon-underline on desktop nav; neon active/hover on the mobile
>   nav link and pagination.
> - **Hero & text** ([homepage.blade.php](../../resources/views/livewire/homepage.blade.php)) —
>   glowing headings; `Welcome!` rises in then neon-flickers (`hero-flicker`); `Web Developer.` is a
>   looping typewriter with a blinking caret (types → holds 3s → loops, JS in `app.js`); `Current
>   Specs!` keeps the RGB-split glitch; the intro paragraph and the last spec value carry a blinking
>   cursor; specs render as a `>`-prefixed terminal readout with a scramble/decode reveal on scroll.
> - **Cards, ambient & loaders** — `x-hud-corners` targeting brackets + hover glow on blog cards and
>   stat cards; neon-pulse timeline marker; site-wide CRT `x-scanline-overlay`; drifting Tron grid on
>   the Welcome section; cyber-cyan skeleton shimmer, and neon spinner.
>
> **Added:** `resources/views/components/button-call-to-action.blade.php`,
> `resources/views/components/hud-corners.blade.php`,
> `resources/views/components/scanline-overlay.blade.php`,
> `tests/Feature/CyberComponentsTest.php`.
>
> **Deviations from the plan:** glitch is pure CSS (no Alpine `glitchText`); `Web Developer.` became a
> looping typewriter rather than a glitch (the two can't cleanly coexist), so the glitch now lives on
> `Current Specs!` only; nav links use a neon-underline sweep instead of per-link glitch; HUD cards use
> corner brackets + glow rather than re-plumbing the SVG `border-tracer` into every card.
>
> **Verified:** `npm run build` clean; DOM/CSSOM checks in dark + light mode (glow, glitch timing,
> typewriter loop, scanline overlay, HUD corners, decode init, cyber-link light-vs-dark hover); no
> console errors; 8 component tests pass (`vendor/bin/pest tests/Feature/CyberComponentsTest.php`).

> ## 🔧 REFINED 2026-07-26 — follow-up changes
>
> Iterated on the first pass — mostly the CTA button, hero text, cards, and JS structure:
>
> - **CTA button** — `cyber-btn` renamed to `btn-call-to-action`. The notched button now draws a
>   cyan→magenta **gradient border that follows the clip path** (two stacked clipped layers, per
>   [stackoverflow.com/a/70238234](https://stackoverflow.com/a/70238234)) instead of a plain `border`
>   the diagonal notch would cut off. Added a desktop-only pressed state (`:active`, gated on
>   `@media (hover:hover) and (pointer:fine)`).
> - **Hero & text** — the intro paragraph is now a run-once typewriter and `Web Developer.` a looping
>   one, both starting only when scrolled into view, with per-element `data-typewriter-speed`. The
>   glitch is slower and only animates in view (`.glitch:not([no-intersect])`). The scramble/decode
>   text shows scrambled glyphs until it scrolls in. Hero section recoloured to flat gray.
> - **JS split out of `app.js`** — the typewriter and scramble logic now live in their own modules,
>   `resources/js/typewriter.js` and `resources/js/cyber-text.js`, each self-registering on
>   `livewire:navigated`; `app.js` just imports them.
> - **Cards** — `cyber-card` renamed to `card-square`; new `card-rectangle` carries only the
>   cyan→magenta gradient border (no glow/inner shadow) and styles the blog prev/next buttons. Both
>   card borders are now a clean 50/50 cyan→magenta split (transparent middle band removed). Blog
>   cards dropped `hud-frame` (it clobbered their scale transition) and trigger the HUD corners via
>   `group` hover instead. Timeline current-item border → neon cyan.
> - **New `x-clipped-table` component** — applies the CTA's corner-bevel clip-path to a `<table>`, with
>   a `corners` prop (`tl`/`tr`/`bl`/`br`, any combination) and a `size` prop. It draws a cyan→magenta
>   **gradient border that follows the bevels** using the same two-layer clip technique as the CTA
>   (a gradient frame `<div>` clipped to the outer shape wrapping the table, which is inset 2px and
>   re-clipped to the same shape). Used on the Steam achievements table
>   ([list-steam-achievement.blade.php](../../resources/views/livewire/archive/list-steam-achievement.blade.php))
>   with `tl tr`.
>
> **Added:** `resources/views/components/clipped-table.blade.php`,
> `resources/js/typewriter.js`, `resources/js/cyber-text.js` (+ clipped-table tests in
> `CyberComponentsTest.php`).

> ## 🔧 REFINED 2026-07-26 (round 2) — table readability + reusable sortable header
>
> Iterated on the Steam achievements table and grew `x-clipped-table` from a border-only wrapper into
> a self-contained sortable table. Driven by readability feedback (the neon interior was hard to read).
>
> - **Table readability** ([list-steam-achievement.blade.php](../../resources/views/livewire/archive/list-steam-achievement.blade.php)) —
>   the first pass styled the interior neon (cyan headers, cyan gridlines, mono/zero-padded index &
>   dates, neon tag pills, HUD corner brackets, and an animated cyan row-hover scan sweep). That read
>   as flashy and low-contrast, so it was walked back: removed the HUD corner hover and the row scan
>   sweep (the `.cyber-row` CSS was added then deleted); row text is now high-contrast neutral
>   (`text-gray-900 dark:text-white`); cell gridlines are neutral gray; tag pills are neutral gray; the
>   only colour left on the body is the **border**. Header labels stay cyan as the accent and the
>   active sort indicator is magenta.
> - **The gradient-bleed fix** — the two-layer clip technique only shows the cyan→magenta gradient as a
>   border if the table on top is *opaque*. The table had no background, so the gradient bled through
>   every transparent row (the real source of "colour on the rows"). Fixed by giving the table an
>   opaque page-surface background (`bg-gray-100 dark:bg-gray-900`), confining the gradient to the 2px
>   border.
> - **Zebra striping prop** — `x-clipped-table` gained `striped` + `stripeColor` (default a neutral
>   translucent grey `rgba(128, 128, 128, 0.12)`), exposed to CSS via a `--clipped-table-stripe` var
>   read by a new `.clipped-table--striped tbody tr:nth-child(even)` rule in `app.css`.
> - **Managed header + client-side sorting** — the `<thead>` and all sort logic moved *into*
>   `x-clipped-table` for unified header styling. New props: `columns` (ordered `['key' => 'Label']`
>   map), `rows` (data), `isSortable`, `sortableColumns` (keys). In managed mode the component renders
>   a unified `<thead>`, owns the Alpine state (`sortColumn`/`sortDirection`/`sort()`/`indicator()`/
>   `sortedRows`) seeded from `rows`, and wraps the slot in `<tbody>`; the caller passes only the row
>   templates. Sorting is a single **generic** comparator (`String(a[key]).localeCompare(…, {numeric:
>   true, sensitivity: 'base'})`) — so `tags` now sorts alphabetically by raw tag text (the old
>   "group tagged vs untagged" nuance was dropped) and `date_completed` sorts on the ISO field while
>   the cell shows the formatted value. When `columns` is empty the component renders the slot as-is
>   (**legacy mode**, backward compatible).
>
> **Changed:** `resources/views/components/clipped-table.blade.php` (props + managed header/sort),
> `resources/views/livewire/archive/list-steam-achievement.blade.php` (declarative API, neutral rows),
> `resources/css/app.css` (`.clipped-table--striped`), `tests/Feature/CyberComponentsTest.php`
> (striping + managed-header/sort tests).
>
> **Verified:** `npm run build` clean; `vendor/bin/pest --filter=CyberComponents` (managed-header,
> whitelist sorting, sorting-off, row-seed, legacy-mode, striping) passes; live DOM checks in light +
> dark — border-only gradient, neutral opaque rows, header from the columns map, and live sorting
> (Game ↑/↓ numeric-aware, Date ↑ chronological, indicator resets on column switch).
>
> **Still open / not in scope here:**
> - The Steam achievements table is the **only** HTML `<table>` in the app, so `x-clipped-table`'s new
>   managed-header/sort feature currently has a single consumer. The sold-items page
>   ([list-sold-item.blade.php](../../resources/views/livewire/archive/list-sold-item.blade.php)) is a
>   card **grid** with **server-side** sorting (`apply_sort`) and still uses the pre-cyberpunk **blue**
>   accent on its sort/view controls — the main candidate if cross-page sort-UI consistency is wanted,
>   but a separate change (different sorting mechanism, not a `<table>`).
> - Pre-existing failing test: `CyberComponentsTest` "renders the cyber button …" expects a
>   `neon-border` class that `x-button-call-to-action` no longer emits (unrelated to this table work).

> ## 🔧 REFINED 2026-07-27 (round 3) — component polish: cards, scanlines, charts, unified inputs, CTA gradient
>
> A round of targeted fixes across the cyberpunk components, each with a non-obvious root cause:
>
> - **Sold-items card hover-zoom** ([list-sold-item.blade.php](../../resources/views/livewire/archive/list-sold-item.blade.php)) —
>   `.card-square` was on both the wrapper and the `<img>`, with `hover:scale-105` on the image, so the
>   image zoomed out from under a fixed neon border. Root cause: `<img>` is a replaced element and
>   can't render the `::before` that draws the border. Moved `hover:scale-105` (+ transition) onto the
>   `.card-square` wrapper and dropped the no-op `card-square` from the image, so border + image scale
>   together.
> - **Card border overlapping the sticky nav** — `.card-square::before` (the neon border) has
>   `z-index: 20`, and the card created no stacking context, so that z-index leaked into the root
>   context and painted over the sticky nav (`z-1`). Fixed by adding `isolation: isolate` to
>   `.card-square`, trapping the border's z-index inside each card (fixes sold-items, blog, stat cards).
> - **`.card-square` vibrancy** — the neon border washed out in light mode; bumped the masked-gradient
>   `::before` to full opacity in light (dark kept at `0.85` via `.dark` override) and grew the glow
>   into a punchier dual-colour cyan+magenta box-shadow (stronger in light, softer in dark).
> - **Scanline overlay was invisible** ([scanline-overlay.blade.php](../../resources/views/components/scanline-overlay.blade.php)) —
>   the lines were ~1% contrast (faint alphas × `opacity-60` × `mix-blend-overlay` over mid-grey).
>   Reworked `.scanline-overlay` to a hard 1px hairline every 3px, with a `.dark .scanline-overlay`
>   cyan variant so it stays visible on the near-black dark theme, and dropped `mix-blend-overlay` +
>   `opacity-60` from the component so the lines render at their literal alpha.
> - **Sold-items charts didn't follow the theme toggle** ([sold-items-chart.js](../../resources/js/sold-items-chart.js)) —
>   charts were built once with the then-current `isDark`; the theme toggle only flips `.dark` and
>   fires no event, so switching mode left stale colours (dark-mode light text on a light background).
>   Added a `MutationObserver` on `<html>`'s class that rebuilds the charts when the dark state flips,
>   and grouped the colours into an editable block at the top of `buildConfig`.
> - **Unified form inputs into `.cyber-input`** ([input-text](../../resources/views/components/forms/input-text.blade.php),
>   [input-datalist](../../resources/views/components/forms/input-datalist.blade.php),
>   [input-select](../../resources/views/components/forms/input-select.blade.php)) — introduced a
>   shared `.cyber-input` class (in `app.css`, built with `@apply`) carrying the common field styling:
>   `py-2 px-3 rounded-none`, `bg-gray-200 dark:bg-gray-800`, `text-gray-800 dark:text-gray-200`,
>   `border` `gray-800 dark:gray-200`, hover `cyan-800 dark:cyan-200`, a 2px cyan focus outline, and a
>   `0.2s` transition. Redundant per-component utilities were removed; the native `<select>` was
>   aligned to the shared grey (was `bg-white`) and input-text to `px-3`/`rounded-none`. The animated
>   select's open state uses `border-cyan-400!` (important) so it beats the class's same-specificity
>   base border. Kept in the **utilities** layer so its `:hover`/`:focus` out-specify the base colour.
> - **Restored the Contact Me button gradient** ([button-call-to-action.blade.php](../../resources/views/components/button-call-to-action.blade.php),
>   [navigation-menu.blade.php](../../resources/views/components/navigation-menu.blade.php)) — the
>   `surfaceClass` prop had been snake-cased to `surface_class`, but the nav still passed the kebab
>   attribute `surface-class`. Blade only auto-binds a kebab attribute to a prop whose `Str::kebab()`
>   form matches — true for camelCase (`surfaceClass`→`surface-class`) but **not** snake_case
>   (`surface_class`→`surface_class`). So the gradient classes silently stopped binding and fell
>   through as an inert attribute, leaving the fill span plain grey. Since the project's linter enforces
>   snake_case props, the usages now pass `surface_class="…"` (snake), restoring
>   `bg-gradient-blue animate-shimmering-gradient bg-size-[800%_800%]`. The test was updated to the
>   snake attribute and strengthened to assert the class lands on the fill span (not as a stray attr).
>
> **Verified:** `npm run build` clean throughout; `vendor/bin/pest --filter="…"` for the touched
> component tests; live DOM/CSSOM + compiled-CSS checks (border/stacking/isolation, scanline computed
> styles, chart canvas pixel sampling across a theme toggle, `.cyber-input` computed values, and a
> Blade render proving the CTA surface class binds to the fill span).
>
> **Verification caveats:** the headless preview pane doesn't composite frames, so CSS transitions
> freeze at their start value and `:focus`/screenshots are unreliable — those were verified by reading
> the resolved target value with the transition bypassed, or against the compiled CSS. The dev server
> on `:8000` was intermittently down; some passes used compiled-CSS/render checks instead of the browser.
>
> **Still open:** the `neon-border` CTA test noted above still fails (pre-existing, unrelated); and
> `.cyber-input`'s snake_case `surface_class`-style fragility means any future *kebab* `surface-class`
> usage would silently break again — a camelCase prop would be more robust but the linter reverts it.

## Context

The portfolio already leaned cyber-retro (Audiowide / Chakra Petch / JetBrains Mono / Space Mono
fonts, a `Neon Retro` logo font, `border-tracer` SVG stroke animation, shimmering-gradient buttons,
`intersect` scroll reveals) but read as a clean modern site rather than cyberpunk. It lacked the
**glow, grit, and HUD framing** that sells the aesthetic. The goal was a full-immersive cyberpunk
pass — neon glow, glitch/typewriter/decode text, animated grid + scanline ambient, notched glowing
buttons, HUD-framed cards, and cyber loaders — while keeping both light and dark modes readable
(dark mode is the priority) and honouring reduced-motion.

## Approach

Build a small, reusable **neon design layer** once, then adopt it across components rather than
scattering one-off styles:

1. **CSS token + utility layer** in `resources/css/app.css` — neon tokens in `@theme`, glow / notch /
   neon-border / scanline / grid / glitch / flicker / typewriter-cursor utilities and keyframes in
   `@layer utilities`, plus one reduced-motion guard. Single source of truth.
2. **Reusable Blade primitives** in `resources/views/components/` — `x-button-call-to-action`, `x-hud-corners`,
   and `x-scanline-overlay` — so existing components adopt the look by composition.
3. **Alpine/vanilla behaviours** in `resources/js/app.js` alongside the existing
   `window.darkModeSwitcher` pattern — `window.initCyberText` (scramble/decode reveal) and
   `window.initTypewriter` (looping type effect), both wired into the `livewire:navigated` lifecycle
   and both no-ops under reduced-motion.

## Verification

1. `vendor/bin/pint` before committing (project-wide reformats of unrelated files were reverted).
2. `npm run build`, then browse `/` in **dark mode** (toggle top-right): headings glow, `Welcome!`
   flickers, `Web Developer.` types on a loop, `Current Specs!` glitches, specs decode in with a
   cursor, grid drifts, scanlines present, buttons/nav notched & glowing, cards show corner brackets.
   Confirm light mode stays readable (notably the softened `cyber-link` hover).
3. With OS "reduce motion" on, confirm animations stop and all text is static and fully visible.
4. `vendor/bin/pest tests/Feature/CyberComponentsTest.php` — component render smoke tests. Note:
   running the **full** Pest suite wipes the dev DB; reseed with `php artisan migrate:fresh --seed`.
