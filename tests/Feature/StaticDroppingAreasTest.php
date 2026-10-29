<?php

use App\Filament\Resources\DroppingAreaResource\Pages\CreateDroppingArea;
use App\Filament\Resources\DroppingAreaResource\Pages\ListDroppingAreas;
use App\Livewire\Archive\StaticDroppingAreas;
use App\Models\DroppingArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeDroppingArea(array $attributes = []): DroppingArea
{
    return DroppingArea::create([
        'address' => null,
        'unit' => 'Room 101',
        'note' => null,
        'opens_at' => null,
        'closes_at' => null,
        'started_on' => null,
        'image_path' => 'dropping-areas/test.webp',
        ...$attributes,
    ]);
}

it('renders the dropping areas route page successfully', function () {
    makeDroppingArea(['unit' => 'Room 211 Stall 4']);
    makeDroppingArea(['unit' => 'Room 203', 'started_on' => '2026-03-25', 'is_current' => true]);

    $this->get(route('archive.dropping-areas.static'))
        ->assertStatus(200)
        ->assertSee('droppingAreasRoute')
        ->assertSee('Room 211 Stall 4')
        ->assertSee('Current')
        ->assertSee('Decommissioned');
});

it('shows the empty state when there are no dropping areas', function () {
    $this->get(route('archive.dropping-areas.static'))
        ->assertStatus(200)
        ->assertSee('There are no dropping areas.');
});

it('shows the opening and closing hours as written', function () {
    makeDroppingArea([
        'opens_at' => 'Mondays - Saturdays 9:00 AM; Sundays 10:00 AM',
        'closes_at' => 'Mondays - Saturdays 5:00 PM; Sundays 3:00 PM',
    ]);

    $this->get(route('archive.dropping-areas.static'))
        ->assertSee('Mondays - Saturdays 9:00 AM; Sundays 10:00 AM')
        ->assertSee('Mondays - Saturdays 5:00 PM; Sundays 3:00 PM');
});

it('orders undated stations first, then by date, and numbers them from V1', function () {
    makeDroppingArea(['unit' => 'Newest', 'started_on' => '2026-03-25']);
    makeDroppingArea(['unit' => 'Oldest']);
    makeDroppingArea(['unit' => 'Dated', 'started_on' => '2025-08-29']);
    makeDroppingArea(['unit' => 'Second']);

    Livewire::test(StaticDroppingAreas::class)
        ->assertViewHas('stations', function (array $stations) {
            $stations = collect($stations);

            return $stations->pluck('unit')->all() === ['Oldest', 'Second', 'Dated', 'Newest']
                && $stations->pluck('tag')->all() === ['V1', 'V2', 'V3', 'V4'];
        });
});

it('marks each station as current from its own flag', function () {
    makeDroppingArea(['unit' => 'Room 209', 'started_on' => '2023-12-03']);
    makeDroppingArea(['unit' => 'Room 204', 'started_on' => '2025-08-29', 'is_current' => true]);
    makeDroppingArea(['unit' => 'Room 203', 'started_on' => '2026-03-25', 'is_current' => true]);

    Livewire::test(StaticDroppingAreas::class)
        ->assertViewHas('stations', fn (array $stations) => collect($stations)->where('is_current', true)->pluck('unit')->all() === ['Room 204', 'Room 203']);
});

it('marks a transfer only where a known address changes', function () {
    makeDroppingArea(['unit' => 'A1', 'address' => 'GP Arcade']);
    makeDroppingArea(['unit' => 'A2', 'address' => 'GP Arcade']);
    makeDroppingArea(['unit' => 'B1', 'address' => 'Olympian Lifestyle Center']);
    makeDroppingArea(['unit' => 'C1', 'address' => null]);
    makeDroppingArea(['unit' => 'D1', 'address' => 'Mabini Shopping Center']);

    Livewire::test(StaticDroppingAreas::class)
        ->assertViewHas('stations', fn (array $stations) => collect($stations)->where('is_transfer', true)->pluck('unit')->all() === ['B1']);
});

it('lists dropping areas in the admin panel', function () {
    $this->actingAs(User::factory()->create());
    $dropping_area = makeDroppingArea(['unit' => 'Room 203']);

    Livewire::test(ListDroppingAreas::class)
        ->assertCanSeeTableRecords([$dropping_area]);
});

it('lists the newest dropping areas first in the admin panel', function () {
    $this->actingAs(User::factory()->create());
    $oldest = makeDroppingArea(['unit' => 'Room 209', 'started_on' => '2023-12-03']);
    $newest = makeDroppingArea(['unit' => 'Room 203', 'started_on' => '2026-03-25']);
    $middle = makeDroppingArea(['unit' => 'Room 204', 'started_on' => '2025-08-29']);

    Livewire::test(ListDroppingAreas::class)
        ->assertCanSeeTableRecords([$newest, $middle, $oldest], inOrder: true);
});

it('creates a dropping area with an uploaded image from the admin panel', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateDroppingArea::class)
        ->fillForm([
            'address' => 'Mabini Shopping Center',
            'unit' => 'Room 203',
            'opens_at' => 'Mondays - Fridays 9:00 AM',
            'closes_at' => 'Mondays - Fridays 6:00 PM',
            'started_on' => '2026-03-25',
            'note' => 'Near the stairs',
            'is_current' => true,
            'image_path' => UploadedFile::fake()->image('room-203.webp'),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $dropping_area = DroppingArea::sole();

    expect($dropping_area->unit)->toBe('Room 203')
        ->and($dropping_area->opens_at)->toBe('Mondays - Fridays 9:00 AM')
        ->and($dropping_area->is_current)->toBeTrue()
        ->and($dropping_area->image_path)->toStartWith('dropping-areas/');

    Storage::disk('public')->assertExists($dropping_area->image_path);
});
