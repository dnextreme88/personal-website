<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Post;
use Livewire\Component;

class DetailPost extends Component
{
    public int $id;
    public string $slug;
    public Post $post;

    public function mount(int $id, string $slug)
    {
        $this->id = $id;
        $this->slug = $slug;
    }

    public function render()
    {
        $this->post = Post::published()->where('id', $this->id)->where('slug', $this->slug)
            ->firstOrFail();

        $related = Post::published()->whereIn('id', $this->post->related_post_ids)
            ->orderBy('title')
            ->get();

        $previous = Post::published()
            ->where(fn ($query) => $query
                ->where('date_published', '<', $this->post->date_published)
                ->orWhere(fn ($tie) => $tie
                    ->where('date_published', $this->post->date_published)
                    ->where('id', '<', $this->post->id)))
            ->orderBy('date_published', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        $next = Post::published()
            ->where(fn ($query) => $query
                ->where('date_published', '>', $this->post->date_published)
                ->orWhere(fn ($tie) => $tie
                    ->where('date_published', $this->post->date_published)
                    ->where('id', '>', $this->post->id)))
            ->orderBy('date_published', 'ASC')
            ->orderBy('id', 'ASC')
            ->first();

        return view('livewire.blog.detail-post', [
            'related' => $related,
            'previous' => $previous,
            'next' => $next,
        ]);
    }
}
