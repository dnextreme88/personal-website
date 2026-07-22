# Blog enhancements — draft/publish, related posts, reading time & prev/next nav

> ## ✅ EXECUTED 2026-07-22 — outcome
>
> Built on branch `feature/20260722-blog-enhancements` (verified end-to-end; **not yet merged**).
> Four features plus admin polish were added to the blog:
>
> - **Draft/Publish status (+ scheduling)** — new `is_published` column; a `Post::published()` scope
>   (`is_published && date_published <= today`) applied at every public read (`ListPost`,
>   `LatestPosts`, `ListCategory` counts, `Calendar` dots). `DetailPost` now `firstOrFail()`s → a
>   **404** for hidden/missing posts (fixing an old no-match crash).
> - **Curated related posts** — a bidirectional JSON pivot (`post_relations` + `PostRelation` model
>   + `Post::syncRelatedPosts()`), managed via a multi-select on the Post form; rendered on the
>   detail page as an indented, `→`-marked list sorted by title (published-only).
> - **Reading time** — a `reading_time` accessor (~200 wpm) shown as a badge on the detail page.
> - **Previous / Next navigation** — chronological neighbours by `date_published` then `id`,
>   published-only, hidden at the ends; styled as bordered, rounded cards (uppercase PREVIOUS/NEXT
>   label above the post title) in a two-column grid that stacks on mobile.
> - **Admin polish** — Published/Draft **tabs** on the Posts list (Published default, replacing a
>   ternary filter); a **"View in Site"** edit-page action visible only when the post is actually
>   viewable; `title` switched to `unique(ignoreRecord: true)` so edits can save.
>
> **Added:** `database/migrations/2026_07_22_000000_add_is_published_to_posts_table.php`,
> `database/migrations/2026_07_22_000001_create_post_relations_table.php`,
> `app/Models/Blog/PostRelation.php`, `tests/Feature/Blog/BlogEnhancementsTest.php`.
> **Modified:** `app/Models/Blog/Post.php`; `app/Livewire/Blog/{DetailPost,ListPost,LatestPosts,ListCategory}.php`;
> `app/Livewire/Calendar.php`; `app/Filament/Resources/PostResource.php` +
> `Pages/{CreatePost,EditPost,ListPosts}.php`; `resources/views/livewire/blog/detail-post.blade.php`;
> `database/seeders/PostSeeder.php` (seeds curated relations — see Seeder note).
> **Out of scope** this round: SEO/OG meta, tags, cover images, RSS, comments, share buttons.
> **Verified:** migrations apply clean; `vendor/bin/pest` 30 passed (15 new); browser checks of the
> 404, badge, prev/next, sorted `→` related list, admin tabs, and gated "View in Site"; Pint clean.
> **Note:** the suite wipes the dev DB (no `.env.testing`) — reseed with `migrate:fresh --seed` after.

## Context

The blog (TALL stack + Filament) already lists posts, supports search, single-category filtering, a
calendar date filter, and Markdown bodies. Gaps addressed this round:

1. **No editorial control** — every post was public the moment it was saved. Only a `date_published`
   *date* existed, with no publish flag, so a future-dated or WIP post showed immediately, and
   `DetailPost` had no 404 handling.
2. **Dead ends on the detail page** — after reading a post there was nothing to click through to, and
   no way to page to the chronologically adjacent post.
3. **No reading cues** — no reading-time estimate.

## Scope decisions (confirmed with user)

- In: Draft/Publish status, Related posts (curated), Reading time, Prev/Next post navigation.
- Out (this round): SEO/OG meta, tags, cover images, RSS, comments, social share buttons.

---

## 1. Draft / Publish status (+ scheduled publishing)

A post is **publicly visible** only when `is_published == true` **and** `date_published <= today`
(a future-dated published post is "scheduled" and stays hidden until its date).

### Schema
`database/migrations/2026_07_22_000000_add_is_published_to_posts_table.php`:
- `boolean('is_published')->default(true)->after('date_published')`.
- Default `true` so existing seeded/live posts stay published after migrating.

### Model — `app/Models/Blog/Post.php`
- `'is_published'` added to `$fillable`.
- Cast **only** `is_published => 'boolean'`. **Note:** `date_published` is intentionally *not* cast
  to `date` — the calendar (`calendar.blade.php`) compares a `Y-m-d` string against plucked values
  via `in_array`, and views call `Carbon::parse($post->date_published)`; a Carbon cast breaks both.
  The scope's `whereDate(...)` and the prev/next queries work fine against the raw date column.
