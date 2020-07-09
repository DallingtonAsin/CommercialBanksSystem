<?php

namespace MillionsSaving\Models\Logs;

use Illuminate\Database\Eloquent\Model;

class RequestResponseLogs extends Model
{
    protected $table = 'request_response_logs';

    protected $fillable = [
    	   'date',
           'user',
           'role',
           'method',
           'method_type',
           'request',
           'response',
           'ip_address',
    ];
    
    public $timestamps = false;
}
