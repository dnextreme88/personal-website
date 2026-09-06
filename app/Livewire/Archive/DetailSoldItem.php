<?php

namespace App\Livewire\Archive;

use App\Models\SoldItem;
use Livewire\Component;

class DetailSoldItem extends Component
{
    public int $id;
    public string $brand;
    public string $type;

    public function mount(int $id, string $brand, string $type)
    {
        $this->id = $id;
        $this->brand = $brand;
        $this->type = $type;
    }

    public function render()
    {
        $sold_item = SoldItem::with(['pay_method', 'sell_method'])->findOrFail($this->id);

        return view('livewire.archive.detail-sold-item', ['sold_item' => $sold_item]);
    }
}
