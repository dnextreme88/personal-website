<?php

namespace App\Livewire\Archive;

use App\Models\SteamAchievement;
use Carbon\Carbon;
use Livewire\Component;

class ListSteamAchievement extends Component
{
    public function render()
    {
        // Shaped for the Alpine sorter in the view: a sortable ISO date plus a display date,
        // and tags pre-split into an array for the badge loop.
        $steam_achievements = SteamAchievement::all()->map(fn (SteamAchievement $achievement): array => [
            'id' => $achievement->id,
            'game_name' => $achievement->game_name,
            'date_completed' => Carbon::parse($achievement->date_completed)->format('Y-m-d'),
            'date_completed_display' => Carbon::parse($achievement->date_completed)->format('M d, Y'),
            'tags' => $achievement->tags,
            'tags_array' => $achievement->tags ? array_map('trim', explode(',', $achievement->tags)) : [],
            'notes' => $achievement->notes,
        ])->values();

        return view('livewire.archive.list-steam-achievement', [
            'steam_achievements' => $steam_achievements,
        ]);
    }
}
