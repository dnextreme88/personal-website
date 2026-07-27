<?php

namespace App\Livewire\Archive;

use Illuminate\Support\Facades\File;
use Livewire\Component;

class StaticDroppingAreas extends Component
{
    public array $dropping_areas = [];

    public function render()
    {
        $dropping_areas_path = 'images/dropping-areas';
        $path = public_path($dropping_areas_path);
        $images = collect(File::allFiles($path))->filter(fn ($file) => in_array($file->getExtension(), ['webp'])
        );

        foreach ($images as $image) {
            $this->dropping_areas[] = [
                'filename' => $image->getFilename(),
                // eg. http://example.com/images/dropping-areas/sub-directory/my-screenshot.webp
                'url' => asset($dropping_areas_path. '/' .str_replace('\\', '/', $image->getRelativePathname()))
            ];
        }

        return view('livewire.archive.static-dropping-areas');
    }
}
