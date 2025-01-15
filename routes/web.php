<?php

use App\Livewire\Archives;
use App\Livewire\Homepage;
use Illuminate\Support\Facades\Route;

Route::get('/', Homepage::class)->name('home');
Route::get('/archives', Archives::class)->name('archives');
