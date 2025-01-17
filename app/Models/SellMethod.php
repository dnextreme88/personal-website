<?php

namespace App\Models;

use App\Models\Traits\ArchiveTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SellMethod extends Model
{
    use ArchiveTrait;

    protected $fillable = [
        'method',
        'location',
    ];

    public function sold_item(): HasOne
    {
        return $this->hasOne(SoldItem::class);
    }
}
