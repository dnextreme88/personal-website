<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ListPost extends Component
{
    use WithPagination;

    public ?Category $category = null;
    public $date_published;
    public ?string $search_query = null;
    public bool $is_filtered = false;

    protected $listeners = ['showed-posts-by-date' => 'dispatch_show_posts_by_date'];

    public function dispatch_show_posts_by_date($data) // Dispatched from another component and to be shown on this component
    {
        $this->date_published = $data['date_published'];
    }

    public function search_posts()
    {
        $this->is_filtered = true;

        $this->resetPage(); // Reset pagination after filtering data

        $this->dispatch('filtered-posts');
    }

    public function mount($category = null, $date_published = null)
    {
        $this->category = $category;
        $this->date_published = $date_published;
    }

    #[On('filtered-posts')]
    public function render()
    {
        $this->search_query = trim($this->search_query);

        $posts = Post::query();

        if ($this->date_published) {
            $posts = $posts->where('date_published', $this->date_published);
        }

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
