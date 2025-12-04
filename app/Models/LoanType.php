<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
    protected $fillable = [
        'loan_name',
        'status'
    ];

    public function loanNames()
    {
        return $this->hasMany(LoanName::class);
    }
}
