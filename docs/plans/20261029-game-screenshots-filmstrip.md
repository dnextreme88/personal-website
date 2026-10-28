# Game Screenshots: futuristic filmstrip gallery

> **Outcome (executed 2026-10-29)**
> `/archive/static-game-screenshots` is now a filmstrip gallery: a featured frame with a glitch swap,
> a decoding HUD readout (index, game, and the date read from the filename), and an ambient glow,
> over a thumbnail strip with a neon active thumbnail, a hover preview, and a progress rail.
> Keyboard: Left/Right, Home/End. A click on the image still opens `<x-modal-image-preview>`.
> - **Bug fixed**: `render()` no longer appends to a public property, so re-renders do not duplicate
>   the list. `dateFromFilename()` is a new public static helper.
> - **Fixes found while verifying**: the key handler now accepts non-element event targets; the
>   gallery grid uses `grid-cols-1` (`minmax(0, 1fr)`) so the strip scrolls inside its own box
>   instead of widening the page; the strip scrollbar is thin and cyan in both modes.
> - **Verified**: `StaticGameScreenshotsTest` and `ArchiveTest` pass (8 tests). In the browser:
>   glitch, decode, wrap-around, `NO DATE`, centred active thumbnail, rail, ambient glow, modal
>   blocks the arrow keys, light and dark mode at phone width. Reduced motion was checked in code and
>   in the shipped CSS, not with an emulated setting.

## Context

The `/archive/static-game-screenshots` page ([StaticGameScreenshots.php](app/Livewire/Archive/StaticGameScreenshots.php), [static-game-screenshots.blade.php](resources/views/livewire/archive/static-game-screenshots.blade.php)) shows 24 `.webp` screenshots as a plain 3-column card grid. A click opens a basic preview modal.

The user reviewed a working prototype (artifact "Screenshot Filmstrip Lab") and chose the **filmstrip** layout with these effects:

| Group | Chosen effects |
| --- | --- |
| 1. Image change | Glitch swap |
| 2. HUD overlay | Data readout, decode numbers |
| 3. Filmstrip | Neon active thumbnail, hover preview, progress rail |
| 4. Controls | Keyboard (← → Home End) |
| 5. Extras | Ambient glow |

The following items are **not** part of this change: the boot-up loader, the corner brackets, the scanlines on the frame, drag/swipe, slideshow, filter tabs, the share link, and the 3D tilt.

Other decisions from the user:
- The readout uses `font-loader` (JetBrains Mono), so the digits line up.
- A click on the featured image still opens the existing `<x-modal-image-preview>`.
- The work goes on a new branch that starts from `refactor/20261011-css-effects`. Merge both branches as stacked pull requests.

## Branch and pull requests

1. Start on `refactor/20261011-css-effects`. It is clean and pushed. Create `feature/20261029-game-screenshots-filmstrip` from it.
2. **Commit directly when the work is done and verified.** The user approved this for this plan. Push the branch.
3. Then open both PRs:
   - PR A: `refactor/20261011-css-effects` → `master`.
   - PR B: `feature/20261029-game-screenshots-filmstrip` → `refactor/20261011-css-effects`. This is the stacked PR. When PR A merges, GitHub retargets PR B to `master`.
   - Assign `dnextreme88` to both PRs.

## 1. Livewire component — `app/Livewire/Archive/StaticGameScreenshots.php`

Bug to fix: at the moment, `render()` appends to the public `$screenshots` array on every render, so a re-render duplicates the list. The fix is to build the list as a local value in each render.

- Remove the `public array $screenshots` property. Make `render()` build the list and pass it to the view.
- Each entry has these keys:
  - `filename`, for example `Crossfire20241115_0000.webp`
  - `url`, the same as now
  - `game`, the folder slug, for example `crossfire-ph`
  - `game_label`, the slug in upper case, for example `CROSSFIRE-PH`
  - `date` in the format `Y-m-d`, or `null`
