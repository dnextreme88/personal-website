<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SellMethods: string implements HasColor, HasLabel
{
    case DROPPING = 'dropping';
    case MEETUP = 'meetup';
    case SHIPMENT = 'shipment';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DROPPING => 'dropping',
            self::MEETUP => 'meetup',
            self::SHIPMENT => 'shipment',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DROPPING => 'danger',
            self::MEETUP => 'info',
            self::SHIPMENT => 'success',
        };
    }
}
