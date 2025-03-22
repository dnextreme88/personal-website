<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class Calendar extends Component
{
    public $current_day;
    public $current_month;
    public $current_year;
    public $current_month_year;
    public $days_in_month;
    public $first_day_of_month;
    public $previous_month_days;

    public function calculate_days()
    {
        $base_carbon_instance = Carbon::create($this->current_year, $this->current_month, 1);

        $this->days_in_month = $base_carbon_instance->copy()->daysInMonth;
        $this->first_day_of_month = $base_carbon_instance->copy()->dayOfWeek;

        $previous_month = $base_carbon_instance->copy()->subMonth();
        $this->previous_month_days = $previous_month->daysInMonth;
    }

    public function switch_to_previous_month()
    {
        $this->current_month--;

        if ($this->current_month < 1) {
            $this->current_month = 12;
            $this->current_year--;
        }

        $this->calculate_days();
    }

    public function switch_to_next_month()
    {
        $this->current_month++;

        if ($this->current_month > 12) {
            $this->current_month = 1;
            $this->current_year++;
        }

        $this->calculate_days();
    }

    public function mount()
    {
        $this->current_day = Carbon::now()->day;
        $this->current_month = Carbon::now()->month;
        $this->current_year = Carbon::now()->year;

        $this->calculate_days();
    }

    public function render()
    {
        $this->current_month_year = Carbon::create($this->current_year, $this->current_month, 1)->format('F Y');

        return view('livewire.calendar');
    }
}
