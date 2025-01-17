<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoldItem extends Model
{
    protected $fillable = [
        'pay_method_id',
        'sell_method_id',
        'brand',
        'name',
        'type',
        'price',
        'condition',
        'size',
        'date_sold',
        'tags',
        'notes',
        'image_location',
    ];
    protected $appends = [
        'item_name',
        'pay_method_name',
        'pay_method_location',
        'sell_method_name',
        'sell_method_location',
    ];

    /* Functions prefixed with get are used in Filament admin panel */
    public function getPayMethodNameAttribute(): string
    {
        return $this->pay_method->method;
    }

    public function getPayMethodLocationAttribute(): string
    {
        return $this->pay_method->remittance_location;
    }

    public function getSellMethodNameAttribute(): string
    {
        return $this->sell_method->method;
    }

    public function getSellMethodLocationAttribute(): string
    {
        return $this->sell_method->location;
    }
    /* ----- */

    protected function getItemNameAttribute(): string
    {
        return $this->brand. ' ' .($this->name ?? ''). ' ' .$this->type;
    }

    protected function size(): Attribute
    {
        return Attribute::make(set: fn (string $value) => strtoupper($value));
    }

    protected function tags(): Attribute
    {
        return Attribute::make(set: fn (string $value) => strtolower($value));
    }

    public function pay_method(): BelongsTo
    {
        return $this->belongsTo(PayMethod::class);
    }

    public function sell_method(): BelongsTo
    {
        return $this->belongsTo(SellMethod::class);
    }
}
