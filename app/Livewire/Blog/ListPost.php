<?php

namespace App\Livewire\Blog;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ListPost extends Component
{
    public Collection $posts;

    public function mount($posts)
    {
        $this->posts = $posts;
    }

    public function render()
    {
        return view('livewire.blog.list-post');
    }
}
