# Steam Achievements table, model, seeder, Livewire page & Filament resource

> ## ✅ EXECUTED 2026-07-12 — outcome
>
> Shipped as planned, with a few refinements made during implementation:
>
> - **Naming is singular** (`ListSteamAchievement`, `list-steam-achievement.blade.php`,
>   `ListSteamAchievementTest`, and the card image `bg-list-steam-achievement.webp`) to match the
>   existing `ListSoldItem` / `bg-list-sold-item.webp` convention — not the plural names used in the
>   draft below.
> - **Public-page tags render as rounded badges** — each comma-separated tag becomes a
>   `rounded-full` pill inside a `flex flex-wrap gap-2` container (plus `m-0.5` per badge); games
>   with no tags show an italic "No tags" placeholder.
> - **Filament tag suggestions** mirror `SoldItemResource`: the `TagsInput` suggests existing tags
>   pulled from all records, and the admin table renders `tags` as badges.
>
> **Files added:**
> - `database/migrations/2026_07_12_000000_create_steam_achievements_table.php`
> - `app/Models/SteamAchievement.php`
> - `database/seeders/SteamAchievementSeeder.php` (80 rows, fully spelled out — one array per game)
> - `app/Livewire/Archive/ListSteamAchievement.php`
> - `resources/views/livewire/archive/list-steam-achievement.blade.php`
> - `app/Filament/Resources/SteamAchievementResource.php` + `Pages/{List,Create,Edit}SteamAchievement.php`
> - `tests/Feature/ListSteamAchievementTest.php`
>
> **Files changed:**
> - `routes/web.php` — added `archive.steam-achievements.list` route + import.
> - `database/seeders/DatabaseSeeder.php` — registered `SteamAchievementSeeder`.
> - `resources/views/livewire/archive/archive.blade.php` — added the Steam Achievements index card.
> - `public/images/archive/bg-list-steam-achievement.webp` — card background image.
>
> **Verified:** `vendor/bin/pest` green (2/2, run on isolated in-memory SQLite so the MySQL dev DB
> was untouched). Migration applied and 80 rows seeded. `route:list` shows all four routes (public
> + 3 Filament admin). Loaded `/archive/steam-achievements` (HTTP 200) and confirmed the table
> loops all 80 games in file order and multi-tag rows render two distinct rounded badges.
> `vendor/bin/pint` clean.

## Context

The owner tracks Steam games they've 100%-completed in a flat text file
(`steam-achievements-list.txt`, 80 entries grouped by year). This brings that data into the app as
a new `steam_achievements` table, exposed through the existing Archive section of the site
(alongside Sold Items, Game Screenshots, Dropping Areas), plus a Filament admin resource to
manage the records.

Decisions confirmed with the owner:
- `notes` column is **nullable**; records without a parenthetical note are `null`.
- `date_completed` is **NOT nullable**.
- Page is reachable at **`/archive/steam-achievements`** (route `archive.steam-achievements.list`),
  matching the existing `archive.sold-items.list` pattern.
- A **Filament admin resource** (create / edit / delete) modeled on `SoldItemResource` but simpler
  (no relationships).

## Schema

Migration `database/migrations/2026_07_12_000000_create_steam_achievements_table.php`
(mirror the style of [create_sold_items_table](database/migrations/2025_01_10_034330_create_sold_items_table.php)):

| Field            | Type                               |
|------------------|------------------------------------|
| `id`             | `$table->id()`                     |
| `game_name`      | `string('game_name', 255)`         |
| `tags`           | `text()->nullable()`               |
| `date_completed` | `date()` (NOT nullable)            |
| `notes`          | `string('notes', 255)->nullable()` |
| timestamps       | `$table->timestamps()`             |

## Model

`app/Models/SteamAchievement.php` — plain Eloquent model matching the minimal style of
[SoldItem](app/Models/SoldItem.php) (no relations needed):

```php
class SteamAchievement extends Model
{
    protected $fillable = ['game_name', 'tags', 'date_completed', 'notes'];
}
```

(Table name `steam_achievements` is inferred from the class name.)

## Seeder

`database/seeders/SteamAchievementSeeder.php` — a single `SteamAchievement::insert([...])` call
(same approach as [SoldItemSeeder](database/seeders/SoldItemSeeder.php)), one fully spelled-out
array per game so the owner can edit `date_completed`/`tags`/`notes` inline. Registered in
[DatabaseSeeder](database/seeders/DatabaseSeeder.php) after `SoldItemSeeder::class`.

Parsing rules applied to the source text file:
- **Game name** = the text after the `— N.` cardinal marker (year-only header lines are ignored).
- **Notes** = the parenthetical text, stripped of its outer `()`, for the 12 flagged entries
  (#4, 22, 26, 45, 47, 53, 59, 60, 64, 65, 70, 72). All other rows get `notes => null`.
- Order is preserved exactly: id 1 = Desolate … id 80 = Hitman Absolution.

## Livewire component + view

`app/Livewire/Archive/ListSteamAchievement.php` — minimal full-page component in the same
namespace/style as [Archive](app/Livewire/Archive/Archive.php), passing `SteamAchievement::all()`
to the view.

`resources/views/livewire/archive/list-steam-achievement.blade.php` — follows the outer shell used
by every archive view (`x-slot name="nav_menu"` + `x-navigation-menu`, `x-slot name="header"`,
`max-w-7xl` wrapper). A responsive table wrapped in `overflow-x-auto`, `@forelse` over the records
with columns **#, Game, Date Completed, Tags, Notes**. The Tags cell splits the comma-separated
string and renders each tag as a `rounded-full` badge (`flex flex-wrap gap-2`, `m-0.5` per badge);
empty tags/notes show an italic placeholder.

## Route

Added to the `archive` group in [routes/web.php](routes/web.php):

```php
Route::get('/steam-achievements', ListSteamAchievement::class)->name('steam-achievements.list');
```

## Filament admin resource

Modeled on [SoldItemResource](app/Filament/Resources/SoldItemResource.php) but far simpler (no
relations, so no `mutateFormDataBefore*` hooks). Filament auto-discovers resources
(`->discoverResources(...)` in
[AdminPanelProvider](app/Providers/Filament/AdminPanelProvider.php)), so no manual registration.

- **Resource** — `$model = SteamAchievement::class`; trophy nav icons. `form()` has one `Section`
  with `TextInput` (game_name), `DatePicker` (date_completed, required), `TagsInput` (tags),
  `Textarea` (notes). The `TagsInput` mirrors `SoldItemResource`'s tag handling — lowercase styling,
  helper text, and `->suggestions(...)` that offers the existing tags pulled from all records.
  `table()` has searchable columns; `date_completed` sorts; `tags` render as badges; `tags`/`notes`
  are toggleable-hidden. Edit action per row.
- **Pages** — `ListSteamAchievement` (Create header action), `CreateSteamAchievement`,
  `EditSteamAchievement` (Delete header action).

## Archive index card

A fourth `<section>` card added to [archive.blade.php](resources/views/livewire/archive/archive.blade.php), copying the existing card
markup (wrapper classes, gradient/overlay divs, `wire:navigate` anchor), linking to
`route('archive.steam-achievements.list')` with heading **"Steam Achievements"**. Uses the
background image `/images/archive/bg-list-steam-achievement.webp` (named singular to match
`bg-list-sold-item.webp`).

## Out of scope

- No nav-menu entry (only the Archive index card).
- No filtering/search/pagination on the public `/archive` table. The Filament admin table does
  include search per the SoldItem basis.
