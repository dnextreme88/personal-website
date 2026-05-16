<?php

namespace App\Filament\Resources\SoldItemResource\Pages;

use App\Filament\Resources\SoldItemResource;
use App\Models\SoldItem;
use App\Models\PayMethod;
use App\Models\SellMethod;
use Filament\Resources\Pages\CreateRecord;

class CreateSoldItem extends CreateRecord
{
    // TODO: TO ADD LOGIC TO SORT TAGS ALPHABETICALLY BEFORE SAVING TO DB
    protected static string $resource = SoldItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $last_sold_item = SoldItem::orderBy('date_sold', 'desc')->first();
        [$year, $sequence] = explode('_', $last_sold_item->transaction_id);
        $sold_year = date('Y', strtotime($data['date_sold'])); // Get year of last sold item

        // AI-generated comment:
        // If the last transaction's year is behind the new item's date_sold year,
        // reset the sequence to 01 for the new year (e.g. 2026_06 → 2027_01).
        // Otherwise, just increment the sequence (e.g. 2026_06 → 2026_07).
        // We use date_sold's year instead of the system year to handle
        // backdated/forward-dated entries correctly.
        $next_transaction_id = (int)$year < (int)$sold_year
            ? $sold_year . '_01'
            : $year . '_' . str_pad((int)$sequence + 1, 2, '0', STR_PAD_LEFT);

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
        $data['transaction_id'] = $next_transaction_id;

        return $data;
    }
}
