<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ShipmentLocations: string implements HasLabel
{
    case ABEST_EXPRESS = 'ABest Express';
    case J_AND_T_EXPRESS = 'J&T Express';
    case JRS = 'JRS';
    case LBC = 'LBC';
    case PARTAS_WAYBILL = 'Partas Waybill';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ABEST_EXPRESS => 'ABest Express',
            self::J_AND_T_EXPRESS => 'J&T Express',
            self::JRS => 'JRS',
            self::LBC => 'LBC',
            self::PARTAS_WAYBILL => 'Partas Waybill',
        };
    }
}
