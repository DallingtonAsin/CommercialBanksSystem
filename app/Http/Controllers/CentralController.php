<?php

namespace MillionsSaving\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use MillionsSaving\Http\Controllers\Logs\LogsController;

class CentralController extends Controller
{


	private $date_of_action; 

	public function __construct()
	{
		$this->date_of_action = now();
	}


//method used to load the index pages: all acounts
	public function index($table, $view)
	{
		$response = Gate::inspect('isAdmin');
		if($response->allowed())
		{
			$data = DB::table($table)->latest('date')->get(); 
			$num_of_transactions =DB::table($table)->count();
			$totalCredit = DB::table($table)->sum('deposit');
			$totalDebt = DB::table($table)->sum('withdrawal');

		}

		if(Gate::allows('isMember'))
		{
			$data = DB::table($table)
			->where('member_id','=', Auth::user()->id)->get();

			$num_of_transactions =
			DB::table($table)
			->where('member_id','=', Auth::user()->id)
			->get()->count();

			$totalCredit = DB::table($table)
			->where('member_id','=', Auth::user()->id)->sum('deposit');
			$totalDebt = DB::table($table)
			->where('member_id','=', Auth::user()->id)->sum('withdrawal');

		}

		return view($view)
		->with(compact('data','totalCredit','totalDebt','num_of_transactions'));

	}

	public function sendMail($data, $page)
	{

		 if($this->is_connectedToInternet() == 1) {
          Mail::send($page, ['data' => $data], function($message) use ($data) {   
            $message->from($data['companyEmail'], 'Dallington');
            $message->to($data['payee_email'])->subject($data['subject']);
          }); 

          if(Mail::failures())
          {
             return -1;
         } 
          else{
          return 1;
        }
      }

      else if($this->is_connectedToInternet() == 0)
      {
       return 0;

     }

	}




//method that is used by ther classes to filter savings

	public function masterSearchSavings($table, $date1, $date2, $account_no)
	{

		$responseAdmin =  Gate::inspect('isAdmin');
		$responseMember = Gate::inspect('isMember');

		if($responseAdmin->allowed()){

            if(isset($account_no) && $account_no != null){
            	
			$filtered_data = DB::table($table)
			->where('acc_no', $account_no)
			->whereBetween('date', [$date1, $date2])
			->latest('date')
			->get();
		

			$num_of_transactions =
			DB::table($table)
			->where('acc_no', $account_no)
			->whereBetween('date', [$date1, $date2])
			->get()->count();

			$totalCredit = DB::table($table)
			->where('acc_no', $account_no)
			->whereBetween('date', [$date1, $date2])
			->sum('deposit');

			$totalDebt = DB::table($table)
			->where('acc_no', $account_no)
			->whereBetween('date', [$date1, $date2])
			->sum('withdrawal');

          }
          else  
          {
            
          	$filtered_data = DB::table($table)
			->whereBetween('date', [$date1, $date2])
			->latest('date')
			->get();

			$num_of_transactions =
			DB::table($table)
			->whereBetween('date', [$date1, $date2])
			->get()->count();

			$totalCredit = DB::table($table)
			->whereBetween('date', [$date1, $date2])
			->sum('deposit');

			$totalDebt = DB::table($table)
			->whereBetween('date', [$date1, $date2])
			->sum('withdrawal');

          }

			$dataArray = array('data' => $filtered_data,
				'number' => $num_of_transactions,
				'deposit' => $totalCredit,
				'withdrawal' => $totalDebt,
			);

		}

		if($responseMember->allowed()){

			$filtered_data = DB::table($table)
			->where('acc_no', $account_no)
			->whereBetween('date', [$date1, $date2])
			->where('member_id','=', Auth::user()->id)
			->latest('date')
			->get();

			$num_of_transactions =
			DB::table($table)
			->where('acc_no', $account_no)
			->whereBetween('date', [$date1, $date2])
			->where('member_id','=', Auth::user()->id)
			->get()->count();

			$totalCredit = DB::table($table)
			->where('acc_no', $account_no)
			->whereBetween('date', [$date1, $date2])
			->where('member_id','=', Auth::user()->id)
			->sum('deposit');

			$totalDebt = DB::table($table)
			->where('acc_no', $account_no)
			->whereBetween('date', [$date1, $date2])
			->where('member_id','=', Auth::user()->id)
			->sum('withdrawal');

			$dataArray = array(
				'data' => $filtered_data,
				'number' => $num_of_transactions,
				'deposit' => $totalCredit,
				'withdrawal' => $totalDebt,
			);

		}

		return $dataArray;

	}

//method that updates accoounts: all accounts (5)

	public function update($table, $type, $desc, $debt, $credit, $bal,
		$id, $account, $withSuccess,$successMessage,
		$withFailed, $failMessage)
	{

		$updateRes = $data = DB::table($table)
		->where('id', $id)
		->update([
			'type' => $type,
			'description' => $desc,
			'withdrawal' =>  floatval($debt),
			'deposit' => floatval($credit),
			'balance' => floatval($bal),
		]);

		$memberName = $this->getMemberName($table, $id);

		if($updateRes)
		{
			$action = "updated a record of ".$memberName.", [member] in ".$account." ";
			LogsController::logger($action, $this->date_of_action);

			return back()
			->with($withSuccess,$successMessage);
		}
		else
		{
			return back()
			->with($withFailed,$failMessage);
		}


	}


//method used to delete records from accounts

	public function destroy($table, $id, $account, $url,
		$withSuccess, $successMessage,
		$withFailed, $failMessage, $action)
	{

		if(Gate::allows('isAdmin')){

			$result = DB::table($table)
			->where('id', $id)->delete();

			if($result){

				LogsController::logger($action, $this->date_of_action); 

				return redirect($url)
				->with($withSuccess,$successMessage);
			}
			else
			{
				return redirect($url)
				->with($withFailed,$failMessage);
			}

		}
	}


	//method that updates loanees and defaultors

	public function updateLoanDetails($data)
	{

		$table = $data['tbl'] ;
		$amount = $data['amount'];
		$collateral = $data['security'];
		$due_date = $data['dueDate'];
		$id = $data['id']; 
		$account= $data['class'];
		$redirect= $data['url'];
		$withSuccess= $data['v1'];
		$successMessage= $data['message1'];
		$withFailed= $data['v2'];
		$failMessage= $data['message2'];
		$action = $data['log'];

		$result = $data = DB::table($table)
		->where('id', $id)
		->update([
			'loan_amount' => $amount,
			'collateral' => $collateral,
			'due_date' => $due_date,
		]);

		if($result)
		{
			LogsController::logger($action, $this->date_of_action);

			return redirect($redirect)
			->with($withSuccess,$successMessage);
		}
		else
		{
			return redirect($redirect)
			->with($withFailed,$failMessage);
		}


	}

	public function getMemberName($table, $id)
	{
		$memberId = DB::table($table)->where('id',$id)->value('member_id');
		$user = DB::table('users')->where('id',$memberId)->value('name');
		return $user;
	}


	public function getLoanee($table, $id)
	{
		$loanee = DB::table($table)
		->where('id',$id)->value('name');
		return $loanee;
	}


     //method to check if there is internet connection
	
	public function is_connectedToInternet()
	{
		$connected = @fsockopen('www.google.com', 80);
		if($connected){
			$is_conn = 1;
			fclose($connected);
		}
		else{
			$is_conn = 0;
		}
		return $is_conn;
	}


	public function is_inArr($dataArr, $item)
	{

		if(count($dataArr) > 0){
			(in_array($item, $dataArr))
			? $bool = true
			: $bool = false;
		}

		return $bool;
	}





}
