<?php

use App\Livewire\Calendar;
use Livewire\Livewire;

it('marks the current day with the neon-magenta marching-ants border', function () {
    // The Calendar mounts on today, so the current-day cell is always rendered
    // in the shown month and must carry the marching-border SVG (which replaced
    // the old card-rectangle highlight), stroked in neon magenta.
    Livewire::test(Calendar::class)
        ->assertSeeHtml('class="marching-border fill-none stroke-neon-magenta stroke-[1.5]"')
        ->assertSeeHtml('pathLength="100"');
});
