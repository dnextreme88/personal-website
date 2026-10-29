<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DroppingArea extends Model
{
    protected $fillable = [
        'address',
        'unit',
        'note',
        'opens_at',
        'closes_at',
        'started_on',
        'is_current',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'started_on' => 'date',
            'is_current' => 'boolean',
        ];
    }

    /**
     * Route order, oldest first: rows with no start date come first (in id order),
     * then the dated rows by date.
     */
    public function scopeRouteOrder(Builder $query): Builder
    {
        return $query->orderByRaw('started_on IS NULL DESC')
            ->orderBy('started_on')
            ->orderBy('id');
    }
}
