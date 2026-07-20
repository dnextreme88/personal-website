# Plans

Implementation and upgrade plans for this repo — the reasoning and step-by-step behind larger
changes, kept alongside the code so the "why" survives.

Each plan is a single Markdown file. When a plan has been carried out, it gets an **outcome
banner** at the top recording what was actually done (which may differ from the original plan) and
how it was verified.

## Index

- [laravel-13-upgrade.md](laravel-13-upgrade.md) — Laravel 12 → 13 upgrade (executed 2026-07-12 as
  a three-way Laravel 13 + Filament 5 + Livewire 4 upgrade).
- [steam-achievements.md](steam-achievements.md) — Steam Achievements table, model, seeder,
  Livewire `/archive` page & Filament resource (executed 2026-07-12).
- [sold-items-enhancements.md](sold-items-enhancements.md) — Sold Items page summary stats, server-side
  sorting, hot-item badge & Chart.js sales chart (executed 2026-07-19).

## Conventions

- One file per plan, named `kebab-case-topic.md`.
- Lead with a **Context** section (why the change is happening).
- Once executed, prepend a dated outcome banner: files changed, what was skipped, verification.
