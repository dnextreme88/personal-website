<?php

use App\Livewire\Archive\ListSteamAchievement;
use App\Models\SteamAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    SteamAchievement::insert([
        ['game_name' => 'Desolate', 'tags' => null, 'date_completed' => '2026-07-12', 'notes' => null],
        ['game_name' => 'Hitman Absolution', 'tags' => null, 'date_completed' => '2026-07-12', 'notes' => 'received as gift'],
    ]);
});

it('renders the steam achievements page successfully', function () {
    $this->get(route('archive.steam-achievements.list'))
        ->assertStatus(200)
        ->assertSee('Desolate')
        ->assertSee('Hitman Absolution');
});

it('lists every seeded game in the component', function () {
    Livewire::test(ListSteamAchievement::class)
        ->assertViewHas('steam_achievements', fn ($achievements) => $achievements->count() === 2)
        ->assertSee('Desolate')
        ->assertSee('Hitman Absolution');
});
