<?php

use App\Livewire\Archive\StaticDroppingAreas;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;

it('renders the dropping areas route page successfully', function () {
    $this->get(route('archive.dropping-areas.static'))
        ->assertStatus(200)
        ->assertSee('droppingAreasRoute')
        ->assertSee('Current')
        ->assertSee('Decommissioned');
});

it('reads the station data from a dropping area filename', function (string $filename, array $expected) {
    expect(StaticDroppingAreas::parseFilename($filename))->toBe($expected);
})->with([
    'stall, no hours' => ['DA V1 - GP Arcade Room 211 Stall 4.webp', [
        'version' => 'V1', 'building' => 'GP Arcade', 'unit' => 'Room 211 Stall 4', 'hours' => null, 'date' => null, 'note' => null,
    ]],
    'note in brackets' => ['DA V2 - GP Arcade Room 211 Stall 6 (original stall).webp', [
        'version' => 'V2', 'building' => 'GP Arcade', 'unit' => 'Room 211 Stall 6', 'hours' => null, 'date' => null, 'note' => 'Original stall',
    ]],
    'hours' => ['DA V2 - Olympian Lifestyle Center Room K-01 9 - 5 PM.webp', [
        'version' => 'V2', 'building' => 'Olympian Lifestyle Center', 'unit' => 'Room K-01', 'hours' => '9 AM - 5 PM', 'date' => null, 'note' => null,
    ]],
    'dropping area suffix' => ['DA V3 - Olympian Lifestyle Center Room K-01 Dropping Area 9 - 5 PM.webp', [
        'version' => 'V3', 'building' => 'Olympian Lifestyle Center', 'unit' => 'Room K-01', 'hours' => '9 AM - 5 PM', 'date' => null, 'note' => null,
    ]],
    'date in brackets' => ['DA V6 - Mabini Shopping Center Room 204 9 - 7 PM (aug 29 2025).webp', [
        'version' => 'V6', 'building' => 'Mabini Shopping Center', 'unit' => 'Room 204', 'hours' => '9 AM - 7 PM', 'date' => '2025-08-29', 'note' => null,
    ]],
    'impossible date is a note' => ['DA V8 - Mabini Shopping Center Room 203 (feb 31 2026).webp', [
        'version' => 'V8', 'building' => 'Mabini Shopping Center', 'unit' => 'Room 203', 'hours' => null, 'date' => null, 'note' => 'Feb 31 2026',
    ]],
    'unknown format' => ['some-photo.webp', [
        'version' => null, 'building' => null, 'unit' => null, 'hours' => null, 'date' => null, 'note' => null,
    ]],
]);

it('passes one station per webp file in version order, without duplicating on re-render', function () {
    $webp_count = collect(File::allFiles(public_path('images/dropping-areas')))
        ->filter(fn ($file) => $file->getExtension() === 'webp')
        ->count();

    $component = Livewire::test(StaticDroppingAreas::class);

    $component->assertViewHas('stations', function (array $stations) use ($webp_count) {
        $stations = collect($stations);
        $versions = $stations->map(fn ($s) => (int) substr($s['version'], 1))->all();
        $sorted = $versions;
        sort($sorted);

        return $stations->count() === $webp_count
            && $versions === $sorted
            // Only the newest station is current
            && $stations->where('is_current', true)->keys()->all() === [$webp_count - 1];
    });

    // A second render must not append to the list again
    $component->call('$refresh')
        ->assertViewHas('stations', fn (array $stations) => count($stations) === $webp_count);
});

it('marks a transfer where the building changes and tags shared versions', function () {
    Livewire::test(StaticDroppingAreas::class)
        ->assertViewHas('stations', function (array $stations) {
            $stations = collect($stations);

            return $stations->where('is_transfer', true)->pluck('tag')->all() === ['V2·B', 'V4']
                && $stations->pluck('tag')->take(3)->all() === ['V1', 'V2·A', 'V2·B'];
        });
});
