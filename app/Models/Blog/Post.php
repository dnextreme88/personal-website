<?php

namespace App\Models\Blog;

use App\Models\Blog\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'description',
        'date_published',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function (self $post) {
            $post->slug = Str::slug($post->title, '-');
        });

        static::updating(function (self $post) {
            $post->slug = Str::slug($post->title, '-');
        });
    }
}
