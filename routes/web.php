<?php

use App\Livewire\AboutMe;
use App\Livewire\Archives;
use App\Livewire\Blog\Blog;
use App\Livewire\Blog\DetailPost;
use App\Livewire\Blog\ListCategoryPost;
use App\Livewire\CreateContactMe;
use App\Livewire\Homepage;
use Illuminate\Support\Facades\Route;

Route::get('/', Homepage::class)->name('home');
Route::get('/about-me', AboutMe::class)->name('about_me');
Route::get('/archives', Archives::class)->name('archives');

Route::group(['prefix' => 'blog', 'as' => 'blog.'], function() {
    Route::get('/', Blog::class)->name('index');
    Route::get('/{id}-{slug}', DetailPost::class)->name('post.detail');
    Route::get('/categories/{slug}/posts', ListCategoryPost::class)->name('categories.post.list');
});

Route::get('/contact', CreateContactMe::class)->name('contact-me');
