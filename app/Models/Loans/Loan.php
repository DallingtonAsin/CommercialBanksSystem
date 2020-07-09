<?php

namespace MillionsSaving\Models\Loans;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
	
	protected $table ='loans';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'gender',
		'loan_amount',
		'duration',
		'duration_in',
		'collateral',
		'address',
		'occupation',
		'telno',
		'date_of_birth',
		'loan_balance',

	];

}
