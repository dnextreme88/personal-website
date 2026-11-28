> ## ✅ EXECUTED 2026-11-29
>
> Final state (differs from the plan below in these points):
>
> - **No `@layer components`.** The multi-class rules stay as plain CSS in `@layer utilities`. In that
>   layer they come after Tailwind's own utilities, so they still override classes such as `-mb-12` on
>   `.portrait-ring--hero`, `border-gray-300` on `.route-card` and `bg-gray-100` on `.route-node`.
>   Moving them to `@layer components` would let those Tailwind classes win.
> - **`.cyber-link` renamed to `.header-nav-link`** (it is used only in the header nav). It now applies
>   `tracking-widest`; the `tracking-wider` class on the three links is removed.
> - **Dead classes removed.** As `@utility`, `logo` and `cyber-input` no longer override Tailwind classes
>   on the same element. Those classes had no effect before, so they are removed to keep the look:
>   `text-xl` on the logo, `pr-8` on the two `input-select` elements, `rounded-md px-3.5` on the contact
>   textarea.
> - **Owner edits included:** section headings on the homepage use `tracking-wider`; the bracket link uses
>   `tracking-widest`. Tests updated and added in `HomepageTest` and `CyberComponentsTest`.
> - **Skipped:** `npm audit` reports a moderate issue in `postcss-selector-parser` (via
>   `@tailwindcss/typography`); the only offered fix is a downgrade.
>
> Verification: `npm run build` OK; `vendor/bin/pest` 115 passed; `vendor/bin/pint --test` OK. Computed
> styles of every element with a custom class were recorded on 12 pages, light and dark, before and
> after. The only remaining differences are the intended letter-spacing changes. Hover and focus states
> were not part of that comparison.

# Package updates + Tailwind v4 `@utility` migration

## Context

The npm and Composer packages are behind their latest releases. The user wants every package updated, with syntax changes where an update needs them. Two syntax changes apply:

1. `tailwindcss-intersect` 3.0.0 moved the `Observer` to its own entry point.
2. The custom classes in `@layer utilities { .x {} }` must move to the Tailwind v4 `@utility x {}` syntax, so that variants (`hover:`, `md:`, `dark:`) work on them and unused ones are removed from the build.

`npm outdated` and `composer outdated --direct` show only one major bump: `tailwindcss-intersect` 2 → 3. All other updates are minor or patch updates in the current major.

## Step 0 — Branch and plan file

- `git pull` on `master`. Create branch `chore/20261129-package-updates`.
- The working tree has an uncommitted change in `resources/views/components/button-bracket.blade.php`. Leave it alone. Do not commit it.
- Copy this plan to `docs/plans/20261129-package-updates.md`. Add an entry in `docs/plans/README.md` (link the dated file, bold the date).

## Step 1 — package.json

| Package | From | To |
| --- | --- | --- |
| `@tailwindcss/postcss`, `tailwindcss` | ^4.3.2 | ^4.3.5 |
| `axios` (exact pin, keep exact) | 1.18.1 | 1.21.1 |
| `chart.js` | ^4.5.1 | ^4.6.2 |
| `concurrently` | ^10.0.3 | ^10.0.7 |
| `laravel-vite-plugin` | ^3.1.0 | ^3.2.1 |
| `postcss` | ^8.5.17 | ^8.5.34 |
| `tailwindcss-intersect` | ^2.2.0 | ^3.0.0 |
| `vite` | ^8.1.4 | ^8.4.0 |

`@tailwindcss/forms`, `@tailwindcss/typography`, `@tailwindplus/elements` are already current. Run `npm install`.

**Syntax change** in `resources/js/app.js:3`:
```js
import { Observer } from 'tailwindcss-intersect/observer';
```
`@import 'tailwindcss-intersect';` in `app.css:2` stays the same (v3 docs use the same CSS import).

## Step 2 — composer.json

| Package | From | To |
| --- | --- | --- |
| `filament/filament` (exact pin, keep exact) | 5.6.8 | 5.12.0 |
| `filament/upgrade` (exact pin, keep exact) | 5.6.7 | 5.12.0 |
| `laravel/framework` | ^13.8.0 | ^13.44.1 |
| `laravel/tinker` | ^3.0.2 | ^3.2.0 |
| `livewire/livewire` | ^4.3.3 | ^4.4.10 |
| `laravel/pail` | ^1.2.7 | ^1.3.0 |
| `laravel/pint` | ^1.29.2 | ^1.32.3 |
| `laravel/sail` | ^1.62.0 | ^1.71.0 |
| `mockery/mockery` | ^1.6 | ^1.6.15 |
| `nunomaduro/collision` | ^8.9.3 | ^8.9.6 |
| `pestphp/pest` | ^4.7.5 | ^4.7.8 |

