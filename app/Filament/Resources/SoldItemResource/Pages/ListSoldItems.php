<?php

namespace App\Filament\Resources\SoldItemResource\Pages;

use App\Filament\Resources\SoldItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSoldItems extends ListRecords
{
    protected static string $resource = SoldItemResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
