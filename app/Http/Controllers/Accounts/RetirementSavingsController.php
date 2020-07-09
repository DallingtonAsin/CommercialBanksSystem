<?php

namespace MillionsSaving\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\CentralController;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use MillionsSaving\Models\Accounts\RetirementSaving;
use MillionsSaving\Imports\Accounts\ImportRetirementSaving;
use MillionsSaving\User;
use Excel;


class RetirementSavingsController extends Controller
{
  private $date_of_action;
  public function __construct()
  {
    $this->date_of_action = now();
    $this->middleware('auth');
}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $table = 'retirementsavingacc';
       $view  = 'pages.accounts.index.retirement-savings';
       $central = new CentralController();
       $result = $central->index($table, $view);
       return $result;

   }


   public function filter(Request $request)
   {
    $request->validate([
      'startDate' => 'required',
      'endDate' => 'required',
  ]);

    ($request->has("accountNo"))
        ? $account_no = trim($request->input('accountNo'))
        : $account_no = "";

    $table = 'retirementsavingacc';
    $date1 = date('Y-m-d', strtotime($request->input('startDate')));
    $date2 = date('Y-m-d', strtotime($request->input('endDate')));

    $central = new CentralController();
    $gateAdmin = Gate::inspect('isAdmin');
      $gateMember = Gate::inspect('isMember');
      if($gateAdmin->allowed()){
        $data = $central->masterSearchSavings($table, $date1,
          $date2, $account_no);
      }
      if($gateMember->allowed()){
        $account_number = Auth::user()->acc_noR;
        $data = $central->masterSearchSavings($table, $date1,
          $date2,$account_number);
      }

    $filtered_data = $data['data'];
    $num_of_transactions = $data['number'];
    $totalCredit = $data['deposit'];
    $totalDebt = $data['withdrawal'];

    return view('pages.accounts.index.retirement-savings')
    ->with(compact('filtered_data','totalCredit','totalDebt'
      ,'num_of_transactions'));
}


 public function searchAccountNumber(Request $request){
  
    if($request->input('query')){
    $query = trim($request->input('query'));
    $data = array();
    $table = 'users';
    $results = DB::table($table)
               ->where("acc_noR", "like", "%".$query."%")
               ->get();
    foreach($results as $row){
      $data[] = $row->acc_noR;
    }
    echo json_encode($data);
  }

}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

       $request->validate([
          'type' => 'required',
          'description' => 'required',
          'debt' => 'required',
          'credit' => 'required',
          'balance' => 'required',
      ]);

       $type = trim($request->input('type'));
       $description = trim($request->input('description'));
       $withdrawal = trim($request->input('debt'));
       $deposit = trim($request->input('credit'));
       $balance = trim($request->input('balance'));

       $table = 'retirementsavingacc';
       $account = 'Retirement Saving Account';
       $with1 = 'success';
       $with2 = 'fail';

       $central = new CentralController();
       $member = $central->getMemberName($table, $id);

       $with1_message = "You have successfully updated ".$member."'s record in retirement saving account.";
       $with2_message =  "".$member."'s record in retirement saving account not updated";

       $result = $central
       ->update($table, $type, $description, $withdrawal,
          $deposit, $balance, $id, $account,
          $with1, $with1_message, $with2, $with2_message);

       return $result;

   }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


      $table ='retirementsavingacc';
      $account = 'Retirement Savings Account';
      $url = 'accounts/retirement';
      $with1 = 'success';
      $with2 = 'fail';

      $central = new CentralController();
      $member = $central->getMemberName($table, $id);

      $with1_message = "You have successfully deleted ".$member."'s record from retirement saving account.";
      $with2_message =  "".$member."'s record from retirement saving account not deleted";
      $action = "deleted a record of ".$member.", [member] from ".$account."";

      $result = $central
      ->destroy(
        $table, $id, $account, $url,
        $with1, $with1_message,
        $with2, $with2_message,$action
    );

      return $result;
  }


    public function importSavings(Request $request)
     {

       $this->validate($request,
        ['select_file' => 'required|mimes:xls,xlsx'],
        ['select_file.mimes' => 'Please select only excel files to import retirement savings']
      );

       $result = Excel::import(new ImportRetirementSaving, request()->file('select_file'));

       if($result){

        $action = "imported an excel file of retirement savings into the system";
        LogsController::logger($action, now());

        return back()
               ->with('success','Retirement savings from an excel file have been imported successfully');
      }
      else{
       return back()
              ->with('fail','Retirement savings not imported!');
     }

   }



}
