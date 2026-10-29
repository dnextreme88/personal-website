# Dropping Areas: database table and Filament resource

> **Outcome (executed 2026-10-29)**
> The transit route page now reads a `dropping_areas` table, not the image filenames. A new Filament
> resource (Admin → Dropping Areas) adds and edits rows, sorted newest first. `DroppingAreaSeeder` copies
> the 8 photos from `public/images/dropping-areas` to the `public` disk under `dropping-areas/` and makes
> the 8 rows, with the owner's addresses for each building.
> The opening and closing hours are free text, and each row has its own `is_current` flag
> (Room 204 and Room 203 are both current).
> `parseFilename()` and its tests are removed. The `DroppingAreas` enum (sold items) is unchanged.
> Branch: `feature/20261029-dropping-areas-table`, stacked on PR #23.
> Verified: Pint, `migrate:fresh --seed`, and the browser pane (8 stations, V1–V8, V7 and V8 current,
> photos served from `/storage`, no console errors).
>
> **Known issue (open):** the image upload in Filament is broken. The owner commented out the
> `FileUpload` field in the form and the `ImageColumn` in the table (marked `TODO: TO FIX LOGIC`).
> Until the upload is fixed:
> - Photos come from the seeder only. The seeded rows and their photos stay as they are.
> - The admin panel still creates, edits and deletes rows, but it does not show or set `image_path`.
>   An edit keeps the existing photo. A row created in the admin panel has no photo.
> - `image_path` is nullable. A station without a photo shows its text only, at full width, with no preview.
> - The test "creates a dropping area with an uploaded image from the admin panel" is commented out
>   (same `TODO`). Tests cover a create without an image and an edit that keeps the existing image.
>
> **Follow-up (to fix later):** the `FileUpload` field, the `ImageColumn`, and the upload test stay
> commented out until the image upload logic is fixed. When it is fixed:
> 1. Uncomment the `FileUpload` field and the `ImageColumn` in `DroppingAreaResource` (`TODO: TO FIX LOGIC`).
> 2. Uncomment the upload test in `StaticDroppingAreasTest`.
> 3. Confirm that the admin panel shows the images. First check `APP_URL` (see the likely cause above).
> 4. Decide whether `image_path` goes back to required.
> - Likely cause of the broken image path in the admin panel: Filament builds the URL from the `public`
>   disk, which uses `APP_URL` (`http://localhost`, no port). The dev server runs on a port, so the
>   admin URL points to the wrong host. The public page uses `asset()`, which takes the request host,
>   so its photos load. Not yet confirmed.

## Context

The transit route page (PR #23) read all of its data from the photo filenames, for example
`DA V6 - Mabini Shopping Center Room 204 9 - 7 PM (aug 29 2025).webp`. To add a location, the owner
had to rename a file. The owner wants a table, filled from the admin panel.

Decisions from the owner:
- Photos are uploaded through Filament.
- The `DroppingAreas` enum stays separate. Sold items do not point to the new table.
- No `version`, `version_suffix`, or `ended_on` field. The page numbers the stations by position.
- `is_current` is set for each row, because more than one dropping area can be active at a time
  (Room 204 and Room 203).
- `address` holds the building. The owner filled in the seeder addresses from the photos.
- A separate `note` field holds anything peculiar or interesting about a location.
- `unit` stays as it is, for example `Room 211 Stall 6`.

## Table: `dropping_areas`

| Field | Type | Use on the page |
| --- | --- | --- |
| `id` | bigint, primary key | Order of undated rows |
| `address` | string, nullable | Card heading; transfer marker when it changes |
| `unit` | string(128) | Card heading |
| `note` | string, nullable | "Note" row |
| `opens_at` / `closes_at` | string, nullable | "Opens" and "Closes" rows, as written, eg. "Mondays - Saturdays 9:00 AM; Sundays 10:00 AM" |
| `started_on` | date, nullable | "Date" row and the order |
| `is_current` | boolean, default false | "Current" badge; off shows "Decommissioned" |
| `image_path` | string, nullable | Photo, on the `public` disk; no photo area when empty |
| `created_at` / `updated_at` | timestamps | |

## Changes

1. **Migration** `2026_10_29_000000_create_dropping_areas_table.php`.
2. **Model** `App\Models\DroppingArea`:
   - `routeOrder()` scope: undated rows first (by `id`), then by `started_on`, then by `id`.
   - `opens_at` and `closes_at` are free text, because the hours change by day of the week.
3. **Filament** `DroppingAreaResource` (list, create, edit; no image field for now, see the known issue):
   - Form: text inputs for `opens_at` and `closes_at`, a "Current" toggle, and a `FileUpload` to `public/dropping-areas`
     (commented out for now, see the known issue above).
   - Table: image (commented out for now), address, unit, opens, closes, start date, a "Current" boolean icon,
     sorted by `started_on` descending (newest first) by default.
4. **Seeder** `DroppingAreaSeeder`, called from `DatabaseSeeder`. Each row has its building address.
   Room 204 and Room 203 have `is_current` set.
5. **Livewire** `StaticDroppingAreas` builds the stations from the table:
   - `tag` is `V1` … `Vn` by position.
   - `is_current` comes from the row's own flag.
   - `is_transfer` is set only when both addresses are known and different. An empty address is not a transfer.
6. **View**: heading is "{address} · {unit}", or the unit only when the address is empty.
   The transfer marker shows the address. The "Hours" row is now an "Opens" row and a "Closes" row.
7. **Tests** `StaticDroppingAreasTest` (with `RefreshDatabase`): page and empty state, hours text,
   route order and tags, more than one current station, transfer rule, Filament list, newest-first admin order,
   and Filament create with a faked upload (commented out while the upload is commented out).
   After the tests, reseed the dev DB with `php artisan migrate:fresh --seed`.
