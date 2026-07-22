<?php

namespace App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'is_published',
    ];
    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function relation(): HasOne
    {
        return $this->hasOne(PostRelation::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereDate('date_published', '<=', now());
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->description));

        return max(1, (int) ceil($words / 200)); // ~200 words per minute
    }

    public function getRelatedPostIdsAttribute(): array
    {
        return $this->relation?->related_post_ids ?? [];
    }

    /**
     * Persist this post's curated related posts, keeping the relationship
     * bidirectional (adding B to A also adds A to B, and removals mirror).
     */
    public function syncRelatedPosts(array $ids): void
    {
        $ids = collect($ids)
            ->map(fn ($id) => (int) $id)
            ->reject(fn ($id) => $id === $this->id)
            ->unique()
            ->values();

        $previous = collect($this->related_post_ids);

        PostRelation::updateOrCreate(
            ['post_id' => $this->id],
            ['related_post_ids' => $ids->all()],
        );

        foreach ($ids->diff($previous) as $related_id) {
            $this->pushRelatedId($related_id, $this->id);
        }

        foreach ($previous->diff($ids) as $related_id) {
            $this->pullRelatedId($related_id, $this->id);
        }

        $this->unsetRelation('relation');
    }

    private function pushRelatedId(int $post_id, int $id): void
    {
        $relation = PostRelation::firstOrNew(['post_id' => $post_id]);

        $relation->related_post_ids = collect($relation->related_post_ids ?? [])
            ->push($id)
            ->unique()
            ->values()
            ->all();

        $relation->save();
    }

    private function pullRelatedId(int $post_id, int $id): void
    {
        $relation = PostRelation::firstOrNew(['post_id' => $post_id]);

        $relation->related_post_ids = collect($relation->related_post_ids ?? [])
            ->reject(fn ($value) => (int) $value === $id)
            ->values()
            ->all();

        $relation->save();
    }

    protected static function booted()
    {
        static::creating(function (self $post) {
            $post->slug = Str::slug($post->title, '-');
        });

        static::updating(function (self $post) {
            $post->slug = Str::slug($post->title, '-');
        });

        static::deleting(function (self $post) {
            // Own row cascades via the FK; strip references from every other row.
            PostRelation::where('post_id', '!=', $post->id)->each(function (PostRelation $relation) use ($post) {
                if (in_array($post->id, $relation->related_post_ids ?? [], true)) {
                    $relation->related_post_ids = collect($relation->related_post_ids)
                        ->reject(fn ($value) => (int) $value === $post->id)
                        ->values()
                        ->all();

                    $relation->save();
                }
            });
        });
    }
}
