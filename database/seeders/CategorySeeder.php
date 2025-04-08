<?php

namespace Database\Seeders;

use App\Models\Blog\Category;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Daily',
                'slug' => Str::slug('Daily', '-'),
                'description' => 'This category is different from a Journal Entry, as posts here are not written first-hand on a clean sheet of paper.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Journal Entry',
                'slug' => Str::slug('Journal Entry', '-'),
                'description' => 'Posts derived from my journal notebook.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Poems',
                'slug' => Str::slug('Poems', '-'),
                'description' => 'List of poems I personally wrote.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Poems (Miscellaneous)',
                'slug' => Str::slug('Poems (Miscellaneous)', '-'),
                'description' => 'List of poems not covered in other categories. These poems are usually not made by me.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'TV Marathons',
                'slug' => Str::slug('TV Marathons', '-'),
                'description' => 'Tracks how many episodes I have watched on specific dates.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Uncategorized',
                'slug' => Str::slug('Uncategorized', '-'),
                'description' => 'Posts that cannot be categorized anywhere else.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Unholy Confessions',
                'slug' => Str::slug('Unholy Confessions', '-'),
                'description' => 'Posts leaning into deeper topics of my human mind.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
