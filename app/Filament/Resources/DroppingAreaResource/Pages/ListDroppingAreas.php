<?php

namespace App\Filament\Resources\DroppingAreaResource\Pages;

use App\Filament\Resources\DroppingAreaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDroppingAreas extends ListRecords
{
    protected static string $resource = DroppingAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
