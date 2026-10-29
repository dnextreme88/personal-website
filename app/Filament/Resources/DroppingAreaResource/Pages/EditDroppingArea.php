<?php

namespace App\Filament\Resources\DroppingAreaResource\Pages;

use App\Filament\Resources\DroppingAreaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDroppingArea extends EditRecord
{
    protected static string $resource = DroppingAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
