<?php

namespace MillionsSaving\Models\Accounts;

use Illuminate\Database\Eloquent\Model;

class MainSaving extends Model
{
    protected $table = 'mainsavingacc';
    public $timestamps = false;

    protected $fillable = [
    	
   		'acc_no',
   		'acc_name',
   		'type',
   		'description',
   		'deposit',
   		'withdrawal',
   		'balance',
   		'date',
    ];
}
