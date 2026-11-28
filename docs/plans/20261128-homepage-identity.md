> ## ✅ EXECUTED 2026-11-28
>
> Final state (the plan below was extended in several review rounds with the owner):
>
> - **Cursor and fonts:** the cyber reticle cursor is removed (JS, import, CSS). `font-subtext` and
>   `font-loader` are Geist Mono; Chakra Petch and JetBrains Mono are no longer loaded. Headings stay
>   Audiowide, text stays Exo 2.
> - **Identity hero (`.hero-identity`, `#top`):** one screen tall below the header at every width
>   (`100svh − 2rem − --site-header-h`; `#site-header` publishes its own height). One `h1` ("Web developer.",
>   typewriter) and the owner's intro sentence, with `x-button-bracket` links. "WELCOME!" and the six-photo
>   stack are gone; the old section `h1`s are `h2`.
> - **Portrait and ring (`x-portrait-ring`):** the 1152×1152 cutout `homepage-hero.webp`, black-and-white,
>   no frame, fading out at the bottom, with `srcset` (`homepage-hero-480.webp`, `-768.webp`, original) and a
>   `sizes` rule. A tilted ring of 18 grayscale 3:2 cards orbits it in 3D (`.animate-ring-orbit` in
>   effects.css, stopped under reduced motion), centred on the cutout's neck line (44% down, from its measured
>   silhouette), radius 47cqw so the whole ring stays inside the portrait box. Nine unique photos
>   (`public/images/portrait-ring/photo-1..9.webp`, 480×320 crops checked by eye), each shown twice. Thumbnails
>   matter: full-size photos as 3D layers left cards unpainted.
> - **Portrait placement:** below 1024px it sits in the flow under the text. From 1024px it stands on the hero's
>   bottom edge at the right, 2rem in from the edge, sized as the smallest of 90% of the hero height, 1152px
>   and the free width beside the fixed-width text column (`--hero-portrait-fit`; 28rem / 30rem text column
>   from 1024px / 1280px). Tall windows (no wider than 9:8) move the text to the top and give the portrait the
>   height below it (hero height − 25rem, or − 27rem from 1280px).
> - **About:** a paragraph and a facts panel (Based in, Stack, Now, Status). Its heading and sentence fade in
>   on scroll like the other section headings. Facts values are samples for the owner to confirm.
> - **Section nav (`x-section-anchor-nav`, scrollspy):** with `reveal_after="top"` it is fixed below the header
>   and hidden over the hero; it fades in as the hero's bottom edge passes the bar (`reveal_lead` px shows it
>   earlier). Anchor ids sit on the section headings (`scroll-mt-(--anchor-offset)` lands them 8px under the
>   bar). The active link is the last heading within `active_offset` (80px) of the bar; the last link wins at
>   the page bottom. It is checked on every scroll, resize and page-height change (no rAF throttle: it stalls
>   in background tabs).
> - **Verified:** `tests/Feature/HomepageTest.php` (11 tests) and the full suite (111 passed); Pint clean.
>   Browser checks at 375, 1024, 1280, 1440 and 1686px wide, tall and short windows, light and dark: one `h1`,
>   no `.cyber-reticle`, Geist Mono loaded, ring inside the viewport, nav reveal and active link at the
>   measured switch points, no horizontal overflow.

# Homepage identity hero, facts panel, Geist Mono and cursor removal

## Context

The user likes the sample portfolio at christiancolewan.tech and wants to borrow elements from it. The homepage must show the user's identity as a web developer, not archive content. After a review of interactive mockups, the user chose:

1. **Sticky anchor nav** (sample element 1): a small bar with links that jump to each homepage section.
2. **Identity hero** (sample element 2): a two-tone headline, one plain sentence, bracket-style text buttons and a portrait (`public/images/homepage-hero.webp`, added by the user). It replaces "WELCOME!" and the six-photo stack.
3. **About facts panel** (sample element 3): a short paragraph next to a key/value list.
4. **Fonts**: subtext (`font-subtext`) and buttons (`font-loader`) change to **Geist Mono**. Headings stay Audiowide, text stays Exo 2.
5. **Remove the custom cursor** ("cyber reticle"). It stays on the page after the browser Back button. The cause is the bfcache (back/forward cache), not memoization.

Keep on the homepage, below the new blocks: Relevant Experiences timeline, From the Blog, Current Specs.

Not chosen: selected work list, services list, contact close.

## Branch

- The current branch `chore/20261113-seeders` has an uncommitted change in `app/Livewire/Archive/ListSoldItem.php`. Ask the user to commit or stash it before the switch.
- `git pull` on `master`, then create `enhancements/20261128-homepage-identity`.

## Changes

### 1. Remove the reticle cursor

- Delete `resources/js/cyber-cursor.js`. Remove `import './cyber-cursor';` from `resources/js/app.js:6`.
- In `resources/css/app.css`, delete the `.cyber-reticle` rules (~lines 507–555) and the reduced-motion `.cyber-reticle` rule (~703–707).
- Update the comment in `resources/css/effects.css:489`. Do not touch `.hud-corner`.

### 2. Geist Mono for subtext and buttons

