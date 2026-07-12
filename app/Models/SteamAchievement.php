<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SteamAchievement extends Model
{
    protected $fillable = [
        'game_name',
        'tags',
        'date_completed',
        'notes',
    ];
}
