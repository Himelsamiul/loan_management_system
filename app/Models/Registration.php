<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Registration extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'sure_name', 'mobile', 'email', 'address', 'date_of_birth', 'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
