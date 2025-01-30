<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ListCategoryPost extends Component
{
    public string $slug;
    public Collection $posts;
    public Category $category;

    public function mount(string $slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $this->posts = Post::whereHas('category', fn ($query) => $query->where('slug', $this->slug))->get();
        $this->category = Category::where('slug', $this->slug)->first();

        return view('livewire.blog.list-category-post');
    }
}
