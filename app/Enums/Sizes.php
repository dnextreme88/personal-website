<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Sizes: string implements HasLabel
{
    case NOT_APPLICABLE = 'N/A';
    case SMALL = 'S';
    case MEDIUM = 'M';
    case LARGE = 'L';
    case EXTRA_LARGE = 'XL';
    case EXTRA_EXTRA_LARGE = 'XXL';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NOT_APPLICABLE => 'N/A',
            self::SMALL => 'S',
            self::MEDIUM => 'M',
            self::LARGE => 'L',
            self::EXTRA_LARGE => 'XL',
            self::EXTRA_EXTRA_LARGE => 'XXL',
        };
    }
}