- `resources/css/app.css:14` → `--font-subtext: 'Geist Mono', ui-monospace, monospace;`
- `resources/css/app.css:15` → `--font-loader: 'Geist Mono', ui-monospace, 'Cascadia Code', monospace;`
- Update the comment at `app.css:138`.
- Google Fonts URL in `resources/views/livewire/app-layout.blade.php:22` and `resources/views/errors/404.blade.php:25`: remove `Chakra+Petch` and `JetBrains+Mono` (no other use), add `Geist+Mono:wght@400;500;600;700`. Update the comment at `app-layout.blade.php:19`.

### 3. Identity hero (replaces `homepage.blade.php` lines 6–77)

- One `<section id="top">` with a two-column grid on `lg` (text left, portrait right). One column on phones, portrait first, under the text.
- Headline as the page's only `h1`, `font-heading`:
  - Line 1: "Web developer." in full ink (`text-gray-900 dark:text-white`, keep `text-glow`).
  - Line 2: "TALL stack, end to end." in a softer tone (`text-gray-500 dark:text-gray-400`).
  - Keep the `data-typewriter` effect on line 1 only.
- One sentence (`font-text`): "I'm Jeanne Kevin T. Decena. I build Laravel and Livewire systems, from the data model to the admin panel."
- Two bracket buttons: `[ About me ]` → `route('about_me')`, `[ Contact me ]` → `route('contact-me')`.
- Portrait: `<img src="{{ asset('images/homepage-hero.webp') }}" alt="Portrait of Jeanne Kevin T. Decena">`, `aspect-4/5 object-cover`, keep the `card-rectangle` frame and the existing `intersect` slide-in.
- Keep the `cyber-grid` background layer. Remove the clip-path blur blob and the six `homepage-1..6.webp` images from the homepage (the files stay in `public/images`).
- Change the three existing section `h1` tags (Relevant Experiences, From the Blog, Current Specs) to `h2`, so the hero has the only `h1`. Keep their classes.

### 4. New reusable component: bracket button

- `resources/views/components/button-bracket.blade.php`, props `href`, `label`. Renders `<a wire:navigate>` with `[ label ]`, `font-loader uppercase text-sm tracking-wider`, `text-cyan-800 dark:text-neon-cyan`, hover `text-neon-magenta-dim dark:hover:text-neon-magenta`, visible focus ring. Hover/press transition stays in `app.css`.

### 5. About facts panel (new `<section id="about">` directly under the hero)

- Two columns on `lg`: paragraph left, facts list right. One column on phones.
- Paragraph (`font-text`): "Workflow Scripter at MachShip Philippines. Before that, a Laravel developer for a language review center. I like small systems that a team can maintain."
- Facts as a `<dl>`: `<dt>` in `font-subtext` (Geist Mono) uppercase small, `<dd>` in full ink:
  - Based in → Philippines
  - Stack → Laravel · Livewire · Filament
  - Now → MachShip Philippines
  - Status → Open to work (`text-cyan-800 dark:text-neon-cyan`)
- These values are sample values. Ask the user to confirm them during implementation.
- Rows divided by a tone step (`odd:bg-gray-200/60 dark:odd:bg-gray-900/40`), not by lines.

### 6. Sticky anchor nav (homepage only)

- New component `resources/views/components/section-anchor-nav.blade.php`, used at the top of `homepage.blade.php` after the hero.
- `sticky top-0 z-30`, plain background `bg-gray-200 dark:bg-gray-800` (no blur), `font-subtext uppercase text-xs`.
- Links: About `#about`, Experience `#experience`, Blog `#blog`, Specs `#specs`. Add these `id`s to the existing sections.
- Active link by Alpine with `IntersectionObserver` (`x-data`, `x-init`). The active link gets `text-cyan-800 dark:text-neon-cyan`. Add `scroll-mt-16` to each section so the sticky bar does not cover headings.
- On phones the bar scrolls sideways (`overflow-x-auto`, no wrap).

## Tests

- New `tests/Feature/HomepageTest.php` (opt in to `RefreshDatabase`, because `latest-posts` reads the DB):
  - GET `/` is 200, contains `homepage-hero.webp`, the `h1` text "Web developer.", the `id="about"` section and the four anchor links.
  - GET `/` contains `Geist+Mono` and not `Chakra+Petch` or `JetBrains+Mono`.
  - `resources/js/app.js` does not contain `cyber-cursor`, and `resources/js/cyber-cursor.js` does not exist.
- Run `vendor/bin/pest`. The suite empties the dev DB, so run `php artisan migrate:fresh --seed` after.
- Run `vendor/bin/pint`.

## Plan docs

- Save as `docs/plans/20261128-homepage-identity.md` and add an entry to `docs/plans/README.md` (link the dated filename, bold the date).
- Add a note to `docs/plans/20260725-cyberpunk-design.md` that the reticle was removed on 2026-11-28.

## Verification

1. `npm run build`, then `preview_start` the dev server.
2. Homepage at 1440px and 390px, light and dark mode: hero headline in two lines or fewer, portrait visible, facts panel aligned, sticky bar stays on scroll, anchor links jump to the right section, active link changes.
3. Keyboard: Tab reaches the bracket buttons and anchor links with a visible focus ring.
4. `document.querySelector('.cyber-reticle') === null` on `/`, `/blog` and the archive. Click a `wire:navigate` link, press Back, confirm no cursor ring.
5. `document.fonts.check('400 14px "Geist Mono"')` is true. A button's computed `fontFamily` starts with `Geist Mono`.
6. Take screenshots of each check for the user.
