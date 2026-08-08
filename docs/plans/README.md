# Plans

Implementation and upgrade plans for this repo — the reasoning and step-by-step behind larger
changes, kept alongside the code so the "why" survives.

Each plan is a single Markdown file. When a plan has been carried out, it gets an **outcome
banner** at the top recording what was actually done (which may differ from the original plan) and
how it was verified.

## Index

- [20260712-laravel-13-upgrade.md](20260712-laravel-13-upgrade.md) — Laravel 12 → 13 upgrade (executed **2026-07-12** as
  a three-way Laravel 13 + Filament 5 + Livewire 4 upgrade).
- [20260712-steam-achievements.md](20260712-steam-achievements.md) — Steam Achievements table, model, seeder,
  Livewire `/archive` page & Filament resource (executed **2026-07-12**).
- [20260720-sold-items-enhancements.md](20260720-sold-items-enhancements.md) — Sold Items page summary stats, server-side
  sorting, hot-item badge & Chart.js sales chart (executed **2026-07-20**).
- [20260722-blog-enhancements.md](20260722-blog-enhancements.md) — Blog draft/publish status, curated related posts,
  reading-time badge, prev/next navigation & admin tabs (executed **2026-07-22**).
- [20260725-cyberpunk-design.md](20260725-cyberpunk-design.md) — Site-wide cyberpunk/retro-futuristic design layer:
  neon glow, glitch/typewriter/decode text, HUD-framed cards, scanline + grid ambient & cyber
  loaders (executed **2026-07-25**).

## Conventions

- One file per plan, named `{YYYYMMDD}-kebab-case-topic.md` (date the plan was executed).
- Lead with a **Context** section (why the change is happening).
- Once executed, prepend a dated outcome banner: files changed, what was skipped, verification.
