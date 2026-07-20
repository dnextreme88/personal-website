<?php

namespace App\Livewire\Archive;

use App\Models\SoldItem;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ListSoldItem extends Component
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
    public $sort_field = 'date_sold';
    public $sort_direction = 'desc';

    // Whole-collection overview shown above the filters; independent of any active filter.
    public $summary = [];

    /** Columns the results may be ordered by. */
    private const SORTABLE_FIELDS = ['date_sold', 'price', 'name'];

    #[On('view-changed')]
    public function onViewChanged(): void
    {
        $this->resetPage();
        $this->dispatch('filtered-sold-items'); // Keep the filter on
    }

    public function apply_sort(string $field): void
    {
        if (!in_array($field, self::SORTABLE_FIELDS)) {
            return;
        }

        if ($this->sort_field === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_field = $field;
            $this->sort_direction = $field === 'name' ? 'asc' : 'desc';
        }

        $this->resetPage();
        $this->dispatch('filtered-sold-items');
    }

    public function reset_archives_form()
    {
        $this->reset([
            'is_filtered',
            'search_query',
            'archive_brands_choice',
            'archive_types_choice',
            'archive_months_choice',
            'archive_years_choice',
            'archive_pay_methods_choice',
            'archive_sell_methods_choice',
            'archive_tags_choice',
        ]);

        $this->is_filtered = false;
        $this->resetPage();
        $this->dispatch('form-reset');
    }

    public function search_archives(array $filters = [])
    {
        $this->archive_brands_choice = $filters['archive_brands_choice'] ?? '';
        $this->archive_types_choice = $filters['archive_types_choice'] ?? '';
        $this->archive_months_choice = $filters['archive_months_choice'] ?? '';
        $this->archive_years_choice = $filters['archive_years_choice'] ?? '';
        $this->archive_pay_methods_choice = $filters['archive_pay_methods_choice'] ?? '';
        $this->archive_sell_methods_choice = $filters['archive_sell_methods_choice'] ?? '';
        $this->is_filtered = true;

        $this->resetPage();
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

        $this->summary = $this->build_summary();
    }

    /**
     * Build the sold items query with the active filters applied. Drives the
     * paginated results only — the summary above the form is deliberately global.
     */
    private function build_query(): Builder
    {
        $query = SoldItem::query()->with(['pay_method', 'sell_method']);

        if (!$this->is_filtered) {
            return $query;
        }

        if ($this->search_query) {
            $query->whereLike('name', '%' .$this->search_query. '%');
        }

        if ($this->archive_brands_choice) {
            $query->where('brand', $this->archive_brands_choice);
        }

        if ($this->archive_types_choice) {
            $query->where('type', $this->archive_types_choice);
        }

        if ($this->archive_months_choice) {
            $query->whereMonth('date_sold', $this->archive_months_choice);
        }

        if ($this->archive_years_choice) {
            $query->whereYear('date_sold', $this->archive_years_choice);
        }

        if ($this->archive_pay_methods_choice) {
            $query->whereHas('pay_method', fn (Builder $q) => $q->where('method', $this->archive_pay_methods_choice));
        }

        if ($this->archive_sell_methods_choice) {
            $query->whereHas('sell_method', fn (Builder $q) => $q->where('method', $this->archive_sell_methods_choice));
        }

        if ($this->archive_tags_choice) {
            $query->where(function (Builder $q) {
                foreach ($this->archive_tags_choice as $selected_tag) {
                    $q->whereLike('tags', '%' .$selected_tag. '%');
                }
            });
        }

        return $query;
    }

    /**
     * Whole-collection summary: headline cards plus the per-year / per-month chart
     * series. Computed once in mount() because it never changes with the filter.
     */
    private function build_summary(): array
    {
        $total_items = SoldItem::query()->count();

        if ($total_items === 0) {
            return ['total_items' => 0];
        }

        $total_transactions = SoldItem::query()->whereNotNull('transaction_id')->distinct()->count('transaction_id');

        $top_brands = SoldItem::query()->select('brand', DB::raw('COUNT(*) as total'))
            ->groupBy('brand')
            ->orderByDesc('total')
            ->limit(3)
            ->pluck('total', 'brand')
            ->toArray();

        $top_types = SoldItem::query()->select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->limit(3)
            ->pluck('total', 'type')
            ->toArray();

        $top_sell_locations = SoldItem::query()
            ->join('sell_methods', 'sold_items.sell_method_id', '=', 'sell_methods.id')
            ->select('sell_methods.location', DB::raw('COUNT(*) as total'))
            ->groupBy('sell_methods.location')
            ->orderByDesc('total')
            ->limit(3)
            ->pluck('total', 'location')
            ->toArray();

        $top_pay_locations = SoldItem::query()
            ->join('pay_methods', 'sold_items.pay_method_id', '=', 'pay_methods.id')
            ->select('pay_methods.remittance_location', DB::raw('COUNT(*) as total'))
            ->groupBy('pay_methods.remittance_location')
            ->orderByDesc('total')
            ->limit(3)
            ->pluck('total', 'remittance_location')
            ->toArray();

        // Per-year numeric aggregates (total items, avg price, total price).
        $per_year = SoldItem::query()
            ->selectRaw('YEAR(date_sold) as year, COUNT(*) as cnt, SUM(price) as total_price, AVG(price) as avg_price')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->keyBy('year');

        // Every item grouped by year, for the "top brand / top item each year" charts.
        $items_by_year = SoldItem::query()->get(['brand', 'name', 'type', 'price', 'date_sold'])
            ->groupBy(fn (SoldItem $item) => Carbon::parse($item->date_sold)->year);

        $min_year = (int) $per_year->keys()->min();
        $max_year = (int) $per_year->keys()->max();

        // Distinct transactions per year / month. A transaction_id groups items bought
        // together into one transaction, so each unique id counts once.
        $tx_per_year = SoldItem::query()->whereNotNull('transaction_id')
            ->selectRaw('YEAR(date_sold) as year, COUNT(DISTINCT transaction_id) as total')
            ->groupBy('year')
            ->get()
            ->mapWithKeys(fn ($r) => [(int) $r->year => (int) $r->total]);

        $tx_per_month = SoldItem::query()->whereNotNull('transaction_id')
            ->selectRaw('MONTH(date_sold) as month, COUNT(DISTINCT transaction_id) as total')
            ->groupBy('month')
            ->get()
            ->mapWithKeys(fn ($r) => [(int) $r->month => (int) $r->total]);

        $year_labels = [];
        $total_items_series = [];
        $avg_price_series = [];
        $total_price_series = [];
        $top_brand_data = [];
        $top_brand_meta = [];
        $top_item_data = [];
        $top_item_meta = [];
        $tx_year_series = [];

        foreach (range($min_year, $max_year) as $year) {
            $row = $per_year->get($year);
            $year_labels[] = (string) $year;
            $total_items_series[] = $row ? (int) $row->cnt : 0;
            $avg_price_series[] = $row ? round((float) $row->avg_price, 2) : 0;
            $total_price_series[] = $row ? round((float) $row->total_price, 2) : 0;

            $year_items = $items_by_year->get($year);

            // Top selling brand this year = the brand with the most items sold.
            $brand_counts = $year_items ? $year_items->groupBy('brand')->map->count()->sortDesc() : collect();
            $top_brand = $brand_counts->isNotEmpty() ? (string) $brand_counts->keys()->first() : '—';

            // Top selling item this year = the highest-priced item sold.
            $top_item = $year_items?->sortByDesc(fn (SoldItem $item) => (float) $item->price)->first();
            $top_item_name = $top_item ? Str::squish($top_item->item_name) : '—';

            $top_brand_data[] = $brand_counts->isNotEmpty() ? (int) $brand_counts->first() : 0;
            $top_brand_meta[] = $top_brand;

            $top_item_data[] = $top_item ? (float) $top_item->price : 0;
            $top_item_meta[] = $top_item_name;

            $tx_year_series[] = $tx_per_year->get($year, 0);
        }

        // Total items sold per calendar month across every year.
        $per_month = SoldItem::query()
            ->selectRaw('MONTH(date_sold) as month, COUNT(*) as cnt')
            ->groupBy('month')
            ->get()
            ->mapWithKeys(fn ($r) => [(int) $r->month => (int) $r->cnt]);

        $month_labels = [];
        $total_month_series = [];
        $tx_month_series = [];

        foreach (range(1, 12) as $month_number) {
            $month_labels[] = Carbon::create()->month($month_number)->format('M');
            $total_month_series[] = $per_month->get($month_number, 0);
            $tx_month_series[] = $tx_per_month->get($month_number, 0);
        }

        return [
            'total_items' => $total_items,
            'total_transactions' => $total_transactions,
            'total_years' => $max_year - $min_year,
            'unique_brands' => SoldItem::query()->distinct()->count('brand'),
            'top_brands' => $top_brands,
            'top_types' => $top_types,
            'top_sell_locations' => $top_sell_locations,
            'top_pay_locations' => $top_pay_locations,
            'charts' => [
                'total_items' => ['labels' => $year_labels, 'data' => $total_items_series],
                'total_month' => ['labels' => $month_labels, 'data' => $total_month_series],
                'avg_price' => ['labels' => $year_labels, 'data' => $avg_price_series, 'money' => true],
                'total_price' => ['labels' => $year_labels, 'data' => $total_price_series, 'money' => true],
                'top_brand' => ['labels' => $year_labels, 'data' => $top_brand_data, 'meta' => $top_brand_meta],
                'top_item' => ['labels' => $year_labels, 'data' => $top_item_data, 'meta' => $top_item_meta, 'money' => true],
                'tx_year' => ['labels' => $year_labels, 'data' => $tx_year_series],
                'tx_month' => ['labels' => $month_labels, 'data' => $tx_month_series],
            ],
        ];
    }

    #[On('filtered-sold-items')]
    public function render()
    {
        $sold_items = $this->build_query()
            ->orderBy($this->sort_field, $this->sort_direction)
            ->paginate(18);

        return view('livewire.archive.list-sold-item', ['sold_items' => $sold_items]);
    }
}
