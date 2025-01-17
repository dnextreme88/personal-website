<?php

namespace App\Models;

use App\Models\Traits\ArchiveTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PayMethod extends Model
{
    use ArchiveTrait;

    protected $fillable = [
        'method',
        'remittance_location',
    ];

    public function sold_item(): HasOne
    {
        return $this->hasOne(SoldItem::class);
    }
}
