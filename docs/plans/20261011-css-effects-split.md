# Move keyframe animations from app.css into effects.css (phase 1)

> **Outcome (executed 2026-10-11)**
> All keyframe animations moved from `resources/css/app.css` to the new `resources/css/effects.css`,
> which `app.css` pulls in with `@import './effects.css';`. No change to `vite.config.js` or the
> `@vite` lines.
> - **Verified**: the compiled CSS before and after the move has the same selectors and the same
>   `@keyframes` names. The only difference is the planned addition of `.btn-submit-sweep` to the
>   reduced-motion `animation: none` list.
> - **Reduced motion**: split per file. `effects.css` stills the keyframe effects; `app.css` keeps
>   the `.hud-corner` and `.cyber-reticle` transition rules.
> - **Comments**: `.bg-gradient-blue` and `.cyber-grid` in `app.css` point to their animation
>   classes in `effects.css`, and the reverse.

## Context

[resources/css/app.css](resources/css/app.css) is about 930 lines long. It mixes layout helpers, fonts, components, and animations. The user wants to split it into smaller files. Phase 1 moves all keyframe animations into one new file, `resources/css/effects.css`.

Scope (approved by the user): **keyframe animations only**. A rule moves if it uses `@keyframes`, uses `animation:`, or is part of an animated effect (for example the static base of `.glitch`). Hover and press `transition:` effects stay in `app.css` for a later phase.

The result must look and behave the same as now. This is a move, not a redesign.

## Pre-work (per CLAUDE.md)

1. The working tree is on `master` and has uncommitted changes: `app.css`, `archive.blade.php`, and the new `tests/Feature/ArchiveTest.php`. Do not stash or discard them.
2. Run `git pull` on `master`. Then create the branch `refactor/20261011-css-effects`. The uncommitted changes move to the branch.
3. **Do not commit.** The user wants to give the commit instruction themselves.

## Rules that move to `resources/css/effects.css`

Move each group as one block, with its comment and its `@keyframes`. Keep the source order of the groups.

| Effect | Classes / selectors | Keyframes |
| --- | --- | --- |
| Marching-ants border | `.marching-border` | `marching-border` |
| Shimmering gradient | `.animate-shimmering-gradient` | `shimmering-gradient` |
| Skeleton loaders | `.skeleton-loader`, `.skeleton-loader-lg` | `shimmer` |
| Animated gray gradients | `.gradient-gray-1`, `.gradient-gray-2`, `.gradient-gray-3` | `animated-gradient` |
| Submit-button sweep | `.btn-submit-sweep`, the `@media (hover: hover)` block with `.btn-submit:hover .btn-submit-sweep` | `btn-submit-sweep` |
| Shiny reflection | `.card-square-reflection`, `.card-square-reflection::after` (with the `overflow-clip` comment) | `shiny-reflection` |
| Grid drift | `.animate-grid-drift` | `grid-drift` |
| Neon flicker | `.animate-flicker` | `flicker` |
| Hero rise + flicker | `.hero-flicker` | `rise` |
| Glitch text | `.glitch`, `.glitch::before/::after`, both `:not([no-intersect])` rules | `glitch-top`, `glitch-bottom` |
| Blinking cursor | `.blinking-cursor::after` | `cursor-blink` |
| Cyber scan | `.animate-cyber-scan` | `cyber-scan` |

Rules that **stay** in `app.css`:
- `.btn-submit` and its `:active` press. These are transitions.
- `.cyber-grid`. This is a static background. `.animate-grid-drift` moves it. Add a one-line comment above `.animate-grid-drift` that says it pairs with `.cyber-grid` in `app.css`.
- `.bg-gradient-blue`. This is static, and it is used with `.animate-shimmering-gradient`. Add the same kind of pairing comment.
- All other transition and static rules: `.border-tracer`, `.neon-border`, `.btn-call-to-action`, `.btn-clear`, `.cyber-link`, HUD frame/corners, card frames, scanlines, badges, `.cyber-reticle`.

## Reduced-motion block

Split the `@media (prefers-reduced-motion: reduce)` block. Each file then owns the reduced-motion rules for its own rules.

- **effects.css** gets a new `@media (prefers-reduced-motion: reduce)` block at the end, with the same comment. It contains:
  - The `animation: none !important` list: `.animate-flicker`, `.animate-grid-drift`, `.animate-shimmering-gradient`, `.animate-cyber-scan`, `.marching-border`, `.skeleton-loader`, `.skeleton-loader-lg`, `.card-square-reflection::after`, `[class*="animate-"]`.
  - `.glitch::before`, `.glitch::after`, `.blinking-cursor::after` → `display: none !important`.
  - `.hero-flicker` → `animation: none` and `opacity: 1`.
  - New: `.btn-submit-sweep` → `animation: none !important`. The sweep is a keyframe animation, but the current block does not stop it. This one addition closes the gap. If the user wants a pure move only, skip this item.