- `published()` query scope (reused everywhere public posts are read):
  ```php
  public function scopePublished(Builder $query): Builder
  {
      return $query->where('is_published', true)
          ->whereDate('date_published', '<=', now());
  }
  ```

### `published()` scope applied at every public read
- `app/Livewire/Blog/ListPost.php` — `Post::published()` instead of `Post::query()`.
- `app/Livewire/Blog/DetailPost.php` — `Post::published()->where('id',…)->where('slug',…)->firstOrFail()`.
  `firstOrFail()` renders a **404** for hidden/missing posts (also fixes the old no-match crash, and
  is required because `public Post $post` is non-nullable).
- `app/Livewire/Blog/LatestPosts.php` — `->published()` before `latest('date_published')`.
- `app/Livewire/Blog/ListCategory.php` — `withCount(['posts' => fn ($q) => $q->published()])`.
- `app/Livewire/Calendar.php` — `unique_dates` pulls from `Post::published()` so the calendar hides
  dots for draft/future posts.

### Filament admin — `app/Filament/Resources/PostResource.php`
- Form: `Toggle::make('is_published')->label('Published')->default(true)` with helper text, placed
  after `date_published` (which also carries helper text about scheduling).
- Table: `IconColumn::make('is_published')->boolean()->label('Published')`; `date_published` column
  made `->sortable()`.
- **Published/Draft tabs** (not a filter — see §5) replace the earlier ternary filter idea.
- **Fix:** the `title` field is now `->unique(ignoreRecord: true)`. Without `ignoreRecord`, editing
  any post (e.g. just to set related posts) fails validation against its own title. Required for the
  edit form to save.

---

## 2. Related posts (manually curated, JSON pivot)

Related posts are **hand-picked in the admin**, kept **bidirectional** (assigning Post 3 to Post 1
also lists Post 1 under Post 3). Stored in a dedicated table, one row per post holding a JSON array.

### Schema — `post_relations` (`2026_07_22_000001_create_post_relations_table.php`)
`id`, `post_id` (unsigned, **unique**, FK → posts, `cascadeOnDelete`), `related_post_ids` (json,
nullable), timestamps.

### Model — `app/Models/Blog/PostRelation.php`
`$fillable = ['post_id', 'related_post_ids']`, `$casts = ['related_post_ids' => 'array']`,
`post(): BelongsTo`.

### `app/Models/Blog/Post.php` additions
- `relation(): HasOne` → `hasOne(PostRelation::class)`.
- Accessor `getRelatedPostIdsAttribute(): array` → `$this->relation?->related_post_ids ?? []`.
- `syncRelatedPosts(array $ids): void`:
  - Normalize: cast to int, drop self, `unique()`; upsert own row via `PostRelation::updateOrCreate`.
  - Diff vs previous set: each **added** id gets `$this->id` pushed into its row (`firstOrNew` +
    `unique`); each **removed** id gets `$this->id` pulled from its row. Unsets the cached relation.
- `booted()` `static::deleting`: strips this post's id from every other `post_relations` row (its own
  row cascades via the FK), so no dangling references remain.

### Filament admin — form + page lifecycle
Form: `Select::make('related_post_ids')->label('Related Posts')->multiple()->searchable()` with a
helper text/placeholder, options = other posts by title (`whereKeyNot` current, `orderBy('title')`).
Because `related_post_ids` is **not** a `posts` column, it's wired through the page lifecycle instead
of being mass-assigned (`CreatePost` / `EditPost`):
- Edit `mutateFormDataBeforeFill()` → seed `$data['related_post_ids'] = $this->record->related_post_ids`.
- `mutateFormDataBeforeCreate()` / `mutateFormDataBeforeSave()` → stash the value, `unset()` from `$data`.
- `afterCreate()` / `afterSave()` → `$this->record->syncRelatedPosts($stashed)`.

### Detail page display (`detail-post.blade.php`)
- `DetailPost.php`: `$related = Post::published()->whereIn('id', $this->post->related_post_ids)
  ->orderBy('title')->get();` — published-only (never links to a hidden/draft post) and **sorted by
  title**.
- View: a **Related Posts** section (shown only `@if ($related->isNotEmpty())`) as an indented list
  (`space-y-2 pl-4`, no disc bullets); each item is a flex row with a blue `&rarr;` marker
  (`aria-hidden`) before a `wire:navigate` link — readable in dark mode, unlike the default bullet.

---

## 3. Reading time

