<?php

namespace App\Models\Blog;

use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }

    protected static function booted()
    {
        static::creating(function (self $category) {
            $category->slug = Str::slug($category->name, '-');
        });

        static::updating(function (self $category) {
            $category->slug = Str::slug($category->name, '-');
        });
    }
}
