# CLAUDE.md

Guidance for Claude Code when working in this repo. Human-facing docs live in [README.md](README.md); this file is the agent-facing companion.

## Stack

Personal website on the **TALL stack** (Tailwind, Alpine, Laravel, Livewire) with a Filament admin panel. Major versions matter — Filament 4, Livewire 3, and Tailwind 4 differ significantly from their previous majors, so prefer current-version APIs.

- PHP `^8.3`, Laravel `^12.10`
- Livewire `^3.6`, Filament `4.2.4`
- Tailwind `^4.1`, Vite `^7`
- Pest `^3.8` (tests), Pint `^1.26` (formatting)

## Commands

- Run app (server + queue + vite concurrently): `composer run dev`
- Tests: `vendor/bin/pest` (or `php artisan test`)
- Format code: `vendor/bin/pint` — run before committing
- Fresh DB with seed data: `php artisan migrate:fresh --seed`
- Clear caches: `php artisan optimize:clear`

## Layout

- **Routes** ([routes/web.php](routes/web.php)) are driven by full-page Livewire components. Route naming uses prefixed groups: `archive.*`, `blog.*` (e.g. `blog.post.detail` at `/blog/{id}-{slug}`).
- **Livewire** components in `app/Livewire`, grouped under `Blog/` and `Archive/`, plus top-level ones (Homepage, AboutMe, CreateContactMe, Calendar).
- **Filament** admin: resources in `app/Filament/Resources/*` (Post, Category, SoldItem), widgets in `app/Filament/Widgets`, panel config in [app/Providers/Filament/AdminPanelProvider.php](app/Providers/Filament/AdminPanelProvider.php).
- **Models** in `app/Models`; blog models namespaced under `app/Models/Blog` (Post, Category); shared traits in `app/Models/Traits`.
- **Enums** in `app/Enums` (Conditions, DroppingAreas, PaymentMethods, etc.).
- **Views**: Blade in `resources/views/{components,livewire,emails,vendor}`.
- **Seeders** in `database/seeders` — seed data (especially posts) is edited frequently.

## Conventions

- **Formatting** is enforced by Pint's custom ruleset in [pint.json](pint.json) (Laravel preset + tweaks like `concat_space: false` and blank line before control statements). Run `vendor/bin/pint` before committing.
- **Tests** use Pest — all tests in this suite are written with Pest. `RefreshDatabase` is commented out in [tests/Pest.php](tests/Pest.php), so DB-touching tests must opt in deliberately. When you change a file, run the relevant tests (`vendor/bin/pest`); if no test covers the change, write one before finishing.
- **Git**: feature branches follow `type/YYYYMMDD-description` (e.g. `enhancements/20260519-blog`, `chore/20260404-...`, `feature/...`). Commit subjects are lowercase and imperative (`add`, `fix`, `refactor`, `update`), and the first line must stay within 100 characters (it is the only part shown in summaries). PRs merge to `master`.
