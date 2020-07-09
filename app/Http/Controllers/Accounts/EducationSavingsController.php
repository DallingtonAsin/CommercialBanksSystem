<?php

namespace MillionsSaving\Http\Controllers\Accounts;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\CentralController;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use MillionsSaving\Models\Accounts\EducationSaving;
use MillionsSaving\Imports\Accounts\ImportEducationSaving;
use MillionsSaving\User;
use Excel;

class EducationSavingsController extends Controller
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

      try{
        $table = 'educationsavingacc';
       $view  = 'pages.accounts.index.education-savings';
       $central = new CentralController();
       $result = $central->index($table, $view);
       return $result;
     }catch(Exception $exception){
      $dataArr = array(
        "error" =>$ex->getMessage(),
        "method" =>"EducationSavingsController@index"
      );

      LogsController::LogError($dataArr);
      report($exception);
    }


     catch(Exception $exception){
      $dataArr = array(
        "error" =>$ex->getMessage(),
        "method" =>"EducationSavingsController@index"
      );

      LogsController::LogError($dataArr);
      report($exception);


    }

  }

  public function filter(Request $request)
  {

    $request->validate([
      'startDate' => 'required',
      'endDate' => 'required',
    ]);
    $method = "EducationSavingsController@filter";
      /*  throw ValidationException::withMessages([
          'startDate' => ['There is custom validation failing here'],
        ]);*/

        ($request->has("accountNo"))
        ? $account_no = trim($request->input('accountNo'))
        : $account_no = "";

        $table = 'educationsavingacc';
        $date1 = date('Y-m-d', strtotime($request->input('startDate')));
        $date2 = date('Y-m-d', strtotime($request->input('endDate')));

        $central = new CentralController();
        try{

          $requestData = $request->getContent();


          $gateAdmin = Gate::inspect('isAdmin');
          $gateMember = Gate::inspect('isMember');
          if($gateAdmin->allowed()){
            $data = $central->masterSearchSavings($table, $date1,
              $date2, $account_no);
          }
          if($gateMember->allowed()){
            $account_number = Auth::user()->acc_noE;
            $data = $central->masterSearchSavings($table, $date1,
              $date2,$account_number);
          }

          $filtered_data = $data['data'];
          $num_of_transactions = $data['number'];
          $totalCredit = $data['deposit'];
          $totalDebt = $data['withdrawal'];

          $arr = array(
            "method" => $method,
            "request" => $requestData,
            "response" => $requestData,
            "method_type" => $request->method(),
          );

          LogsController::LogRequestResponse($arr);

          return view('pages.accounts.index.education-savings')
          ->with(compact('filtered_data','totalCredit','totalDebt'
            ,'num_of_transactions'));
        }
        catch(Exception $ex){
          $dataArr = array(
            "error" =>$ex->getMessage(),
            "method" => $method,
            "method_type" => $request->method(),
          );

          LogsController::LogError($dataArr);
        }


      }

      public function searchAccountNumber(Request $request){

        if($request->input('query')){
          $query = trim($request->input('query'));
          $data = array();
          $table = 'users';
          try{
            $results = DB::table($table)
            ->where("acc_noE", "like", "%".$query."%")
            ->get();
            foreach($results as $row){
              $data[] = $row->acc_noE;
            }
            echo json_encode($data);
          }
          catch(Exception $ex){
            throw $ex->getMessage();
          }
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

     $type =   trim($request->input('type'));
     $description = trim($request->input('description'));
     $withdrawal = trim($request->input('debt'));
     $deposit = trim($request->input('credit'));
     $balance = trim($request->input('balance'));

     $table = 'educationsavingacc';
     $account = 'Education Saving Account';
     $with1 = 'success';
     $with2 = 'fail';
     try{
       $central = new CentralController();
       $member = $central->getMemberName($table, $id);

       $with1_message = "You have successfully updated ".$member."'s record in education saving account.";
       $with2_message =  "".$member."'s record in education saving account not updated";

       $result = $central
       ->update($table, $type, $description, $withdrawal,
        $deposit, $balance, $id, $account,$with1,
        $with1_message, $with2, $with2_message);

       return $result;
     }catch(Exception $ex){
      $dataArr = array(
        "error" =>$ex->getMessage(),
        "method" =>"EducationSavingsController@update"
      );

      LogsController::LogError($dataArr);
    }

  }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

     $table ='educationsavingacc';
     $account = 'Education Savings Account';
     $url = 'accounts/education';
     $with1 = 'success';
     $with2 = 'fail';

     try{

       $central = new CentralController();
       $member = $central->getMemberName($table, $id);

       $with1_message = "You have successfully deleted ".$member."'s record from education saving account.";
       $with2_message = "".$member."'s record from education saving account not deleted";
       $action = "deleted a record of ".$member.", [member] from ".$account."";


       $result = $central
       ->destroy(
        $table, $id, $account, $url,
        $with1, $with1_message,
        $with2, $with2_message, $action
      );

       return $result;
     }
     catch(Exception $ex){
      $dataArr = array(
        "error" =>$ex->getMessage(),
        "method" =>"EducationSavingsController@destroy"
      );

      LogsController::LogError($dataArr);
    }

  }


  public function importSavings(Request $request)
  {

   $this->validate($request,
    ['select_file' => 'required|mimes:xls,xlsx'],
    ['select_file.mimes' => 'Please select only excel files to import education savings']
  );


   try{
     $result = Excel::import(new ImportEducationSaving, request()->file('select_file'));

     if($result){

      $action = "imported an excel file of education savings into the system";
      LogsController::logger($action, now());

      return back()
      ->with('success','Education savings from an excel file have been imported successfully');
    }
    else{
     return back()
     ->with('fail','Education savings not imported!');
   }

 }catch(Exception $ex){
  $dataArr = array(
    "error" =>$ex->getMessage(),
    "method" =>"EducationSavingsController@importSavings"
  );

  LogsController::LogError($dataArr);
}
}





}
