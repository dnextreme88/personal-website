# Sold Items "Name" sort: brand, then name, then type

> **Outcome (executed 2026-10-10)**
> The "Name" sort on `/archive/sold-items` now orders by brand, then name, then type. When an
> item has no name, it sorts by brand, then type.
> - **Livewire**: `ListSoldItem::render()` uses three sort keys when `sort_field` is `name`:
>   `brand`, `COALESCE(NULLIF(name, ''), type)`, and `type`. `sort_direction` is whitelisted to
>   `asc` / `desc` before it goes into `orderByRaw`.
> - **Tests**: one case added to `tests/Feature/ListSoldItemTest.php` (brand → name → type order,
>   null name falls back to type, asc and desc).
> - **Test fix**: the existing test "summarizes the whole collection regardless of the active
>   filter" failed on `master`. It called `search_archives`, but the method is
>   `search_sold_items`. The test now calls `search_sold_items`.
> - **View**: the list-view skeleton loader in `list-sold-item.blade.php` gets `h-20`, to match
>   the height of the list-view image.
> - **Verification**: `vendor/bin/pest tests/Feature/ListSoldItemTest.php` (all 15 pass),
>   `vendor/bin/pint`, `migrate:fresh --seed`, and a browser preview of the "Name" sort.

## Context

On the archive sold items page, the "Name" sort ordered rows by the `name` column only. The page
shows each item by its `item_name` label (`SoldItem::getItemNameAttribute()`, which is
`brand + name + type`). Thus the order did not match the label, and items from different brands
mixed together.

The required order when `sort_field` is `name`:

1. `brand`
2. `name`
3. `type`

When `name` is null, the order is `brand`, then `type`. Thus the second sort key is
`COALESCE(NULLIF(name, ''), type)`, and `type` is the tiebreaker. This matches the label text:
`Nike Air Shoe` and `Nike Shirt` (null name) sort as `Air` before `Shirt`.

## Changes

### `app/Livewire/Archive/ListSoldItem.php`

In `render()`, replace the single `orderBy` with a branch:

```php
$query = $this->build_query();

if ($this->sort_field === 'name') {
    // Match the item_name label: brand, then name, then type. A null name falls back to type.
    $direction = $this->sort_direction === 'asc' ? 'asc' : 'desc';

    $query->orderBy('brand', $direction)
        ->orderByRaw("COALESCE(NULLIF(name, ''), type) {$direction}")
        ->orderBy('type', $direction);
} else {
    $query->orderBy($this->sort_field, $this->sort_direction);
}

$sold_items = $query->paginate(18);
```

`sort_direction` is a public Livewire property, so a client can change it. The code whitelists it
to `asc` / `desc` before it goes into `orderByRaw`, to prevent SQL injection.

No change to `SORTABLE_FIELDS`, the Blade view, or the asc/desc toggle.

### `tests/Feature/ListSoldItemTest.php`

Add one Pest test after the price sort test. Use the existing `createSoldItem()` helper:

- Create items (brand / name / type):
  - `Nike / Zeta / Shirt`
  - `Adidas / Beta / Shoe`
  - `Nike / null / Cap`
  - `Nike / Alpha / Pants`
  - `Nike / Alpha / Jacket`
- Call `apply_sort('name')`. Assert `sort_direction` is `asc`.
- Assert the `item_name` order: `Adidas Beta Shoe`, `Nike Alpha Jacket`, `Nike Alpha Pants`,
  `Nike  Cap`, `Nike Zeta Shirt`. (The null name sorts by `Cap`.)
- Call `apply_sort('name')` again. Assert the reverse order for `desc`.

## Verification

1. Run `vendor/bin/pest tests/Feature/ListSoldItemTest.php`.
2. Run `vendor/bin/pint`.
3. The test file uses `RefreshDatabase`, so the run empties the dev MySQL DB. Run
   `php artisan migrate:fresh --seed` after the tests.
4. Open `/archive/sold-items`. Select the "Name" sort. Confirm that the brand groups are in order
   and that items with no name sort by type.
