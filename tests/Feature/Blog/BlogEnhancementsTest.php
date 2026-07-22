<?php

use App\Livewire\Blog\DetailPost;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makePost(array $overrides = []): Post
{
    $user = User::factory()->create();
    $category = Category::firstOrCreate(['name' => 'General']);

    return Post::create(array_merge([
        'category_id' => $category->id,
        'user_id' => $user->id,
        'title' => 'Post '.Str::random(10),
        'description' => '<p>Some content here.</p>',
        'date_published' => now()->subDay()->format('Y-m-d'),
        'is_published' => true,
    ], $overrides));
}

// --- Draft / Publish scope ------------------------------------------------

it('published scope includes only live posts', function () {
    $live = makePost();
    $draft = makePost(['is_published' => false]);
    $future = makePost(['date_published' => now()->addWeek()->format('Y-m-d')]);

    $ids = Post::published()->pluck('id');

    expect($ids)->toContain($live->id)
        ->not->toContain($draft->id)
        ->not->toContain($future->id);
});

// --- DetailPost visibility ------------------------------------------------

it('shows a published post detail page', function () {
    $post = makePost();

    $this->get(route('blog.post.detail', ['id' => $post->id, 'slug' => $post->slug]))
        ->assertOk()
        ->assertSee($post->title);
});

it('returns 404 for a draft post', function () {
    $post = makePost(['is_published' => false]);

    $this->get(route('blog.post.detail', ['id' => $post->id, 'slug' => $post->slug]))
        ->assertNotFound();
});

it('returns 404 for a future-dated post', function () {
    $post = makePost(['date_published' => now()->addWeek()->format('Y-m-d')]);

    $this->get(route('blog.post.detail', ['id' => $post->id, 'slug' => $post->slug]))
        ->assertNotFound();
});

// --- Reading time ---------------------------------------------------------

it('reads short posts as a one minute read', function () {
    $post = makePost(['description' => '<p>Just a few words.</p>']);

    expect($post->reading_time)->toBe(1);
});

it('scales reading time by word count', function () {
    $post = makePost(['description' => '<p>'.str_repeat('word ', 450).'</p>']);

    expect($post->reading_time)->toBe(3); // ceil(450 / 200)
});

// --- Related posts (bidirectional JSON pivot) -----------------------------

it('syncs related posts on both sides', function () {
    $a = makePost();
    $b = makePost();

    $a->syncRelatedPosts([$b->id]);

    expect($a->fresh()->related_post_ids)->toEqual([$b->id]);
    expect($b->fresh()->related_post_ids)->toEqual([$a->id]);
});

it('ignores self references when syncing', function () {
    $a = makePost();
    $b = makePost();

    $a->syncRelatedPosts([$a->id, $b->id]);

    expect($a->fresh()->related_post_ids)->toEqual([$b->id]);
});

it('removes the relation from both sides', function () {
    $a = makePost();
    $b = makePost();

    $a->syncRelatedPosts([$b->id]);
    $a->syncRelatedPosts([]);

    expect($a->fresh()->related_post_ids)->toEqual([]);
    expect($b->fresh()->related_post_ids)->toEqual([]);
});

it('strips references when a related post is deleted', function () {
    $a = makePost();
    $b = makePost();

    $a->syncRelatedPosts([$b->id]);
    $b->delete();

    expect($a->fresh()->related_post_ids)->toEqual([]);
});

it('only lists published related posts on the detail page', function () {
    $post = makePost();
    $publishedRelated = makePost();
    $draftRelated = makePost(['is_published' => false]);

    $post->syncRelatedPosts([$publishedRelated->id, $draftRelated->id]);

    Livewire::test(DetailPost::class, ['id' => $post->id, 'slug' => $post->slug])
        ->assertViewHas('related', fn ($related) => $related->pluck('id')->all() === [$publishedRelated->id]);
});

// --- Previous / Next navigation -------------------------------------------

it('resolves chronological neighbours by published date', function () {
    $older = makePost(['date_published' => '2026-01-01']);
    $middle = makePost(['date_published' => '2026-02-01']);
    $newer = makePost(['date_published' => '2026-03-01']);

    Livewire::test(DetailPost::class, ['id' => $middle->id, 'slug' => $middle->slug])
        ->assertViewHas('previous', fn ($previous) => $previous?->id === $older->id)
        ->assertViewHas('next', fn ($next) => $next?->id === $newer->id);
});

it('breaks same-date ties by id', function () {
    $low = makePost(['date_published' => '2026-01-01']);
    $high = makePost(['date_published' => '2026-01-01']);

    Livewire::test(DetailPost::class, ['id' => $low->id, 'slug' => $low->slug])
        ->assertViewHas('next', fn ($next) => $next?->id === $high->id)
        ->assertViewHas('previous', fn ($previous) => $previous === null);

    Livewire::test(DetailPost::class, ['id' => $high->id, 'slug' => $high->slug])
        ->assertViewHas('previous', fn ($previous) => $previous?->id === $low->id)
        ->assertViewHas('next', fn ($next) => $next === null);
});

it('has no neighbours at the ends', function () {
    $only = makePost();

    Livewire::test(DetailPost::class, ['id' => $only->id, 'slug' => $only->slug])
        ->assertViewHas('previous', fn ($previous) => $previous === null)
        ->assertViewHas('next', fn ($next) => $next === null);
});

it('skips draft and future posts as neighbours', function () {
    $current = makePost(['date_published' => '2026-02-01']);
    makePost(['date_published' => '2026-01-01', 'is_published' => false]); // older draft
    makePost(['date_published' => now()->addWeek()->format('Y-m-d')]); // future publish

    Livewire::test(DetailPost::class, ['id' => $current->id, 'slug' => $current->slug])
        ->assertViewHas('previous', fn ($previous) => $previous === null)
        ->assertViewHas('next', fn ($next) => $next === null);
});
