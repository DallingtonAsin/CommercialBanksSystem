<?php

namespace MillionsSaving\Models\Loans;

use Illuminate\Database\Eloquent\Model;

class LoanSetting extends Model
{
    protected $table = 'loan_settings';
    public $timestamps = false;

    protected $fillable = [
    	    'min_loanamt',
            'max_loanamt',
            'interest_rate',
    ];
}
