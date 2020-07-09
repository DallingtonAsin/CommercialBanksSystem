<?php

namespace MillionsSaving\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\CentralController;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use MillionsSaving\Models\Accounts\MainSaving;
use MillionsSaving\Imports\Accounts\ImportMainSaving;
use MillionsSaving\User;
use Excel;

class MainSavingsController extends Controller
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

      $table = 'mainsavingacc';
      $view  = 'pages.accounts.index.main-savings';
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

      $table = 'mainsavingacc';
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
        $account_number = Auth::user()->acc_noM;
        $data = $central->masterSearchSavings($table, $date1,
          $date2,$account_number);
      }

      $filtered_data = $data['data'];
      $num_of_transactions = $data['number'];
      $totalCredit = $data['deposit'];
      $totalDebt = $data['withdrawal'];

      return view('pages.accounts.index.main-savings')
      ->with(compact('filtered_data','totalCredit','totalDebt'
        ,'num_of_transactions'));
    }


 public function searchAccountNumber(Request $request){
  
    if($request->input('query')){
    $query = trim($request->input('query'));
    $data = array();
    $table = "users";
    $results = DB::table($table)
               ->where("acc_noM", "like", "%".$query."%")
               ->get();
    foreach($results as $row){
      $data[] = $row->acc_noM;
    }
    echo json_encode($data);
  }

}

 public function searchAccountNames(Request $request){
  
    if($request->input('item')){
    $query = trim($request->input('item'));
    $data = array();
    $account_number = User::where("acc_noM", "like", "%".$query."%")
               ->value('acc_name');
    echo json_encode($account_number);
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

      $table = 'mainsavingacc';
      $account = 'Main Saving Account';
      $with1 = 'success';
      $with2 = 'fail';

      $central = new CentralController();
      $member = $central->getMemberName($table, $id);

      $with1_message = "You have successfully updated ".$member."'s record in main saving account.";
      $with2_message =  "".$member."'s record in main saving account not updated";

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

     $table ='mainsavingacc';
     $account = 'Main Savings Account';
     $url = 'accounts/main-saving';
     $with1 = 'success';
     $with2 = 'fail';

     $central = new CentralController();
     $member = $central->getMemberName($table, $id);

     $with1_message = "You have successfully deleted ".$member."'s record from main saving account.";
     $with2_message =  "".$member."'s record from main saving account not deleted";
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
      ['select_file.mimes' => 'Please select only excel files to import main savings']
    );

     $result = Excel::import(new ImportMainSaving, request()->file('select_file'));

     if($result){

      $action = "imported an excel file of main savings into the system";
      LogsController::logger($action, now());

      return back()
      ->with('success','Main savings from an excel file have been imported successfully');
    }
    else{
     return back()
     ->with('fail','Main savings not imported!');
   }

 }


} //end of class
