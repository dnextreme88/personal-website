<?php

namespace App\Livewire\Archive;

use Illuminate\Support\Facades\File;
use Livewire\Component;

class StaticGameScreenshots extends Component
{
    private const SCREENSHOTS_PATH = 'images/game-screenshots';

    /**
     * Read the first YYYYMMDD run in a screenshot filename (e.g. Crossfire20241115_0000.webp
     * or 20220117-pentakill.webp) as a date. Returns null when there is none or it is not a real date.
     */
    public static function dateFromFilename(string $filename): ?string
    {
        if (!preg_match('/(20\d{2})(\d{2})(\d{2})/', $filename, $matches)) {
            return null;
        }

        [, $year, $month, $day] = $matches;

        return checkdate((int) $month, (int) $day, (int) $year) ? "{$year}-{$month}-{$day}" : null;
    }

    public function render()
    {
        // Built per render (not kept on a public property) so a re-render never duplicates the list
        $screenshots = collect(File::allFiles(public_path(self::SCREENSHOTS_PATH)))
            ->filter(fn ($file) => $file->getExtension() === 'webp')
            ->map(function ($file) {
                $relative_path = str_replace('\\', '/', $file->getRelativePathname());
                $game = str_contains($relative_path, '/') ? explode('/', $relative_path)[0] : 'misc';

                return [
                    'filename' => $file->getFilename(),
                    // eg. http://example.com/images/game-screenshots/sub-directory/my-screenshot.webp
                    'url' => asset(self::SCREENSHOTS_PATH.'/'.$relative_path),
                    'game' => $game,
                    'game_label' => strtoupper($game),
                    'date' => self::dateFromFilename($file->getFilename()),
                ];
            })
            ->sortBy([['game', 'asc'], ['filename', 'asc']])
            ->values()
            ->all();

        return view('livewire.archive.static-game-screenshots', [
            'screenshots' => $screenshots,
        ]);
    }
}
