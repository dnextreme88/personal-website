<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentMethods: string implements HasColor, HasLabel
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

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::CASH_ON_HAND => 'info',
            self::DROPPING_AREA_CASHOUT => 'danger',
            self::REMITTANCE => 'success',
        };
    }
}
