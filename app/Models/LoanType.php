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

     public function getIsUsedAttribute()
    {
        return $this->loanNames()->exists(); // true if any loan names exist
    }

     public function getUsedCountAttribute()
    {
        return $this->loanNames()->count();
    }
}
