<?php

namespace MillionsSaving\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\CentralController;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use MillionsSaving\Imports\Accounts\ImportSharesSaving;
use MillionsSaving\Models\Accounts\SharesSaving;
use MillionsSaving\User;
use Excel;

class SharesSavingsController extends Controller
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

     $table = 'sharessavingacc';
     $view  = 'pages.accounts.index.shares-savings';
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

    $table = 'sharessavingacc';
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
        $account_number = Auth::user()->acc_noS;
        $data = $central->masterSearchSavings($table, $date1,
          $date2,$account_number);
      }

    $filtered_data = $data['data'];
    $num_of_transactions = $data['number'];
    $totalCredit = $data['deposit'];
    $totalDebt = $data['withdrawal'];

    return view('pages.accounts.index.shares-savings')
    ->with(compact('filtered_data','totalCredit','totalDebt'
        ,'num_of_transactions'));


}


 public function searchAccountNumber(Request $request){
  
    if($request->input('query')){
    $query = trim($request->input('query'));
    $data = array();
    $table = 'users';
    $results = DB::table($table)
               ->where("acc_noS", "like", "%".$query."%")
               ->get();
    foreach($results as $row){
      $data[] = $row->acc_noS;
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

        $table = 'sharessavingacc';
        $account = 'Shares Saving Account';
        $with1 = 'success';
        $with2 = 'fail';

        $central = new CentralController();
        $member = $central->getMemberName($table, $id);

        $with1_message = "You have successfully updated ".$member."'s record in shares saving account.";
        $with2_message =  "".$member."'s record in shares saving account not updated";

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

     $table ='sharessavingacc';
     $account = 'Shares Savings Account';
     $url = 'accounts/shares';
     $withV1 = 'success';
     $withV2 = 'fail';
     $central = new CentralController();
     $member = $central->getMemberName($table, $id);

     $withV1_message = "You have successfully deleted ".$member."'s record from shares saving account.";
     $withV2_message = "".$member."'s record from shares saving account record not deleted";
     $action = "deleted a record of ".$member.", [member] from ".$account."";


     $result = $central
                ->destroy(
                    $table,  $id, $account, $url,
                    $withV1, $withV1_message,
                    $withV2, $withV2_message, $action
                  );

     return $result;

 }


 public function importSavings(Request $request)
     {

       $this->validate($request,
        ['select_file' => 'required|mimes:xls,xlsx'],
        ['select_file.mimes' => 'Please select only excel files to import shares savings']
      );

       $result = Excel::import(new ImportSharesSaving, request()->file('select_file'));

       if($result){

        $action = "imported an excel file of shares savings into the system";
        LogsController::logger($action, now());

        return back()
               ->with('success','Shares savings from an excel file have been imported successfully');
      }
      else{
       return back()
              ->with('fail','Shares savings not imported!');
     }

   }






}
