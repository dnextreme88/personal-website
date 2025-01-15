<?php

namespace App\Filament\Resources\SoldItemResource\Pages;

use App\Filament\Resources\SoldItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditSoldItem extends EditRecord
{
    // TODO: TO ADD LOGIC TO SORT TAGS ALPHABETICALLY BEFORE SAVING TO DB
    protected static string $resource = SoldItemResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->getRecord()->pay_method->method = $data['pay_method_name'];
        $this->getRecord()->pay_method->remittance_location = $data['pay_method_location'];
        $this->getRecord()->pay_method->save();

        $this->getRecord()->sell_method->method = $data['sell_method_name'];
        $this->getRecord()->sell_method->location = $data['sell_method_location'];
        $this->getRecord()->sell_method->save();

        $old_image_location_path = $this->record->image_location;

        if ($old_image_location_path && ($old_image_location_path != $data['image_location'])) {
            Storage::disk('public')->delete($old_image_location_path);
        }

        return $data;
    }
}
