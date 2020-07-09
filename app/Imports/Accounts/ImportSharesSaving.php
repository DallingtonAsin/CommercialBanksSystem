<?php

namespace MillionsSaving\Imports\Accounts;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use MillionsSaving\Models\Accounts\SharesSaving;
use Illuminate\Support\Carbon;
use MillionsSaving\User;
use Illuminate\Support\Facades\DB;

class ImportSharesSaving implements ToModel, WithHeadingRow
{
   /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

   public function model(array $row)
   {

    $arr = $this->getMemberdata($row['account_number']);

   	(empty($row['withdrawal']))
   	? $withdrawal = 0
   	: $withdrawal = $row['withdrawal'];

   	(empty($row['description']))
   	? $description = 'saving to education account'
   	: $description = $row['description'];

   	$accountno =  $row['account_number'];
   	$time = date('h:i:s');

   	(empty($row['date']))
   	? $date = now()
   	: $date = date('Y-m-d', strtotime($row['date']))." ".$time;

   	return new SharesSaving([

      'member_id' => $arr['id'],
   		'acc_no' => $row['account_number'],
   		'acc_name' => $arr['accname'],
   		'type' => 'deposit',
   		'description' => $description,
   		'deposit' => $row['deposit'],
   		'withdrawal' => $withdrawal,
   		'balance' => $this->getAccountBalance($accountno),
   		'date' => $date,
   	]);
   }

   public function getAccountBalance($accountno)
   {

   	$table = 'sharessavingacc';

   	$deposit = Db::table($table)
   	->where('acc_no', $accountno)
   	->sum('deposit');
   	$withdrawal = Db::table($table)
   	->where('acc_no', $accountno)
   	->sum('withdrawal');

   	$balance = $deposit-$withdrawal;

   	($balance)
   	? $balance = $balance
   	: $balance = 0;

   	return $balance;

   }

   public function getMemberdata($accountno)
   {

   	$id = User::where('acc_noS', $accountno)
              	->value('id');

   	$acc_name = User::where('acc_noS', $accountno)
   	         ->value('acc_name');

   	$dataArr = array(
   		'id' => $id,
   		'accname' => $acc_name,
   	);

   	return $dataArr ;

   }


}
