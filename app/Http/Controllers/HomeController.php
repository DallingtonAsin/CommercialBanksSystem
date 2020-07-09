<?php

namespace MillionsSaving\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use MillionsSaving\Models\Accounts\EducationSaving;
use MillionsSaving\Models\Accounts\MainSaving;
use MillionsSaving\Models\Accounts\RetirementSaving;
use MillionsSaving\Models\Accounts\SharesSaving;
use MillionsSaving\User;
use MillionsSaving\Http\Controllers\Accounts\SharesSavingsController;
use MillionsSaving\Http\Controllers\Accounts\MainSavingsController;
use MillionsSaving\Http\Controllers\Accounts\RetirementSavingsController;
use MillionsSaving\Http\Controllers\Accounts\EducationSavingsController;

class HomeController extends Controller
{

  private $tableA, $tableB, $tableC, $tableD;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth');
      $this->tableA = 'mainsavingacc';
      $this->tableB = 'educationsavingacc';
      $this->tableC = 'retirementsavingacc';
      $this->tableD = 'sharessavingacc';
    }

    protected function getAccNames($id)
    {
      $user = User::where('id', $id)->value('acc_name');
      return $user;
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

      $GateAdmin = Gate::inspect('isAdmin');
      $GateMember = Gate::inspect('isMember');
      $month = date('m');
      $year = date('Y');

      if($GateAdmin->allowed()){

       $totalCredit1 = EducationSaving::sum('deposit');
       $totalCredit2 = MainSaving::sum('deposit');
       $totalCredit3 = RetirementSaving::sum('deposit');
       $totalCredit4 = SharesSaving::sum('deposit');
       $totalSavings = ($totalCredit1 + $totalCredit2 + $totalCredit3
        +$totalCredit4);


       $top_savers = DB::table('top_savers')->get();
       $education_topsavers = DB::table('education_topsavers')->get();
       $retirement_topsavers =DB::table('retirement_topsavers')->get();
       $shares_topsavers = DB::table('shares_topsavers')->get();

       $dataMarr = $this->getAccountBalance($this->tableA);
       $dataEarr = $this->getAccountBalance($this->tableB);
       $dataRarr = $this->getAccountBalance($this->tableC);
       $dataSarr = $this->getAccountBalance($this->tableD);

       $num_of_users = User::count();

       return view('pages.main.admin.index')
       ->with(compact('totalSavings','num_of_users','top_savers',
        'education_topsavers','retirement_topsavers','dataMarr',
        'shares_topsavers'));
     }

     if($GateMember->allowed()){

       $memberId = Auth::user()->id;
       $main_account_no = Auth::user()->acc_noM;
       $educ_account_no = Auth::user()->acc_noE;
       $ret_account_no = Auth::user()->acc_noR;
       $shares_account_no = Auth::user()->acc_noS;

       $arr1 = $this->getMemberAccountBalance($this->tableA, $main_account_no);
       $arr2 = $this->getMemberAccountBalance($this->tableB, $educ_account_no);
       $arr3 = $this->getMemberAccountBalance($this->tableC, $ret_account_no);
       $arr4 = $this->getMemberAccountBalance($this->tableD, $shares_account_no);

       $main_account_balance = $arr1['balance'];
       $educ_account_balance = $arr2['balance'];
       $ret_account_balance =  $arr3['balance'];
       $shares_account_balance = $arr4['balance'];

       $currentMonthDeposits =
       EducationSaving::where('acc_no',$educ_account_no)
       ->whereYear('date', $year)
       ->whereMonth('date', $month)
       ->sum('deposit') -
       MainSaving::where('acc_no',$main_account_no)
       ->whereYear('date', $year)
       ->whereMonth('date', $month)
       ->sum('deposit');

       SharesSaving::where('acc_no',$shares_account_no)
       ->whereYear('date', $year)
       ->whereMonth('date', $month)
       ->sum('deposit');

       RetirementSaving::where('acc_no',$ret_account_no)
       ->whereYear('date', $year)
       ->whereMonth('date', $month)
       ->sum('deposit');

       $acc_E = $this->getAccountNo($memberId, 'Education');
       $acc_M = $this->getAccountNo($memberId, 'Main');
       $acc_R = $this->getAccountNo($memberId, 'Retirement');
       $acc_S = $this->getAccountNo($memberId, 'Shares');

       $memberDataArr = array(
         'accM' => $acc_M,
         'accE' => $acc_E,
         'accR' => $acc_R,
         'accS' => $acc_S,

         'valueM' => $main_account_balance,
         'valueE' => $educ_account_balance,
         'valueR' => $ret_account_balance,
         'valueS' => $shares_account_balance,
       );

       $arrA = $this->getCurrentMonthSavings($this->tableA,
        $main_account_no);
       $arrB = $this->getCurrentMonthSavings($this->tableB,
         $educ_account_no);
       $arrC = $this->getCurrentMonthSavings($this->tableC, 
         $ret_account_no);
       $arrD = $this->getCurrentMonthSavings($this->tableD, 
        $shares_account_no);

       $TimedDataArr = array(
         'TvalueM1' =>$arrA['deposits'],
         'TvalueM2' =>$arrA['withdrawals'],
         'TvalueE1' =>$arrB['deposits'],
         'TvalueE2' =>$arrB['withdrawals'],
         'TvalueR1' =>$arrC['deposits'],
         'TvalueR2' =>$arrC['withdrawals'],
         'TvalueS1' =>$arrD['deposits'],
         'TvalueS2' =>$arrD['withdrawals'],
       );

       $totalSavings = ($main_account_balance + $educ_account_balance + 
        $ret_account_balance + $shares_account_balance);

       $num_of_users = User::count();

       return view('pages.main.member.index')
       ->with(compact('memberDataArr','totalSavings','arr1','arr2',
        'arr3','arr4','num_of_users','currentMonthDeposits', 'TimedDataArr'));
     }


   }

   protected function getMemberAccountBalance($table, $accountno)
   {

    $deposits = DB::table($table)
    ->where('acc_no', $accountno)
    ->sum('deposit');

    $withdrawals = DB::table($table)
    ->where('acc_no', $accountno)
    ->sum('withdrawal');

    $balance = $deposits - $withdrawals;

    $dataArr = array(
     'deposits' => $deposits,
     'withdrawals' => $withdrawals,
     'balance' => $balance,
   );

    return $dataArr;

  }

   protected function getAccountBalance($table)
   {

    $deposits = DB::table($table)
    ->sum('deposit');

    $withdrawals = DB::table($table)
    ->sum('withdrawal');

    $balance = $deposits - $withdrawals;

    $dataArr = array(
     'deposits' => $deposits,
     'withdrawals' => $withdrawals,
     'balance' => $balance,
   );

    return $dataArr;

  }

  protected function getCurrentMonthSavings($table, $accountno)
  {

    $month = date('m');
    $year = date('Y');
    $deposits = DB::table($table)->where('acc_no',$accountno)
    ->whereYear('date', $year)
    ->whereMonth('date', $month)
    ->sum('deposit');

    $withdrawals = DB::table($table)->where('acc_no',$accountno)
    ->whereYear('date', $year)
    ->whereMonth('date', $month)
    ->sum('withdrawal');

    $dataArr = array(
     'deposits' => $deposits,
     'withdrawals' => $withdrawals,
   );

    return $dataArr;


  }


  protected function getCurrentYearSavings($table, $accountno)
  {

    $year = date('Y');
    $deposits = DB::table($table)->where('acc_no',$accountno)
    ->whereYear('date', $year)
    ->sum('deposit');

    $withdrawals = DB::table($table)->where('acc_no',$accountno)
    ->whereYear('date', $year)
    ->sum('withdrawal');

    $dataArr = array(
     'deposits' => $deposits,
     'withdrawals' => $withdrawals,
   );

    return $dataArr;


  }

  public function getAccountNo($memberId, $acc_id)
  {
    switch (true)
    {
      case $acc_id == 'Education':
      $accno = User::where('id', $memberId)->value('acc_noE');
      break;
      case $acc_id == 'Main':
      $accno = User::where('id', $memberId)->value('acc_noM');
      break;

      case $acc_id == 'Shares':
      $accno = User::where('id', $memberId)->value('acc_noS');
      break;

      case $acc_id == 'Retirement':
      $accno = User::where('id', $memberId)->value('acc_noR');
      break;

    }

    return $accno;

  }



}
