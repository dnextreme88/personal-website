<?php

namespace App\Filament\Resources\SteamAchievementResource\Pages;

use App\Filament\Resources\SteamAchievementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSteamAchievement extends EditRecord
{
    protected static string $resource = SteamAchievementResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
