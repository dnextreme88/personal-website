<?php

namespace App\Livewire;

use App\Models\SoldItem;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Archives extends Component
{
    use WithPagination;

    public $brands = [];
    public $types = [];
    public $months = [];
    public $years = [];
    public $tags = [];

    public $is_filtered = false;
    public $search_query = '';
    public $archive_brands_choice;
    public $archive_types_choice;
    public $archive_months_choice;
    public $archive_years_choice;
    public $archive_pay_methods_choice;
    public $archive_sell_methods_choice;
    public $archive_tags_choice = [];

    public function search_archives()
    {
        $this->is_filtered = true;

        $this->resetPage(); // Reset pagination after filtering data

        $this->dispatch('filtered-sold-items');
    }

    public function mount()
    {
        $this->brands = SoldItem::all()->pluck('brand')
            ->unique()
            ->sort()
            ->values();

        $this->types = SoldItem::all()->pluck('type')
            ->unique()
            ->sort()
            ->values();

        foreach (range(1, 12) as $month_number) {
            $month_index = $month_number < 10 ? '0' .$month_number : $month_number;

            $this->months[$month_index] = Carbon::create()->month($month_number)->format('F');
        }

        $this->years = range(2014, date('Y'));

        $collected_tags = SoldItem::all()->pluck('tags')
            ->reduce(function (?string $carry, ?string $item): ?string {
                if ($item) {
                    $carry .= $item. ',';
                }

                return $carry;
            });

        $this->tags = Str::of($collected_tags)->explode(',')
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    #[On('filtered-sold-items')]
    public function render()
    {
        if ($this->is_filtered) {
            $search = SoldItem::query();

            if ($this->search_query) {
                $search = $search->whereLike('name', '%' .$this->search_query. '%');
            }

            if ($this->archive_brands_choice) {
                $search = $search->where('brand', $this->archive_brands_choice);
            }

            if ($this->archive_types_choice) {
                $search = $search->where('type', $this->archive_types_choice);
            }

            if ($this->archive_months_choice) {
                $search = $search->whereMonth('date_sold', $this->archive_months_choice);
            }

            if ($this->archive_years_choice) {
                $search = $search->whereYear('date_sold', $this->archive_years_choice);
            }

            if ($this->archive_pay_methods_choice) {
                $search = $search->whereHas('pay_method', fn (Builder $query) => $query->where('method', $this->archive_pay_methods_choice));
            }

            if ($this->archive_sell_methods_choice) {
                $search = $search->whereHas('sell_method', fn (Builder $query) => $query->where('method', $this->archive_sell_methods_choice));
            }

            if ($this->archive_tags_choice) {
                $search = $search->where(function (Builder $query) {
                    foreach ($this->archive_tags_choice as $selected_tag) {
                        $query->whereLike('tags', '%' .$selected_tag. '%');
                    }
                });
            }

            $sold_items = $search->paginate(18);
        } else {
            $sold_items = SoldItem::with(['pay_method', 'sell_method'])->paginate(18);
        }

        return view('livewire.archives', ['sold_items' => $sold_items]);
    }
}
