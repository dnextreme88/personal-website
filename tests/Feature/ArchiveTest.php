<?php

use App\Livewire\Archive\Archive;
use Livewire\Livewire;

it('renders the archive page successfully', function () {
    $this->get(route('archive.index'))
        ->assertStatus(200)
        ->assertSee('Sold Items')
        ->assertSee('Steam Achievements');
});

it('gives every archive card the reflection sheen', function () {
    $html = Livewire::test(Archive::class)->html();

    expect(substr_count($html, 'card-square-reflection card-square'))->toBe(4);
});
