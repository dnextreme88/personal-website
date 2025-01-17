<?php

namespace App\Models\Traits;

trait ArchiveTrait
{
    // Prefixing functions with scope allow you to chain query constraints,
    // which is useful for code readability and the DRY principle
    // Sample usage: $cash_on_hands = PayMethod::getMethod('cash on-hand')->get();
    protected function scopeGetMethod($query, $method_name)
    {
        return $query->where('method', $method_name);
    }
}
