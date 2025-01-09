<?php

namespace App\Filament\Resources\SoldItemResource\Pages;

use App\Filament\Resources\SoldItemResource;
use App\Models\PayMethod;
use App\Models\SellMethod;
use Filament\Resources\Pages\CreateRecord;

class CreateSoldItem extends CreateRecord
{
    // TODO: TO ADD LOGIC TO SORT TAGS ALPHABETICALLY BEFORE SAVING TO DB
    protected static string $resource = SoldItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $payment_method = PayMethod::create([
            'method' => $data['pay_method_name'],
            'remittance_location' => $data['pay_method_location'],
        ]);

        $sell_method = SellMethod::create([
            'method' => $data['sell_method_name'],
            'location' => $data['sell_method_location'],
        ]);

        $data['pay_method_id'] = $payment_method->id;
        $data['sell_method_id'] = $sell_method->id;

        return $data;
    }
}
