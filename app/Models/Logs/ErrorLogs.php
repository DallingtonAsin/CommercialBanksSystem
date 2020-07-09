<?php

namespace MillionsSaving\Models\Logs;

use Illuminate\Database\Eloquent\Model;

class ErrorLogs extends Model
{
    protected $table = 'error_logs';

    protected $fillable = [
           'user',
           'role',
           'error',
           'method',
           'ip_address',
    ];
    
    public $timestamps = false;
}
