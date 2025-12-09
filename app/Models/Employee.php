<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_card_number',
        'name',
        'designation',
        'role',
        'phone',
        'address',
        'status'
    ];
}
