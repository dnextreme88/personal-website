# Dropping Areas: transit route map

> **Outcome (executed 2026-10-29)**
> `/archive/static-dropping-areas` is now a transit route map. Each photo is a station on a rail that
> fills on scroll. The newest station (V7) has a magenta node with a radar beacon and a green CURRENT badge.
> Older stations are grey until hover and have a DECOMMISSIONED badge. Transfer markers show before
> V2·B (Olympian) and V4 (Mabini). Stations are 64 px apart on a phone and 88 px on desktop.
> Changes from the plan: the badges use `<x-badge>` (its own `font-subtext`, not `font-loader`), and
> the rail fills to the end when the page is scrolled to the bottom, because the last station can sit
> below the fill mark. Branch: `feature/20261029-dropping-areas-route`, stacked on PR #21.
> Verified: Pest (new `StaticDroppingAreasTest`), Pint, `npm run build`, and the browser pane at
> 1280 px and 375 px in light and dark mode (rail fill, lit nodes, beacon, modal, no console errors).

## Context

The `/archive/static-dropping-areas` page ([StaticDroppingAreas.php](../../app/Livewire/Archive/StaticDroppingAreas.php), [static-dropping-areas.blade.php](../../resources/views/livewire/archive/static-dropping-areas.blade.php)) shows 8 `.webp` photos in a plain 3-column card grid. A click opens the image preview modal.

The user reviewed a prototype (artifact "Dropping Areas Lab") and chose **concept A, the transit route map**, with more space between the stations than in the first prototype version. The Game Screenshots page is a gallery. This page must look different: it shows the moves between locations as a subway line.

Each photo is one **station** on the line. The data for each station comes from the filename, for example `DA V6 - Mabini Shopping Center Room 204 9 - 7 PM (aug 29 2025).webp`.

## Design

| Part | Behaviour |
| --- | --- |
| Rail | A vertical line. A cyan-to-magenta fill grows as the page scrolls down. On desktop (900 px and wider), the rail is in the centre and the cards alternate left and right. On a phone, the rail is on the left. |
| Station node | A ring on the rail. It lights up (cyan glow) when the fill reaches it. |
| Current station | The newest version. It has a filled magenta node with two radar pulses and a "LIVE" chip. Its card has a magenta border and glow. |
| Old stations | The photo is grey. It gets its colour back on hover. Each card has a "DECOMMISSIONED" chip. |
| Transfer marker | A dashed ring with "TRANSFER → {BUILDING}" between two stations in different buildings. |
| Card | Photo on the left, data on the right: version tag, building, room or stall, hours, date, note (for example "Original stall"). A missing value shows "—". |
| Click | A click on the photo opens the existing `<x-modal-image-preview>`. |
| Spacing | 64 px between stations on a phone, 88 px on desktop. |

Fonts: the version tag, the chips, and the spec labels use `font-loader`. The building name is a heading, so it uses `font-heading`. The lede uses `font-subtext`.

Dark mode and light mode: the page uses the same colour pairs as the Game Screenshots page. Light mode uses `cyan-700` / `fuchsia-600` with a soft glow. Dark mode uses `neon-cyan` / `neon-magenta` with the full glow.

Reduced motion: the rail shows fully filled, all nodes show lit, and the beacon pulses stop.

## 1. Livewire component: `app/Livewire/Archive/StaticDroppingAreas.php`

Bug to fix: `render()` appends to the public `$dropping_areas` array on every render, so a re-render duplicates the list. This is the same bug that the Game Screenshots page had.

- Remove the `public array $dropping_areas` property. Make `render()` build the list as a local value and pass it to the view.
- Add `public static function parseFilename(string $filename): array`. It returns:
  - `version`, for example `V6`
  - `building`, for example `Mabini Shopping Center`
  - `unit`, for example `Room 204` or `Stall 6`
  - `hours`, for example `9 - 7 PM`, or `null`
  - `date` in the format `Y-m-d`, or `null` (from `(aug 29 2025)`)
  - `note`, for example `Original stall`, or `null`
- Sort the list by version, then by filename. The last entry is the current station (`is_current`).
- Add `is_transfer` to each entry: `true` when its building is different from the building of the entry before it.
- Keep the `.webp`-only filter.

## 2. Alpine component: new `resources/js/dropping-areas-route.js`

`Alpine.data('droppingAreasRoute', () => ({ ... }))`, loaded with `@push('scripts') @vite([...])`. Add it to the `input` list in [vite.config.js](../../vite.config.js).

- `isImagePreviewModalOpen`, `imagePreviewSrc`, and `openPreview(url)` for the modal.
- `onScroll()` (on `x-on:scroll.window`, throttled with `requestAnimationFrame`):
  - It sets the rail fill height from the scroll position.
  - It adds the `reached` class to each station whose node is above the fill end.
- `reducedMotion`: when it is true, the fill is 100 % and every station is reached.

## 3. Blade view: `resources/views/livewire/archive/static-dropping-areas.blade.php`

Rewrite the card grid as the route. Keep `nav_menu`, the `header` slot, and the empty state. Use the lede "A list of Dropping Areas over the years".

```
<div x-data="droppingAreasRoute()" x-on:scroll.window="onScroll()">
  route (relative)
    rail + rail-fill (absolute)
    @foreach entries
      @if is_transfer and not first → transfer marker
      station (current / old)
        node
        card: photo button (opens modal) + data (version, chips, building, spec list)
    @endforeach
  <x-modal-image-preview data-img-src="imagePreviewSrc" />
</div>
```

The node, the chips, and the card frame are small, so they stay as Tailwind classes in the view. If a Blade component in `resources/views/components` fits (for example a chip), reuse it.

## 4. CSS

- **`resources/css/effects.css`**: the `route-beacon` keyframes and the class that uses them. Add the class to the reduced-motion block.
- **`resources/css/app.css`**: the transitions for the node glow, the photo greyscale, and the card hover. Add them to the reduced-motion block.

## 5. Tests: new `tests/Feature/StaticDroppingAreasTest.php`

These tests do not touch the database, so they do not use `RefreshDatabase`.

- The page returns 200 and contains `droppingAreasRoute`.
- `parseFilename()` gives the correct values for a dataset of the 8 real filenames (version, building, unit, hours, date, note).
- The component passes one entry for each `.webp` file. The count is the same after a second render. Only the last entry has `is_current`. The `is_transfer` flags are on V2 Olympian and V4 Mabini only.

## 6. Docs

- When the work is done, change the status banner of this file to an outcome banner.
- Add an index entry to [docs/plans/README.md](README.md) with **2026-10-29** in bold.

## Branch

The user chose a stacked branch: `feature/20261029-dropping-areas-route` from `feature/20261029-game-screenshots-filmstrip`, as a PR on top of PR #21. No commit and no push until the user says so.

## Verification

1. Run `vendor/bin/pest tests/Feature/StaticDroppingAreasTest.php tests/Feature/ArchiveTest.php`. After this, reseed the dev database with `php artisan migrate:fresh --seed`.
2. Run `vendor/bin/pint` on the changed PHP files.
3. Run `npm run build`. Make sure that the new JS entry is in the build output.
4. Open `http://localhost:8001/archive/static-dropping-areas` in the browser pane:
   - The rail fills on scroll, and the nodes light up.
   - V7 shows the LIVE chip and the beacon. The other stations are grey and get colour on hover.
   - The transfer markers show before V2 Olympian and V4 Mabini.
   - A click on a photo opens the modal.
   - Check light mode, dark mode, a phone width (375 px), and reduced motion.