Run `composer update -W`. The `post-autoload-dump` hook runs `filament:upgrade` already. All are semver-safe, so no PHP syntax change is expected. If `vendor/bin/pint` reformats files because of new Pint rules, keep that as a separate commit.

Also update the stale **Stack** list in `CLAUDE.md` (it still says Laravel 12, Filament 4, Livewire 3, Pest 3) to the new versions.

## Step 3 — `@layer utilities` → `@utility`

### Rules of `@utility` (Tailwind v4)

- The name is one plain class name. A comma list (`.a, .b`) or a compound root (`.a .b`, `.a.is-x`) is not allowed.
- Inside the block, nesting with `&` is allowed: `&:hover`, `&::before`, `&:not([no-intersect])::before`, `.group:hover &`, and `@media`.
- `.dark .x {}` becomes `@variant dark { ... }` inside the block (it uses the project's `@custom-variant dark`).
- `@keyframes` must move out of the block to the top level of `effects.css`.
- Tailwind writes a utility to the build only if the class name appears in a scanned file. I checked: all class names are full strings in Blade/JS (`badge.blade.php`, `hud-corners.blade.php`, `latest-posts.blade.php`), so no class gets lost.

### My suggestion: which classes become `@utility`

**Convert to `@utility`** (one root class, used like a utility):

- `app.css`: `h-25-vh`, `h-50-vh`, `h-75-vh`, `w-25-vw`, `w-50-vw`, `w-75-vw`, `max-h-*-vh`, `max-w-*-vw` (12 total), `cyber-input`, `logo`, `border-tracer` (with `.group:hover &`), `bg-gradient-blue`, `text-glow`, `text-glow-magenta`, `neon-border` (with `&:hover`), `clip-notch`, `btn-call-to-action`, `btn-submit`, `btn-clear`, `btn-bracket`, `anchor-link` (split into two blocks), `header-nav-link` (renamed from `cyber-link`; with `&::after`, `@variant dark`), `hud-frame`, `cyber-grid`, `scanline-overlay`, `badge-neon-cyan`, `badge-neon-magenta`, `route-rail-fill`.
- `effects.css`: `marching-border`, `animate-shimmering-gradient`, `skeleton-loader`, `skeleton-loader-lg`, `gradient-gray-1/2/3`, `btn-submit-sweep`, `card-square-reflection`, `animate-grid-drift`, `animate-ring-orbit`, `animate-flicker`, `hero-flicker`, `glitch`, `blinking-cursor`, `animate-cyber-scan`.

**Move to `@layer components`** (component parts, element chains, state from JS, or a shared `::before` for two classes). These stay plain CSS, because `@utility` cannot express them without copies:

- `specs-list`, `clipped-table--striped`, `prose-sold-items-desc` (they style child elements).
- `card-square` / `card-rectangle` (they share one `::before` rule and use `:has()`).
- `hud-corner*` (the hover comes from a parent `.hud-frame` or `.group`).
- `portrait-ring*`, `hero-identity*` (BEM parts, media queries for each part).
- `filmstrip*`, `route-node`, `route-card`, `route-station …` (state classes set by JS).
- `screenshot-ghost*`, `.screenshot-frame.is-glitching …`, the route beacon `::before/::after`.

The `@layer base` block and both `@media (prefers-reduced-motion)` blocks stay as they are.

Optional, only if the user wants it later: fold the 18 `@apply` lines in `cyber-input` into one `@apply` line. Not in scope.

### Risk

`@layer components` comes before `utilities`. A Tailwind class on the same element now wins over a component rule where before the file order decided. Also, Tailwind sorts `@utility` rules with its own utilities. `cyber-input` depends on its `hover:`/`focus:` border colors that win over the base `border-*` of each input (comment at `app.css:70`). Check these points in the browser.

## Verification

1. `npm run build`. It must finish with no errors.
2. `vendor/bin/pest`. All tests must pass. Then run `php artisan migrate:fresh --seed`, because the suite empties the dev DB.
3. `vendor/bin/pint --test`.
4. `composer run dev` and open the site in the browser pane, in light mode and dark mode:
   - Homepage: hero flicker, portrait ring, glitch text starts on scroll (intersect observer works), cyber links, HUD corners on hover.
   - Contact form: `cyber-input` hover/focus borders, submit sweep, button press.
   - Blog latest posts (`gradient-gray-*`), cards (`card-square`, reflection), badges.
   - Archive: sold items, game screenshots filmstrip and glitch swap, dropping areas route.
   - Calendar marching border, `/admin` Filament panel loads, sold items chart renders.
5. Compare the built CSS size before and after (unused utilities drop out).
6. Commit in small commits (npm, composer, CSS migration, docs). Open a PR to `master` with `--assignee dnextreme88`.
