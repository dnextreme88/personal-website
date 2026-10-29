<?php

namespace App\Livewire\Archive;

use DateTime;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class StaticDroppingAreas extends Component
{
    private const DROPPING_AREAS_PATH = 'images/dropping-areas';

    /**
     * Read the station data out of a dropping area filename, eg.
     * "DA V6 - Mabini Shopping Center Room 204 9 - 7 PM (aug 29 2025).webp". Values that
     * the filename does not have are null.
     */
    public static function parseFilename(string $filename): array
    {
        $data = [
            'version' => null,
            'building' => null,
            'unit' => null,
            'hours' => null,
            'date' => null,
            'note' => null,
        ];

        $pattern = '/^DA (V\d+) - (.+?) (Room [\w-]+(?: Stall \d+)?)(?: Dropping Area)?(?: (\d{1,2}) - (\d{1,2}) ([AP]M))?(?: \(([^)]+)\))?\.webp$/i';

        if (!preg_match($pattern, $filename, $matches)) {
            return $data;
        }

        $data['version'] = strtoupper($matches[1]);
        $data['building'] = $matches[2];
        $data['unit'] = $matches[3];

        if (!empty($matches[4])) {
            // "9 - 5 PM" means 9 AM to 5 PM
            $data['hours'] = $matches[4].' AM - '.$matches[5].' '.strtoupper($matches[6]);
        }

        if (!empty($matches[7])) {
            $date = DateTime::createFromFormat('!M j Y', $matches[7]);

            // The format check rejects overflow dates such as "feb 31 2025"
            if ($date && strtolower($date->format('M j Y')) === strtolower($matches[7])) {
                $data['date'] = $date->format('Y-m-d');
            } else {
                $data['note'] = ucfirst($matches[7]);
            }
        }

        return $data;
    }

    public function render()
    {
        $path = public_path(self::DROPPING_AREAS_PATH);

        $stations = collect(File::exists($path) ? File::allFiles($path) : [])
            ->filter(fn ($file) => $file->getExtension() === 'webp')
            ->map(fn ($file) => [
                'filename' => $file->getFilename(),
                // eg. http://example.com/images/dropping-areas/my-dropping-area.webp
                'url' => asset(self::DROPPING_AREAS_PATH.'/'.str_replace('\\', '/', $file->getRelativePathname())),
                ...self::parseFilename($file->getFilename()),
            ])
            ->sortBy([
                fn ($a, $b) => (int) substr($a['version'] ?? 'V0', 1) <=> (int) substr($b['version'] ?? 'V0', 1),
                fn ($a, $b) => $a['filename'] <=> $b['filename'],
            ])
            ->values();

        // Two photos with the same version get a letter, eg. V2·A and V2·B
        $version_counts = $stations->countBy('version');
        $version_seen = [];

        $stations = $stations->map(function ($station, $i) use ($stations, $version_counts, &$version_seen) {
            $version = $station['version'] ?? 'V?';
            $tag = $version;

            if (($version_counts[$station['version']] ?? 0) > 1) {
                $version_seen[$version] = ($version_seen[$version] ?? 0) + 1;
                $tag .= '·'.chr(64 + $version_seen[$version]);
            }

            return [
                ...$station,
                'tag' => $tag,
                'is_current' => $i === $stations->count() - 1,
                'is_transfer' => $i > 0 && $stations[$i - 1]['building'] !== $station['building'],
            ];
        });

        return view('livewire.archive.static-dropping-areas', [
            'stations' => $stations->all(),
        ]);
    }
}
