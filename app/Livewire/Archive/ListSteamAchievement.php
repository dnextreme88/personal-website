<?php

namespace App\Livewire\Archive;

use App\Models\SteamAchievement;
use Livewire\Component;

class ListSteamAchievement extends Component
{
    public function render()
    {
        return view('livewire.archive.list-steam-achievement', [
            'steam_achievements' => SteamAchievement::all(),
        ]);
    }
}
