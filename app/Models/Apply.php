<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_type_id',
        'loan_name_id',
        'name',
        'father_name',
        'mother_name',
        'nid_number',
        'date_of_birth',
        'gender',
        'marital_status',
        'permanent_address',
        'present_address',
        'loan_amount',
        'loan_duration',
        'document1',
        'document2',
        'document3',
        'document4',
        'document5',
        'status',
    ];

// Relationship with LoanType
    public function loan_type()
    {
        return $this->belongsTo(LoanType::class, 'loan_type_id');
    }

    // Relationship with LoanName
    public function loan_name()
    {
        return $this->belongsTo(LoanName::class, 'loan_name_id');
    }

}
