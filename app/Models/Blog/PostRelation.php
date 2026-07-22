<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostRelation extends Model
{
    protected $fillable = [
        'post_id',
        'related_post_ids',
    ];
    protected $casts = [
        'related_post_ids' => 'array',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
