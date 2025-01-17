<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethods: string implements HasLabel
{
    case CASH_ON_HAND = 'cash on-hand';
    case DROPPING_AREA_CASHOUT = 'dropping area cashout';
    case REMITTANCE = 'remittance';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CASH_ON_HAND => 'cash on-hand',
            self::DROPPING_AREA_CASHOUT => 'dropping area cashout',
            self::REMITTANCE => 'remittance',
        };
    }
}
