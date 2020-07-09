<?php

namespace MillionsSaving\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use MillionsSaving\User;

class ImportStaff implements ToModel, WithHeadingRow
{
   /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   public function model(array $row)
   {

   	(isset($row['middle_name']))
   		? $name = $row['first_name']." ".$row['middle_name']." ".$row['last_name']
   	    : $name = $row['first_name']." ".$row['last_name'];

   	return new User([
   		'first_name' => $row['first_name'],
   		'middle_name' => $row['middle_name'],
   		'last_name' => $row['last_name'],
   		'name' => $name,
   		'username' => strtolower(Str::random(4).".".$row['first_name']),
   		'gender' => $row['gender'],
   		'email' => $row['email'],
   		'user_role' => $row['user_role'],
   		'occupation' => $row['occupation'],
   		'tel_no' => $row['contact1'],
   		'alt_telno ' => $row['contact2'],
   		'address' => $row['address'],
   		'date_of_birth' => $row['dob'],
   	]);
   }
}