- **app.css** keeps its block with the `.hud-corner` and `.cyber-reticle` transition rules only.

## File structure

`resources/css/effects.css`:

```css
/* Keyframe-driven effects (animations) for the site. Imported by app.css.
   Hover/press transitions still live in app.css. Motion is stilled under
   prefers-reduced-motion at the end of this file. */

@layer utilities {
    /* ...moved groups, in the order of the table above... */
}

@media (prefers-reduced-motion: reduce) {
    /* ...effects-only rules... */
}
```

In `app.css`, add the import directly after `@import 'tailwindcss-intersect';`:

```css
@import './effects.css';
```

Why this position:
- CSS requires every `@import` to come before other rules. Tailwind 4 inlines the imported file, so `@apply` (`relative`, `overflow-clip`, `inset-0`, `opacity-85`, `from-gray-*`, …) and the theme variables (`--color-neon-*`) still resolve.
- The moved rules use `.dark …` selectors, not `dark:` with `@apply`. Thus the import does not need the `@custom-variant dark` line above it.
- The moved rules stay in `@layer utilities`, so their cascade layer does not change. No moved rule has the same selector and property as a rule that stays in `app.css`. Thus the change in source order has no visible effect.

The Filament theme [resources/css/filament/admin/tailwind-theme.css](resources/css/filament/admin/tailwind-theme.css) imports `app.css`. It gets `effects.css` through that import, the same as now.

**Do not change [vite.config.js](vite.config.js) or [app-layout.blade.php](resources/views/livewire/app-layout.blade.php) / [404.blade.php](resources/views/errors/404.blade.php) (`@vite('resources/css/app.css')`).** The `@import` puts the content of `effects.css` into the compiled `app.css`. Thus the existing `app.css` entry and `@vite` lines already load the effects. If you add `effects.css` as a separate entry:
- The rules load two times.
- The build fails, because a separate entry does not import `tailwindcss`, so its `@apply` utilities are unknown.
- The browser makes one more CSS request for no benefit.

This is the same pattern as the Filament theme, which imports `app.css` and does not list it as a second Vite entry.

## Other files

- Update the comment in [resources/views/components/clipped-table.blade.php:75](resources/views/components/clipped-table.blade.php) only if it points to a moved rule. It points to `.clipped-table--striped`, which stays, so no change is expected.
- Search `resources/` and `docs/` for comments that say a moved class is "in app.css". Update each one to say `effects.css`.
- Add the plan to `docs/plans/20261011-css-effects-split.md` (phase 1 of the CSS split). Add an index entry to [docs/plans/README.md](docs/plans/README.md) with the date **2026-10-11** in bold.
- In [CLAUDE.md](CLAUDE.md) "Design", add one line: keyframe animations live in `resources/css/effects.css`, not `app.css`.

## Verification

1. Build check: run `npm run build` (or keep `composer run dev` running). Make sure that there are no Tailwind or Vite errors, for example an unknown `@apply` utility.
2. Compare the compiled CSS before and after the move. Build on the current tree first and save `public/build/assets/app-*.css`. Then build again after the move. Every selector and every `@keyframes` name must still exist. Only the order can change. (A sorted diff of the selectors is enough.)
3. Browser pane (`http://localhost:8000`), light mode and dark mode:
   - `/archive`: the reflection sweeps on both `card-square-reflection` cards, and the gray gradients move.
   - Home page: the hero "Welcome" rises in and flickers, the glitch text animates, the grid drifts, and the cursor blinks.
   - Sold items list: the skeleton loaders shimmer during loading.
   - Calendar: the current day has the marching-ants border.
   - Contact form: the submit sweep runs on hover.
4. Emulate `prefers-reduced-motion: reduce`. Make sure that all of the effects above stop, and that the hero text is still visible.
5. Filament admin (`/admin`): make sure that the panel styles load with no build error.
6. Run `vendor/bin/pest tests/Feature/ArchiveTest.php tests/Feature/CyberComponentsTest.php`. Run the full suite only if the user agrees, because the full suite empties the dev database. After the full suite, run `php artisan migrate:fresh --seed`.
7. Make no commit. Report the result and wait for the commit instruction from the user.
