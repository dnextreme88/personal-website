<?php

namespace Database\Seeders;

use App\Models\DroppingArea;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DroppingAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dropping_areas = [
            [
                'address' => 'Lower Mabini St. GP Arcade Building, Benguet Baguio 2600',
                'image' => 'DA V1 - GP Arcade Room 211 Stall 4.webp',
                'unit' => 'Room 211 Stall 4',
                'note' => 'First dropping area',
                'opens_at' => 'Mondays - Saturdays 9:00 AM; Sundays 10:00 AM',
                'closes_at' => 'Mondays - Saturdays 5:00 PM; Sundays 3:00 PM',
                'started_on' => '2019-04-22',
                'is_current' => false,
            ],
            [
                'address' => 'Lower Mabini St. GP Arcade Building, Benguet Baguio 2600',
                'image' => 'DA V2 - GP Arcade Room 211 Stall 6 (original stall).webp',
                'unit' => 'Room 211 Stall 6',
                'note' => 'Got transferred',
                'opens_at' => 'Mondays - Saturdays 9:00 AM; Sundays 10:00 AM',
                'closes_at' => 'Mondays - Saturdays 5:00 PM; Sundays 3:00 PM',
                'started_on' => '2021-08-23',
                'is_current' => false,
            ],
            [
                'address' => 'Lower Mabini St. Olympian Lifestyle Center, Benguet Baguio 2600',
                'image' => 'DA V2 - Olympian Lifestyle Center Room K-01 9 - 5 PM.webp',
                'unit' => 'Lower Ground Floor Room K-01',
                'note' => 'Got transferred again from the old Room 211 Stall 6 in GP Arcade Building',
                'opens_at' => 'Mondays - Saturdays 8:30 AM; Sundays 10:00 AM',
                'closes_at' => 'Mondays - Saturdays 6:00 PM; Sundays 4:00 PM',
                'started_on' => '2022-04-07',
                'is_current' => false,
            ],
            [
                'address' => 'Lower Mabini St. Olympian Lifestyle Center, Benguet Baguio 2600',
                'image' => 'DA V3 - Olympian Lifestyle Center Room K-01 Dropping Area 9 - 5 PM.webp',
                'unit' => 'Lower Ground Floor Room 211',
                'note' => 'Dropping area was renamed but on the same unit',
                'opens_at' => 'Mondays - Saturdays 8:30 AM; Sundays 10:00 AM',
                'closes_at' => 'Mondays - Saturdays 6:00 PM; Sundays 4:00 PM',
                'started_on' => '2023-02-05',
                'is_current' => false,
            ],
            [
                'address' => 'Lower Mabini St. Mabini Shopping Center, Benguet Baguio 2600',
                'image' => 'DA V4 - Mabini Shopping Center Room 212 9 - 5 PM.webp',
                'unit' => 'Room 212',
                'note' => 'Was registered on the dropping area late in the afternoon the same day I dropped an item on Room 211 in Olympian Lifestyle Center building. First sold item on this DA was on June 5, 2023',
                'opens_at' => 'Mondays - Saturdays 9:00 AM; Sundays 10:00 AM',
                'closes_at' => 'Mondays - Saturdays 6:00 PM; Sundays 5:00 PM',
                'started_on' => '2023-02-05',
                'is_current' => false,
            ],
            [
                'address' => 'Lower Mabini St. Mabini Shopping Center, Benguet Baguio 2600',
                'image' => 'DA V5 - Mabini Shopping Center Room 209 9 - 6 PM.webp',
                'unit' => 'Room 209',
                'note' => 'Decided to find one',
                'opens_at' => 'Mondays - Sundays 9:00 AM',
                'closes_at' => 'Mondays - Sundays 6:00 PM',
                'started_on' => '2023-12-03',
                'is_current' => false,
            ],
            [
                'address' => 'Lower Mabini St. Mabini Shopping Center, Benguet Baguio 2600',
                'image' => 'DA V6 - Mabini Shopping Center Room 204 8 - 6 PM (aug 29 2025).webp',
                'unit' => 'Room 204',
                'note' => 'Previous dropping area shifted business priorities',
                'opens_at' => 'Mondays - Saturdays 8:00 AM',
                'closes_at' => 'Mondays - Saturdays 6:00 PM',
                'started_on' => '2025-08-29',
                'is_current' => true,
            ],
            [
                'address' => 'Lower Mabini St. Mabini Shopping Center, Benguet Baguio 2600',
                'image' => 'DA V7 - Mabini Shopping Center Room 203 9 - 6 PM (mar 25 2026).webp',
                'unit' => 'Room 203',
                'note' => 'Wanted to have a dropping area that is open on Sundays',
                'opens_at' => 'Mondays - Fridays 9:00 AM; Saturdays 10:30 AM; Sundays 1:00 PM',
                'closes_at' => 'Mondays - Fridays 6:00 PM; Saturdays 6:30 PM; Sundays 6:30 PM',
                'started_on' => '2026-03-25',
                'is_current' => true,
            ],
        ];

        foreach ($dropping_areas as $index => $dropping_area) {
            $source = public_path('images/dropping-areas/' .$dropping_area['image']);
            $image_path = 'dropping-areas/dropping-area-' .($index + 1). '.webp';

            if (file_exists($source)) {
                Storage::disk('public')->put($image_path, file_get_contents($source));
            }

            DroppingArea::create([
                'address' => null,
                'unit' => $dropping_area['unit'],
                'note' => $dropping_area['note'],
                'opens_at' => $dropping_area['opens_at'],
                'closes_at' => $dropping_area['closes_at'],
                'started_on' => $dropping_area['started_on'] ? Carbon::parse($dropping_area['started_on']) : null,
                'is_current' => $dropping_area['is_current'],
                'image_path' => $image_path,
            ]);
        }
    }
}
