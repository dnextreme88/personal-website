# Personal Website

A personal website created using the TALL stack (Tailwind, Alpine, Laravel, and Livewire).

## Useful Commands

- Starts the server
```
composer run dev
```

- Drops the current DB, migrates all tables, and populates the tables
```
php artisan migrate:fresh --seed
```

- Clear cache
```
php artisan optimize:clear
```

- Compares commits between 2 branches
```
git log <origin branch eg. master>..<feature branch> --pretty="- %s" 
```

## Planned Features
- [x] Blog (from my old WordPress blog site)
- [ ] Mods (showcase of game modifications from Nexus Mods, most likely under Archives)
- [ ] Portfolio (links to my other GitHub repos)
 