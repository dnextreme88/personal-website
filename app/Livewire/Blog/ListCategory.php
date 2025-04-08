<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ListCategory extends Component
{
    public Collection $categories;

    public function render()
    {
        $this->categories = Category::withCount(['posts'])->orderBy('name', 'ASC')
            ->get();

        return view('livewire.blog.list-category');
    }
}
