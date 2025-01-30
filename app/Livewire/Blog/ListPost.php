<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Post;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ListPost extends Component
{
    use WithPagination;

    public $category;
    public ?string $search_query = null;
    public bool $is_filtered = false;

    public function search_posts()
    {
        $this->is_filtered = true;

        $this->resetPage(); // Reset pagination after filtering data

        $this->dispatch('filtered-posts');
    }

    public function mount($category = false)
    {
        $this->category = $category;
    }

    #[On('filtered-posts')]
    public function render()
    {
        $this->search_query = trim($this->search_query);

        $posts = Post::query();

        if ($this->category) {
            $posts = $posts->whereHas('category', fn ($query) => $query->where('name', $this->category->name));
        } else {
            $posts = $posts->latest('date_published');
        }

        if ($this->is_filtered && $this->search_query) {
            $posts = $posts->where(fn ($query) => $query->whereLike('title', '%' .$this->search_query. '%')->orWhereLike('description', '%' .$this->search_query. '%'));
        }

        $posts = $posts->paginate(8);

        return view('livewire.blog.list-post', ['posts' => $posts]);
    }
}
