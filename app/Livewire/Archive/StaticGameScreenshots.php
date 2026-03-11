<?php

namespace App\Livewire\Archive;

use Illuminate\Support\Facades\File;
use Livewire\Component;

class StaticGameScreenshots extends Component
{
    public array $screenshots = [];

    public function render()
    {
        $game_screenshots_path = 'images/game-screenshots';
        $path = public_path($game_screenshots_path);
        $images = collect(File::allFiles($path))->filter(fn($file) =>
            in_array($file->getExtension(), ['webp'])
        );

        foreach ($images as $image) {
            $this->screenshots[] = [
                'filename' => $image->getFilename(),
                // eg. http://example.com/images/game-screenshots/sub-directory/my-screenshot.webp
                'url' => asset($game_screenshots_path. '/' .str_replace('\\', '/', $image->getRelativePathname()))
            ];
        }

        return view('livewire.archive.static-game-screenshots');
    }
}
