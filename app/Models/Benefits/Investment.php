<?php

namespace MillionsSaving\Models\Benefits;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
	protected $table = 'investments';
	public $timestamps = false;
	protected $fillable = [
		'asset',
		'details',
		'capital',
		'returns', 
		'approved_on',
		'approved_by',     
	];
}
