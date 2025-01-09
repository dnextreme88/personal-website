<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DroppingAreas: string implements HasLabel
{
    case GP_ARCADE_STALL_4 = 'GP Arcade Stall 4';
    case GP_ARCADE_STALL_6 = 'GP Arcade Stall 6';
    case MABINI_SHOPPING_CENTER_ROOM_209 = 'Mabini Shopping Center Room 209';
    case MABINI_SHOPPING_CENTER_ROOM_212 = 'Mabini Shopping Center Room 212';
    case OLYMPIAN_BUILDING_ROOM_211 = 'Olympian Building Room 211';
    case OLYMPIAN_BUILDING_ROOM_K_01 = 'Olympian Building Room K-01';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::GP_ARCADE_STALL_4 => 'GP Arcade Stall 4',
            self::GP_ARCADE_STALL_6 => 'GP Arcade Stall 6',
            self::MABINI_SHOPPING_CENTER_ROOM_209 => 'Mabini Shopping Center Room 209',
            self::MABINI_SHOPPING_CENTER_ROOM_212 => 'Mabini Shopping Center Room 212',
            self::OLYMPIAN_BUILDING_ROOM_211 => 'Olympian Building Room 211',
            self::OLYMPIAN_BUILDING_ROOM_K_01 => 'Olympian Building Room K-01',
        };
    }
}
