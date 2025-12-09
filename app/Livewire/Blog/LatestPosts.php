<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class LatestPosts extends Component
{
    public Collection $latest_posts;

    public function render()
    {
        $this->latest_posts = Post::latest('date_published')->take(3)
            ->get();

        return view('livewire.blog.latest-posts');
    }
}
