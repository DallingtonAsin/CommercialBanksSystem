<?php

namespace MillionsSaving\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use MillionsSaving\Models\Loans\Loan;

class ImportLoanees implements ToModel, WithHeadingRow
{
   /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   public function model(array $row)
   {

   	return new Loan([
   		'name' => $row['name'],
   		'gender' => $row['gender'],
   		'loan_amount' => floatval($row['loan_amount']),
   		'duration' => $row['duration'],
   		'duration_in' => $row['duration_in'],
   		'collateral'=> $row['security'],
   		'address' => $row['address'],
   		'occupation' => $row['occupation'],
   		'telno' => $row['contact'],
   		'loan_balance' => floatval($row['loan_amount']),
   	]);


   }


}
