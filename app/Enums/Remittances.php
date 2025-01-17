<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Remittances: string implements HasLabel
{
    case BUYERS_DROPPING_AREA = 'Buyer\'s Dropping Area';
    case CEBUANA = 'Cebuana';
    case GCASH = 'GCash';
    case J_AND_T_EXPRESS = 'J&T Express';
    case LBC = 'LBC';
    case M_LHUILLIER = 'M Lhuillier';
    case PALAWAN_EXPRESS = 'Palawan Express';
    case SMART_PADALA = 'Smart Padala';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BUYERS_DROPPING_AREA => 'Buyer\'s Dropping Area',
            self::CEBUANA => 'Cebuana',
            self::GCASH => 'GCash',
            self::J_AND_T_EXPRESS => 'J&T Express',
            self::LBC => 'LBC',
            self::M_LHUILLIER => 'M Lhuillier',
            self::PALAWAN_EXPRESS => 'Palawan Express',
            self::SMART_PADALA => 'Smart Padala',
        };
    }
}
