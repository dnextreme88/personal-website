<?php

use App\Livewire\Archive\StaticGameScreenshots;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;

it('renders the game screenshots filmstrip page successfully', function () {
    $this->get(route('archive.game-screenshots.static'))
        ->assertStatus(200)
        ->assertSee('gameScreenshotsGallery')
        ->assertSee('Previous screenshot')
        ->assertSee('Next screenshot');
});

it('reads the capture date from a screenshot filename', function (string $filename, ?string $expected) {
    expect(StaticGameScreenshots::dateFromFilename($filename))->toBe($expected);
})->with([
    'crossfire prefix' => ['Crossfire20241115_0000.webp', '2024-11-15'],
    'leading date' => ['20220117-pentakill.webp', '2022-01-17'],
    'no date' => ['riot-lol-1st-tft-game.webp', null],
    'impossible date' => ['Crossfire20241345_0000.webp', null],
]);

it('passes one entry per webp file, sorted, without duplicating on re-render', function () {
    $webp_count = collect(File::allFiles(public_path('images/game-screenshots')))
        ->filter(fn ($file) => $file->getExtension() === 'webp')
        ->count();

    $component = Livewire::test(StaticGameScreenshots::class);

    $component->assertViewHas('screenshots', function (array $screenshots) use ($webp_count) {
        $order = collect($screenshots)->map(fn ($s) => $s['game'].'/'.$s['filename'])->all();
        $sorted = $order;
        sort($sorted);

        return count($screenshots) === $webp_count
            && $order === $sorted
            && collect($screenshots)->every(fn ($s) => array_keys($s) === ['filename', 'url', 'game', 'game_label', 'date']);
    });

    // A second render must not append to the list again
    $component->call('$refresh')
        ->assertViewHas('screenshots', fn (array $screenshots) => count($screenshots) === $webp_count);
});
