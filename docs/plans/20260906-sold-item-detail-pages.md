# Sold Item detail subpages + `description` field

> **Outcome (executed 2026-09-06)**
> Added a detail subpage per sold item at `/archive/sold-items/{id}-{brand}-{type}`, plus a new
> nullable `description` text column.
> - **Migration**: `2026_09_06_000000_add_description_to_sold_items_table.php` adds
>   `text('description')->nullable()->after('size')`.
> - **Model**: `description` added to `SoldItem::$fillable` after `size`.
> - **Filament**: a `description` `Textarea` added after the `size` field.
> - **Seeder**: `'description' => null,` added after every `'size'` line (305 items).
> - **Route**: `archive.sold-items.detail`, with `{id}` constrained to digits.
> - **Livewire**: `App\Livewire\Archive\DetailSoldItem` resolves the item by `id` (404 if missing).
> - **View**: `resources/views/livewire/archive/detail-sold-item.blade.php`, single column, with a
>   description block and a "Back to Sold Items" link.
> - **List links**: image and item name link to the detail page in the table and list views, using
>   `str($brand)->slug()` / `str($type)->slug()`.
> - **Tests**: three cases added to `tests/Feature/ListSoldItemTest.php` (renders detail, 404 on
>   missing id, shows description).
> - **Not done**: previous/next item navigation (out of scope; first-pass layout only).
> - **Verification**: `migrate:fresh --seed`, `vendor/bin/pest`, `vendor/bin/pint`, and a browser
>   preview of the list → detail flow in light and dark mode.

## Context

The sold items archive at `/archive/sold-items` showed every item in a table view and a list view
only. No item had its own page. This change adds a detail subpage per item so a single item can be
linked and viewed on its own. It also adds a `description` field so an item can hold a longer text.

The detail page is a first pass. The layout is a simple single column; the owner will review it and
request adjustments afterwards.

## Decisions

- **URL**: the link is built from `str($brand)->slug()` and `str($type)->slug()`. `{id}` is
  constrained to digits so the three-part segment always parses. The item is resolved by `id` only;
  a missing id returns 404. `brand`/`type` in the URL are cosmetic.
- **List links**: both the image and the item name link to the detail page, in the table view and
  the list view.
- **Detail page scope**: item fields (single column) + the `description` block + the item image +
  a "Back to Sold Items" link. No previous/next navigation.

## Files

- `database/migrations/2026_09_06_000000_add_description_to_sold_items_table.php` (new)
- `app/Models/SoldItem.php`
- `app/Filament/Resources/SoldItemResource.php`
- `database/seeders/SoldItemSeeder.php`
- `routes/web.php`
- `app/Livewire/Archive/DetailSoldItem.php` (new)
- `resources/views/livewire/archive/detail-sold-item.blade.php` (new)
- `resources/views/livewire/archive/list-sold-item.blade.php`
- `tests/Feature/ListSoldItemTest.php`
