<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ListCategoryPost extends Component
{
    public string $slug;
    public $date_published;
    public Collection $posts;
    public Category $category;

    public function mount(string $slug, $date_published = null)
    {
        $this->slug = $slug;
        $this->date_published = $date_published;
    }

    public function render()
    {
        $posts = Post::whereHas('category', fn ($query) => $query->where('slug', $this->slug));
        $this->category = Category::where('slug', $this->slug)->first();

        if ($this->date_published) {
            $posts = $posts->copy()->where('date_published', $this->date_published);
        }

        $this->posts = $posts->get();

        return view('livewire.blog.list-category-post');
    }
}
