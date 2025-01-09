<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Conditions: string implements HasColor, HasLabel
{
    case HEALTHY = 'healthy';
    case NEW = 'new';
    case USED = 'used';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::HEALTHY => 'healthy',
            self::NEW => 'new',
            self::USED => 'used',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::HEALTHY => 'info',
            self::NEW => 'success',
            self::USED => 'warning',
        };
    }

    public static function textColor(DocumentTraineesStatus $label): ?string
    {
        return match ($label) {
            self::HEALTHY => 'text-blue-500 dark:text-blue-300',
            self::NEW => 'text-green-500 dark:text-green-300',
            self::USED => 'text-orange-500 dark:text-orange-300',
        };
    }
}
