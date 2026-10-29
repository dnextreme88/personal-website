<?php

namespace App\Livewire\Archive;

use App\Models\DroppingArea;
use Livewire\Component;

class StaticDroppingAreas extends Component
{
    public function render()
    {
        $dropping_areas = DroppingArea::routeOrder()->get();

        // V1 is the oldest station
        $stations = $dropping_areas->values()->map(fn (DroppingArea $dropping_area, int $i) => [
            'tag' => 'V' .($i + 1),
            'address' => $dropping_area->address,
            'unit' => $dropping_area->unit,
            'note' => $dropping_area->note,
            'opens_at' => $dropping_area->opens_at,
            'closes_at' => $dropping_area->closes_at,
            'date' => $dropping_area->started_on?->format('Y-m-d'),
            'url' => asset('storage/' .$dropping_area->image_path),
            'is_current' => $dropping_area->is_current,
            // Only a known change of address is a transfer; an empty address is not
            'is_transfer' => $i > 0
                && $dropping_area->address
                && $dropping_areas[$i - 1]->address
                && $dropping_areas[$i - 1]->address !== $dropping_area->address,
        ]);

        return view('livewire.archive.static-dropping-areas', [
            'stations' => $stations->all(),
        ]);
    }
}