`app/Models/Blog/Post.php` accessor:
```php
public function getReadingTimeAttribute(): int
{
    $words = str_word_count(strip_tags($this->description));

    return max(1, (int) ceil($words / 200)); // ~200 wpm
}
```
Rendered on `detail-post.blade.php` as a `{{ $post->reading_time }} min read` badge next to the
category badge, reusing the existing badge classes.

---

## 4. Previous / Next post navigation

Detail-page **Previous** / **Next** links to the chronologically adjacent **published** post,
ordered by `date_published` then `id` (matching the list's `date_published DESC, id DESC`).

- **Previous** = immediately **older** (earlier date, or same date + smaller `id`).
- **Next** = immediately **newer** (later date, or same date + larger `id`).
- Newest post → no "Next"; oldest → no "Previous".

`DetailPost.php` computes `$previous` and `$next` with two guarded `where(... orWhere(tie))` queries
(each `->first()`, may be `null`) and passes them to the view. In `detail-post.blade.php` they render
as a two-column grid (`sm:grid-cols-2`, stacking on mobile) of **bordered, rounded cards**: each card
is the full clickable link (`wire:navigate`) with a small uppercase `← PREVIOUS` / `NEXT →` label
above the post title, hover border/background states, and dark-mode variants. `sm:col-start-1/2`
pins Previous to the left column and Next to the right even when only one neighbour exists.
"Back to Blog" sits just below.

---

## 5. Admin polish (added during the build)

- **Published/Draft tabs** — `ListPosts::getTabs()` returns `Tab::make('Published Posts')` (default,
  `where('is_published', true)`) and `Tab::make('Draft Posts')` (`where('is_published', false)`),
  using `Filament\Schemas\Components\Tabs\Tab` + `modifyQueryUsing`. This replaced the ternary filter.
- **"View in Site" edit action** — `EditPost::getHeaderActions()` adds an `Action` (gray, external-
  link icon, opens in new tab) linking to `route('blog.post.detail', …)`. It is **only visible when
  the post is actually viewable**: `->visible(fn () => Post::published()->whereKey($this->record
  ->getKey())->exists())`, so drafts *and* future-scheduled posts hide it (a bare `is_published`
  check would leave a 404 link on scheduled posts).

---

## Seeder note

`database/seeders/PostSeeder.php`'s `Post::create([...])` calls omit `is_published`, so every seeded
post defaults to published via the DB default; draft/scheduled posts are demonstrated via the admin.

At the end of `run()` it now also seeds curated related posts (via `syncRelatedPosts()`, so the
bidirectional pivot stays consistent):
- `Post::find(3)->syncRelatedPosts([4, 5, 8])` — one explicit grouping.
- **TV marathons (category 5):** posts are grouped dynamically by show name (the text before
  `" Season <n>"` in `"<Show> Season <n> Marathon"`, tolerating a `"Part <n>"`), and every season of a
  show with 2+ seasons is related to all the others. Single-season shows get no relations. This
  auto-covers all shows (Big Bang Theory, Manifest incl. its Part 1/2, Warrior Nun, Shooter, CSI,
  Designated Survivor, Kingdom, The Witcher, Wrong Side of the Tracks, …) with no hardcoded lists.

## Tests — `tests/Feature/Blog/BlogEnhancementsTest.php` (Pest, opt-in `RefreshDatabase`)

15 tests, using an inline `makePost()` helper (creates a user via `UserFactory` + a shared `General`
category, since only `UserFactory` exists). Coverage:
- `published()` includes a live post, excludes drafts and future-dated posts.
- `DetailPost` route → 200 for published, **404** for draft, **404** for future-dated.
- `reading_time`: short body → 1; ~450 words → 3.
- `syncRelatedPosts`: bidirectional add; ignores self-references; removal pulls from both sides;
  deleting a post strips its id from other rows.
- Detail `related` view data lists only **published** related posts.
- Prev/Next: middle post resolves correct neighbours; same-date ties break by `id`; ends have no
  neighbour; draft/future posts are skipped as neighbours.

## Verification (done)

1. `php artisan migrate` applied both migrations cleanly; `migrate:fresh --seed` reseeds without error.
2. `vendor/bin/pest` — 30 passed (15 new). Note: the suite wipes the dev DB (no `.env.testing`), so
   reseed with `migrate:fresh --seed` after running it.
3. Browser (via `php artisan serve`): draft post URL returns 404; detail page shows the reading-time
   badge, prev/next links, and the sorted `→` related list; Filament shows Published/Draft tabs
   (Published default) and the "View in Site" button appears only for viewable posts.
4. `vendor/bin/pint` — clean on all changed files.
