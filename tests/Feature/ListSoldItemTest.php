<?php

use App\Livewire\Archive\ListSoldItem;
use App\Models\PayMethod;
use App\Models\SellMethod;
use App\Models\SoldItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    PayMethod::create(['method' => 'cash on-hand', 'remittance_location' => 'SM']);
    SellMethod::create(['method' => 'meetup', 'location' => 'SM']);
});

/**
 * The `tags` key is omitted by default so the model's (non-nullable) tags
 * mutator is never invoked with null; pass it explicitly when a tag is needed.
 */
function createSoldItem(array $attrs = []): SoldItem
{
    return SoldItem::create(array_merge([
        'pay_method_id' => PayMethod::query()->value('id'),
        'sell_method_id' => SellMethod::query()->value('id'),
        'brand' => 'Ecko',
        'type' => 'Shirt',
        'price' => 100,
        'condition' => 'used',
        'size' => 'L',
        'date_sold' => '2014-05-01',
    ], $attrs));
}

it('renders the sold items page with the summary section', function () {
    createSoldItem(['brand' => 'Ecko']);

    $this->get(route('archive.sold-items.list'))
        ->assertStatus(200)
        ->assertSee('Sold Items Summary');
});

it('summarizes the whole collection regardless of the active filter', function () {
    createSoldItem(['brand' => 'Ecko']);
    createSoldItem(['brand' => 'Ecko']);
    createSoldItem(['brand' => 'Nike']);

    $component = Livewire::test(ListSoldItem::class)
        ->call('search_archives', ['archive_brands_choice' => 'Ecko']);

    // Summary stays global (all 3 items) even though the results are filtered to 2.
    $summary = $component->get('summary');
    expect($summary['total_items'])->toBe(3)
        ->and($summary['unique_brands'])->toBe(2);

    $component->assertViewHas('sold_items', fn ($items) => $items->total() === 2);
});

it('computes the top 3 brands and headline totals', function () {
    createSoldItem(['brand' => 'Ecko', 'transaction_id' => '2015_01', 'date_sold' => '2015-01-01']);
    createSoldItem(['brand' => 'Ecko', 'transaction_id' => '2015_01', 'date_sold' => '2015-01-01']); // same tx
    createSoldItem(['brand' => 'Nike', 'transaction_id' => '2020_01', 'date_sold' => '2020-03-01']);

    $summary = Livewire::test(ListSoldItem::class)->get('summary');

    expect($summary['top_brands'])->toBe(['Ecko' => 2, 'Nike' => 1])
        ->and($summary['total_transactions'])->toBe(2)  // {2015_01, 2020_01}
        ->and($summary['total_years'])->toBe(5);        // 2020 - 2015
});

it('builds the per-year chart series over the whole collection', function () {
    createSoldItem(['brand' => 'Ecko', 'name' => 'Shirt', 'type' => 'Tee', 'date_sold' => '2014-05-01', 'price' => 100]);
    createSoldItem(['brand' => 'Ecko', 'date_sold' => '2015-06-01', 'price' => 200]);
    createSoldItem(['brand' => 'Ecko', 'date_sold' => '2015-06-15', 'price' => 150]);
    createSoldItem(['brand' => 'Nike', 'name' => 'Air', 'type' => 'Shoe', 'date_sold' => '2015-07-01', 'price' => 400]);

    $charts = Livewire::test(ListSoldItem::class)->get('summary')['charts'];

    expect($charts['total_items']['labels'])->toBe(['2014', '2015'])
        ->and($charts['total_items']['data'])->toBe([1, 3])          // count per year
        ->and($charts['total_price']['data'][1])->toBe(750.0)       // sum in 2015
        ->and($charts['avg_price']['data'][1])->toBe(250.0);        // avg in 2015

    // Top selling brand each year (Ecko wins 2015 with 2 items). Labels are plain
    // years; the brand name rides along in `meta` for the hover tooltip.
    expect($charts['top_brand']['labels'])->toBe(['2014', '2015'])
        ->and($charts['top_brand']['data'])->toBe([1, 2])
        ->and($charts['top_brand']['meta'])->toBe(['Ecko', 'Ecko']);

    // Top selling item each year (highest price; Nike Air Shoe in 2015).
    expect($charts['top_item']['labels'])->toBe(['2014', '2015'])
        ->and($charts['top_item']['data'][1])->toBe(400.0)
        ->and($charts['top_item']['meta'][1])->toBe('Nike Air Shoe');
});

