<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SellMethods: string implements HasLabel
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
}
