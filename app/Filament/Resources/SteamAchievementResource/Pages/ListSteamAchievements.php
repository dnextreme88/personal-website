<?php

namespace App\Filament\Resources\SteamAchievementResource\Pages;

use App\Filament\Resources\SteamAchievementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSteamAchievements extends ListRecords
{
    protected static string $resource = SteamAchievementResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