it('ranks top types, sell locations and payment locations', function () {
    $gcash = PayMethod::create(['method' => 'remittance', 'remittance_location' => 'GCash']);
    $mall = SellMethod::create(['method' => 'meetup', 'location' => 'Mall']);

    // Defaults use the beforeEach pay/sell methods (both located at "SM").
    createSoldItem(['type' => 'Shirt', 'date_sold' => '2014-12-01']);
    createSoldItem(['type' => 'Shirt', 'date_sold' => '2015-12-01']);
    createSoldItem([
        'type' => 'Pants',
        'date_sold' => '2015-01-01',
        'sell_method_id' => $mall->id,
        'pay_method_id' => $gcash->id,
    ]);

    $summary = Livewire::test(ListSoldItem::class)->get('summary');

    expect($summary['top_types'])->toBe(['Shirt' => 2, 'Pants' => 1])
        ->and($summary['top_sell_locations'])->toBe(['SM' => 2, 'Mall' => 1])
        ->and($summary['top_pay_locations'])->toBe(['SM' => 2, 'GCash' => 1]);
});

it('counts distinct transactions per year and per month', function () {
    createSoldItem(['transaction_id' => '2015_01', 'date_sold' => '2015-01-05']);
    createSoldItem(['transaction_id' => '2015_01', 'date_sold' => '2015-01-05']); // same tx → counts once
    createSoldItem(['transaction_id' => '2015_02', 'date_sold' => '2015-01-20']);
    createSoldItem(['transaction_id' => '2016_01', 'date_sold' => '2016-02-10']);

    $charts = Livewire::test(ListSoldItem::class)->get('summary')['charts'];

    expect($charts['tx_year']['labels'])->toBe(['2015', '2016'])
        ->and($charts['tx_year']['data'])->toBe([2, 1]); // 2015: {2015_01, 2015_02}; 2016: {2016_01}

    $jan = array_search('Jan', $charts['tx_month']['labels']);
    $feb = array_search('Feb', $charts['tx_month']['labels']);
    expect($charts['tx_month']['data'][$jan])->toBe(2)
        ->and($charts['tx_month']['data'][$feb])->toBe(1);
});

it('totals sold items across calendar months', function () {
    createSoldItem(['date_sold' => '2014-06-01']);
    createSoldItem(['date_sold' => '2015-06-01']); // two Junes across the years
    createSoldItem(['date_sold' => '2015-08-01']); // one August

    $total_month = Livewire::test(ListSoldItem::class)->get('summary')['charts']['total_month'];

    expect($total_month['labels'])->toHaveCount(12);

    $june = array_search('Jun', $total_month['labels']);
    $august = array_search('Aug', $total_month['labels']);
    expect($total_month['data'][$june])->toBe(2)   // two Junes total
        ->and($total_month['data'][$august])->toBe(1); // one August total
});

it('sorts results by price and toggles direction on repeat', function () {
    createSoldItem(['brand' => 'Cheap', 'price' => 10]);
    createSoldItem(['brand' => 'Pricey', 'price' => 500]);

    Livewire::test(ListSoldItem::class)
        ->call('apply_sort', 'price')
        ->assertSet('sort_field', 'price')
        ->assertSet('sort_direction', 'desc')
        ->assertViewHas('sold_items', fn ($items) => (int) $items->first()->price === 500)
        ->call('apply_sort', 'price')
        ->assertSet('sort_direction', 'asc')
        ->assertViewHas('sold_items', fn ($items) => (int) $items->first()->price === 10);
});

it('ignores sort requests for non-whitelisted fields', function () {
    createSoldItem();

    Livewire::test(ListSoldItem::class)
        ->call('apply_sort', 'notes')
        ->assertSet('sort_field', 'date_sold');
});

it('shows the hot item badge for an item tagged hot item', function () {
    createSoldItem(['brand' => 'Blazing', 'tags' => 'hot item']);

    Livewire::test(ListSoldItem::class)
        ->assertSeeHtml('title="Hot item"');
});

it('hides the hot item badge when no item is tagged hot item', function () {
    createSoldItem(['brand' => 'Mild']);

    Livewire::test(ListSoldItem::class)
        ->assertDontSeeHtml('title="Hot item"');
});
