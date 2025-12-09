<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // for login
use Illuminate\Notifications\Notifiable;

class Role extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'gmail',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    // Relation to Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
