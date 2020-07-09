<?php

namespace MillionsSaving\Models\User;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
   protected $table = 'roles';
   public $timestamps = false;
   protected $fillable = [
   	        'role',
            'is_admin',
            'isSuperAdmin',
   ];
}
