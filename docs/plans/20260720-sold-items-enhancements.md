# Sold Items page — summary dashboard, sorting & hot-item badge

> ## ✅ EXECUTED 2026-07-20 — outcome
>
> Added a whole-collection **summary dashboard**, server-side **sorting**, and a **hot-item badge**
> to the public `/archive/sold-items` page. Final state after several rounds of owner feedback:
>
> - **"Sold Items Summary"** — a fixed overview placed found above the search/filter form and
>   **independent of the active filter** (computed once in `mount()`; a filtered search never changes
>   it). It's a **collapsible accordion** (Alpine, shown by default): a rotating chevron button
>   (up = shown, down = hidden) with a smooth `grid-rows-[1fr]↔[0fr]` + opacity height animation.
> - **Eight summary cards** via a new reusable `<x-stat-card>` component — headline values: **total
>   items sold**, **total transactions** (distinct `transaction_id`), **total number of years**
>   (`maxYear − minYear`, e.g. 2026 − 2014 = 12), **unique brands**; and ranked "name (count)" lists:
>   **top 3 brands**, **most common types**, **top 3 sell-method locations**
>   (`sell_methods.location`), **top 3 payment-method locations** (`pay_methods.remittance_location`).
> - **Eight charts** (Chart.js, new dependency, **light-blue** bars) under the cards: **total sold
>   items per year**, **total sold items per month** (per calendar month across every year),
>   **average price per year**, **total price per year**, **top selling brand each year** (bar = that
>   year's top brand's count), **top selling item each year** (bar = that year's highest price),
>   **total transactions per year** and **total transactions per month** (distinct `transaction_id`,
>   since one id groups items bought together into a single transaction). The top-brand/top-item
>   charts use plain-year x-axis labels and carry per-bar `meta` names surfaced in tooltips; price
>   charts format as `₱`. Charts are static (never change with filters), each drawn once from JSON
>   embedded on its `<canvas>` inside a `wire:ignore` container so Livewire re-renders don't clobber
>   them. Vanilla JS (not Alpine — the heavy `chart.js` import can execute after Alpine walks the
>   DOM); a `whenSized` guard defers drawing until the canvas has real size so Chart.js never caches a
>   0×0 canvas.
> - **Sorting is server-side** (Date / Price / Name, asc↔desc toggle) via `apply_sort()` + an
>   `orderBy` on the shared query — the Steam page's client-side sort would only reorder the current
>   paginated page. Default is `date_sold desc` (newest first).
> - **Hot-item 🔥 badge** shows next to the item name (grid card + list row) when an item's `tags`
>   contain `hot item`. Static emoji. Tag data is added manually in the seeder by the owner.
>
> **Files changed:**
> - `app/Livewire/Archive/ListSoldItem.php` — unified filter query, sort, and the whole `build_summary()`
> - `resources/views/livewire/archive/list-sold-item.blade.php` — accordion summary (cards + charts), sort control, 🔥 badge
> - `resources/views/components/stat-card.blade.php` *(new)*
> - `resources/js/sold-items-chart.js` *(new)* + registered in `vite.config.js`
> - `package.json` — added `chart.js`
> - `tests/Feature/ListSoldItemTest.php` *(new)* — 11 Pest tests
>
> **Verified:** `vendor/bin/pest` (full suite green), `vendor/bin/pint`, `npm run build`, and manual
> end-to-end on the running app — cards populate from 300 seeded items (255 transactions, 12 years),
> all 8 charts draw, the accordion animates with the chevron, sort round-trips, and the transaction
> chart matches the seed (`2025 → 20`). No console errors.

---

## Context

The public **Sold Items** archive is the most feature-rich archive page (filters, grid/list toggle,
lazy images, pagination over ~300 items) but surfaced **no aggregate insight**, offered **no
sorting**, and had a **stubbed "hot item" TODO**. This work adds a whole-collection summary
dashboard, server-side sorting, and the hot-item badge.

## Features

1. **Sold Items Summary** — a fixed, filter-independent accordion above the search form: eight cards
   (total items sold, total transactions, total number of years, unique brands, top 3 brands, most
   common types, top 3 sell-method locations, top 3 payment-method locations) plus eight charts.
   Computed once in `mount()`; rendered via the new `<x-stat-card>` component.
2. **Eight charts** (Chart.js, light-blue) — total sold items per year, total sold items per month,
   average price per year, total price per year, top selling brand each year, top selling item each
   year, total transactions per year, total transactions per month.
3. **Sort control** — server-side sort by date / price / name with a direction toggle.
4. **Hot-item 🔥 badge** — tag-driven (`tags` contains `hot item`), next to the item name in both
   the grid and list views.

Out of scope (future pass): enum-backing `condition`/`size`, DB indexes on filtered columns,
`price` → decimal.

## Verification

- `vendor/bin/pest tests/Feature/ListSoldItemTest.php` — summary is global (ignores filters),
  headline totals (transactions, years) and rankings compute correctly, per-year/per-month and
  transaction chart series are correct, sorting toggles direction, and the 🔥 badge shows only for
  tagged items.
- Manual: load `/archive/sold-items`, collapse/expand the summary accordion, apply/clear filters
  (results change, summary does not), toggle sort across pages, and tag an item `hot item` in the
  Filament admin to see 🔥.
