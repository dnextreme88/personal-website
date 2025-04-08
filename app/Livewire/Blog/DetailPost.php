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
        $this->post = Post::where('id', $this->id)->where('slug', $this->slug)
            ->first();

        return view('livewire.blog.detail-post');
    }
}
