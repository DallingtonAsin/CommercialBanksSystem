<?php

namespace MillionsSaving\Imports\Accounts;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use MillionsSaving\Models\Accounts\MainSaving;
use Illuminate\Support\Carbon;
use MillionsSaving\User;
use Illuminate\Support\Facades\DB;

class ImportMainSaving implements ToModel, WithHeadingRow
{
   /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   public function model(array $row)
   {

    $arr = $this->getMemberdata($row['account_number']);
    //dd($arr);
   // dd($row['account_number']);
   	(empty($row['withdrawal']))
   	? $withdrawal = 0
   	: $withdrawal = $row['withdrawal'];

   	(empty($row['description']))
   	? $description = 'saving to main account'
   	: $description = $row['description'];

   	$accountno =  $row['account_number'];
   	$time = date('H:i:s');

   	(empty($row['date']))
   	? $date = now()
   	: $date = date('Y-m-d', strtotime($row['date']))." ".$time;

   	return new MainSaving([

      'member_id' => $arr['id'],
   		'acc_no' => $row['account_number'],
   		'acc_name' => $arr['accname'],
   		'type' => 'main saving',
   		'description' => $description,
   		'deposit' => $row['deposit'],
   		'withdrawal' => $withdrawal,
   		'balance' => $this->getAccountBalance($accountno),
   		'date' => $date,
   	]);
   }

   public function getAccountBalance($accountno)
   {

   	$deposit = Db::table('mainsavingacc')
   	->where('acc_no', $accountno)
   	->sum('deposit');
   	$withdrawal = Db::table('mainsavingacc')
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

   	$id = User::where('acc_noM', $accountno)
              	->value('id');

   	$acc_name = User::where('acc_noM', $accountno)
   	         ->value('acc_name');

   	$dataArr = array(
   		'id' => $id,
   		'accname' => $acc_name,
   	);

   	return $dataArr ;

   }


}
