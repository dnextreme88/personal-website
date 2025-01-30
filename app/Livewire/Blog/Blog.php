<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Blog extends Component
{
    public Collection $posts;

    public function render()
    {
        $this->posts = Post::latest('date_published')->get();

        return view('livewire.blog.blog');
    }
}
