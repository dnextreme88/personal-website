<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMe extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'company',
        'message',
    ];

    protected $table = 'contact_me';
}
