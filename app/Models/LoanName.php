<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanName extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_type_id',
        'loan_name',
        'interest',
        'status',
    ];

    // Relation: LoanName belongs to LoanType
    public function loanType()
    {
        return $this->belongsTo(LoanType::class);
    }

    // Scope to get only active loan names
    public function scopeActive($query)
    {
        return $query->where('status','active');
    }
}