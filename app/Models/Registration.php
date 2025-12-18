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

    /**
     * Get all loan applications for this user
     */
    public function applies()
    {
        // 'user_id' is the foreign key in your applies table
        return $this->hasMany(\App\Models\Apply::class, 'user_id', 'id');
    }
}