- Add `public static function dateFromFilename(string $filename): ?string`. It finds the first `20YYMMDD` match and validates it with `checkdate`:
  - `Crossfire20241115_0000.webp` → `2024-11-15`
  - `20220117-pentakill.webp` → `2022-01-17`
  - `riot-lol-1st-tft-game.webp` → `null`
- Sort the list by `game`, then by `filename`, so the order is the same every time.
- Keep the `.webp`-only filter.

## 2. Alpine component — new `resources/js/game-screenshots-gallery.js`

The file follows the pattern of [skeleton-loading.js](resources/js/skeleton-loading.js): `Alpine.data('gameScreenshotsGallery', (screenshots) => ({ ... }))`. The view loads the file with `@push('scripts') @vite([...]) @endpush`. Add the file to the `input` list in [vite.config.js](vite.config.js), the same as `skeleton-loading.js` and `sold-items-chart.js`. A file that loads separately with `@vite` must be a Vite entry, unlike the CSS `@import`.

State: `screenshots`, `index`, `previousUrl`, `isGlitching`, `readoutLeft`, `readoutRight`, `isFrameLoaded`.

Methods:
- `show(i, direction)`:
  - Wrap the index at both ends.
  - Keep the old URL.
  - Restart the glitch: remove `is-glitching`, force a reflow, add `is-glitching` again, and remove it after 340 ms.
  - Update the readout with `decode()`.
  - Centre the active thumbnail with `scrollTo` on the strip. Do not use `scrollIntoView`, because it also scrolls the page vertically.
- `next()` / `prev()`.
- `onKey(e)` on `x-on:keydown.window`. It does nothing in these cases:
  - The preview modal is open.
  - The focus is in an input field.

  The keys: ArrowLeft and ArrowRight go to the previous and next image. Home and End go to the first and last image, with `preventDefault`.
- `decode(text)`: scrambles the readout for about 300 ms, then shows the real text. Spaces and `/ - _` stay fixed. Copy this function from the prototype.
- `reducedMotion`, from `matchMedia('(prefers-reduced-motion: reduce)')`. When it is true, `show()` makes no glitch and `decode()` sets the text at once.
- Readout text: `IMG_03 / 24 // CROSSFIRE-PH` on the left, and the date or `NO DATE` on the right.

## 3. Blade view — `resources/views/livewire/archive/static-game-screenshots.blade.php`

Rewrite the grid as a filmstrip. Keep `nav_menu`, the `header` slot, and the empty state ("There are no screenshots.").

```
<div x-data="{ isImagePreviewModalOpen: false, imagePreviewSrc: '', ...gameScreenshotsGallery(@js($screenshots)) }">
  viewport (relative, isolate)
    ambient   : absolute, -z-10, bg-image = current url, blur-3xl saturate-150, opacity-40 / dark:opacity-60, transition-opacity
    frame     : screenshot-frame, aspect-video, overflow-clip, bg-gray-950 (dark in both modes), border cyan-800 / dark:neon-cyan,
                shadow glow; x-bind:class="{ 'is-glitching': isGlitching }"
      img.cur   : object-contain, x-on:click opens the modal, x-on:load sets isFrameLoaded
      ghost x2  : cyan + magenta glitch layers (bg-image = current url)
      skeleton  : the existing skeleton-loader-lg until the first image load
      prev/next : buttons, font-loader, aria-label "Previous screenshot" / "Next screenshot"
      readout   : bottom bar, font-loader, tabular-nums, x-text readoutLeft / readoutRight
  filename caption : font-subtext, muted, wraps (no truncation)
  filmstrip   : role="list"; buttons per thumbnail (loading="lazy", object-cover, 2-digit index), aria-current on the active one
  progress rail : track + fill, width = (index+1)/count
  keyboard hint : "← → browse · Home / End jump", font-subtext, small
  <x-modal-image-preview data-img-src="imagePreviewSrc" />
```

- Remove the `skeleton-loading.js` push. Each thumbnail uses `loading="lazy"`. The frame shows the skeleton only until its first image loads.
- Use `alt` text from the filename. Give the thumbnail buttons a clear `aria-label`, for example "Show screenshot 3: Crossfire20241115_0000.webp".
- Dark mode: the frame and the thumbnail backgrounds stay dark in both modes, because they hold screenshots. The borders, the rail, the active glow, the hint text, and the caption use the light and dark pairs that the site already uses. Light mode: `cyan-800`, `gray-500`, with a soft glow. Dark mode: `neon-cyan`, `gray-400`, with the full glow. This is the same approach as `.cyber-link`.

## 4. CSS

**`resources/css/effects.css`** (keyframes only, per CLAUDE.md):
- `.screenshot-frame.is-glitching .screenshot-frame-image` → `@keyframes screenshot-glitch`. These are the clip-path slices and the small horizontal offsets.
- `.screenshot-frame.is-glitching .screenshot-ghost-cyan` / `-magenta` → `@keyframes screenshot-ghost-cyan` / `screenshot-ghost-magenta`.
- Ghost layer base styles: absolute position, `mix-blend-mode: screen`, the sepia/hue-rotate tint, and `opacity: 0`.
- Reduced-motion block: add `.screenshot-frame .screenshot-frame-image` and both ghost classes to the `animation: none !important` list. Also hide the ghosts.

**`resources/css/app.css`** (transitions and static styles):
- `.filmstrip-thumb`:
  - Hover preview: the thumbnails dim when the strip is hovered, and the hovered thumbnail lifts and scales to 1.06.
  - Neon active state `[aria-current="true"]`: a cyan ring and glow. The light-mode glow is softer, and the dark-mode glow uses `.dark` selectors.
- Add `.filmstrip-thumb` to the reduced-motion block for transitions.

## 5. Tests — new `tests/Feature/StaticGameScreenshotsTest.php`

These tests do not touch the database, so they do not use `RefreshDatabase`.
- The page returns 200 and contains `gameScreenshotsGallery`.
- `dateFromFilename()` returns the 3 values listed in section 1, and also `null` for an impossible date such as `20241345`.
- The component passes one entry for each `.webp` file in `public/images/game-screenshots`. The count is the same after a second render, so there are no duplicates. Each entry has the keys `game`, `game_label`, `date`, `url`, and `filename`.

## 6. Docs

- Add `docs/plans/20261029-game-screenshots-filmstrip.md`. Put this plan in it, with an outcome banner.
- Add an index entry to `docs/plans/README.md` with **2026-10-29** in bold.

## Verification

1. Run `vendor/bin/pest tests/Feature/StaticGameScreenshotsTest.php tests/Feature/ArchiveTest.php`.
2. Run `vendor/bin/pint` on the changed PHP files.
3. Run `npm run build`. Make sure there are no errors, and that the new JS entry is in the build output.
4. Start MySQL if it is down. Open `http://localhost:8000/archive/static-game-screenshots` in the browser pane.
   - The glitch swap runs when you use ‹ ›, a thumbnail click, ArrowLeft and ArrowRight, and Home and End.
   - The readout decodes, and it shows the correct date (`NO DATE` for `riot-lol-1st-tft-game.webp`).
   - The active thumbnail glows and stays in the centre of the strip. The hover dims the other thumbnails. The rail fills.
   - The ambient glow changes with the image.
   - A click on the image opens the modal. While the modal is open, the arrow keys do nothing. Escape closes the modal.
   - Check light mode and dark mode, and a phone width (375 px).
   - Emulate reduced motion: there is no glitch and no decode, and the image still changes.
5. Run `vendor/bin/pint` and commit (subject in lowercase, 100 characters or fewer). Push the branch. Then open PR A and PR B as described in "Branch and pull requests", with `--assignee dnextreme88`.
