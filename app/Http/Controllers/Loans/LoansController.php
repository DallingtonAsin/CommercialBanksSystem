<?php

namespace MillionsSaving\Http\Controllers\Loans;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\CentralController;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use MillionsSaving\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use MillionsSaving\Models\Loans\Loan;
use MillionsSaving\Imports\ImportLoanees;
use Excel;


class LoansController extends Controller
{

  private $tableName;

  public function __construct()
  {
    $this->middleware('auth');
    $this->tableName = 'loans';
  }
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {


    $loanees = DB::table($this->tableName)
    ->where('is_paid', false)
    ->where('loan_amount', '>', 0)
    ->whereNotNull('loan_balance')
    ->get();

    $number_of_loanees = DB::table($this->tableName)
    ->where('is_paid', false)
    ->where('loan_amount', '>', 0)
    ->whereNotNull('loan_balance')
    ->count();

    $totalLoans = DB::table($this->tableName)
    ->where('is_paid', false)
    ->where('loan_amount', '>', 0)
    ->whereNotNull('loan_balance')
    ->sum('loan_amount');

    return view('pages.loans.loanees')
    ->with(compact('loanees', 'totalLoans','number_of_loanees'));

  }


  public function loanHistory()
  {

    if(Gate::allows('isAdmin')){
      $data = DB::table($this->tableName)
      ->where('paid_amount', '>', 0)
      ->get();

      $totalAmount = DB::table($this->tableName)
      ->where('paid_amount', '>', 0)
      ->sum('paid_amount');

      $number_of_records = DB::table($this->tableName)
      ->where('paid_amount', '>', 0)
      ->count();

      return view('pages.loans.loan-history')
      ->with(compact('data', 'totalAmount','number_of_records'));

    }

    if(Gate::allows('isMember')){
      $data = DB::table($this->tableName)
      ->where('paid_amount', '>', 0)
      ->where('name', Auth::user()->name)
      ->get();

      $totalAmount = DB::table($this->tableName)
      ->where('paid_amount', '>', 0)
      ->where('name', Auth::user()->name)
      ->sum('paid_amount');

      $number_of_records = DB::table($this->tableName)
      ->where('paid_amount', '>', 0)
      ->where('name', Auth::user()->name)
      ->count();

      return view('pages.loans.loan-history')
      ->with(compact('data', 'totalAmount','number_of_records'));

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

    $this->validate($request,[

      'firstName' => 'required',
      'lastName' => 'required',
      'Gender' => 'required',
      'address' => 'required',
      'dob' => 'required',
      'occupation' => 'required',
      'phone1' => 'required',
      'loanAmount' => 'required',
      'loanSecurity' => 'required',
      'loanDuration' => 'required',
      'durationIn' => 'required',
      'loanStmt' => 'required',

    ]);

    $data = new Loan;
    $central = new CentralController;

    $f_name = $request->input('firstName');
    $l_name = $request->input('lastName');
    if($request->has('middleName')){
      $mname = $request->input('middleName');
      $name =  $f_name." ".$mname." ".$l_name;
    }else{
      $name = $f_name." ".$l_name;
    }

  

    if($request->hasfile('loan-file-input'))
    {
      $loanfile = $request->file('loan-file-input');
      $extension = $loanfile->getClientOriginalExtension();
      $filename = Auth::user()->id.".".$extension;
      $loanfile->storeAs('loan-applications', $filename);
      $data->loanapp_file = $filename;

      //Storage::putFile('loanapp_file', $loanfile);
    }
    else
    {
      $loan_file = null;
    }

    $gender = $request->input('Gender');
    $address = $request->input('address');
    $date_of_birth = $request->input('dob');
    $jobTitle = $request->input('occupation');
    $telno = $request->input('phone1');
    $amt = $request->input('loanAmount');
    $duration =  $request->input('loanDuration');
    $duration_in =  $request->input('durationIn');
    $security = $request->input('loanSecurity');
    $stmt = $request->input('loanStmt');

    $data->name = Str::title($name);
    $data->gender = $gender;
    $data->address = $address;
    $data->date_of_birth = $date_of_birth;
    $data->occupation = $jobTitle;
    $data->telno = $telno;
    $data->loan_amount = $amt;
    $data->duration = $duration;
    $data->duration_in = $duration_in;
    $data->collateral = $security;
    $data->statement = $stmt;

     ($request->has('email'))
    ? $applicantEmail = $request->input("email")
    : $applicantEmail = "";

    ($request->has('phone2'))
    ? $data->alt_telno = $request->input('phone2')
    : $data->alt_telno = null;

    $res = $data->save();
    if($res)
   {

    $amount = number_format($amt);
    $applicant = $name;
    $action = "submitted an application for a loan worth ".$amount."";
    LogsController::logger($action, now());

    if(isset($applicantEmail)){

    if($central->is_connectedToInternet() == 1)
    {
      $companyEmail = config('app.companyEmail');
      $subject = Str::title("Submission of loan request of amount ".$amount."");
     
      $applicantDataArray = array(
       'applicantEmail' => $applicantEmail,
       'applicant' => $applicant,
       'amount' => $amount,
       'subject' => $subject,
       'companyEmail' => $companyEmail,
     );

      Mail::send('pages.mail.loan.loan-submission-mail', 
       $applicantDataArray, function($message) 
       use ($applicant, $applicantEmail, $amount, $subject, $companyEmail)
       {   
        $message->from($companyEmail, 'Dallington');
        $message->to($applicantEmail)->subject($subject);
      }); 

      if(Mail::failures()){
        return back()
        ->with('success', "You have successfully ".$action." but an email has not been sent because of poor internet connection");

      }
      else
      {
        return back()
        ->with('success', "You have successfully ".$action." and an email has been automatically sent to you, ".$applicant." about application for loan worth ".$amount.""); 
      } 

    }
  
    else if($central->is_connectedToInternet() == 0)
    {

      return back()
      ->with('success', "You have successfully ".$action." but an email has not been sent because of no internet connection"); 

    }
  }

     else{
       return back()
      ->with('success', "You have successfully ".$action."");
  }

  }
    else
    {
      return back()->with('fail', "Application for the loan
      has failed");
    }


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

    $GateAdmin = Gate::inspect('isLoansManager');

    if($GateAdmin->allowed()){
      $request->validate([
        'loanAmount' => 'required',
        'security' => 'required',
        'dueDate' => 'required',
      ]);

      $amount = $request->input('loanAmount');
      $collateral = $request->input('security');
      $due_date = $request->input('dueDate');

      $relation = 'loanees';
      $url = 'loans/loanees';
      $with1 = 'success';
      $with2 = 'fail';

      $central = new CentralController();
      $loanee = $central->getLoanee($this->tableName, $id);

      $with1_message = "You have successfully updated ".$loanee."'s loan record in list of ".$relation."";
      $with2_message =  "".$loanee."'s loan record not deleted";
      $action = "updated a loan record of ".$loanee." in list of ".$relation."";

      $dataArray =array(
        'tbl' => $this->tableName, 'amount' => $amount,
        'security' => $collateral, 'dueDate' => $due_date,
        'id' => $id, 'class' => $relation, 'url' => $url,
        'v1' => $with1, 'message1' => $with1_message,
        'v2' => $with2, 'message2' => $with2_message,
        'log' => $action
      );

      $result = $central->updateLoanDetails($dataArray);

      return $result;

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

    $response = Gate::inspect('isLoansManager');
    if($response->allowed()){
      $class = 'loanees';
      $url = 'loans/loanees';
      $with1 = 'success';
      $with2 = 'fail';

      $central = new CentralController();
      $loanee = $central->getLoanee($this->tableName, $id);
      $with1_message = "You have successfully deleted ".$loanee."'s loan record from list of ".$class."";
      $with2_message =  "".$loanee."'s loan record not deleted";
      $action = "deleted a loan record of ".$loanee." from list of ".$class."";


      $result = $central
      ->destroy(
        $this->tableName, $id, $class, $url,
        $with1, $with1_message,
        $with2, $with2_message,$action
      );

      return $result;
    }


  }




  public function loanApplication()
  {
    return view('pages.loans.loan-application');
  }

  public function pendingLoanRequests()
  {

    $gateAdmin= Gate::inspect('isAdmin');
    $gateMember= Gate::inspect('isMember');

    if($gateAdmin->allowed()){

      $pendingRequests = DB::table($this->tableName)
      ->where('loan_amount', '>', 0)
      ->where('is_approved', 0)
      ->where('is_denied', 0)
      ->whereNull('taken_on')
      ->get();

      $no_of_pendingRequests = DB::table($this->tableName)
      ->where('loan_amount', '>', 0)
      ->where('is_approved', 0)
      ->where('is_denied', 0)
      ->whereNull('taken_on')
      ->count();

      $totalLoansInRequest = DB::table($this->tableName)
      ->where('loan_amount', '>', 0)
      ->where('is_approved', 0)
      ->where('is_denied', 0)
      ->whereNull('taken_on')
      ->sum('loan_amount');

    }

    if($gateMember->allowed()){

      $name = Auth::user()->name;

      $pendingRequests = DB::table($this->tableName)
      ->where('name', $name)
      ->where('loan_amount', '>', 0)
      ->where('is_approved', 0)
      ->where('is_denied', 0)
      ->whereNull('taken_on')
      ->get();

      $no_of_pendingRequests = DB::table($this->tableName)
      ->where('name', $name)
      ->where('loan_amount', '>', 0)
      ->where('is_approved', 0)
      ->where('is_denied', 0)
      ->whereNull('taken_on')
      ->count();

      $totalLoansInRequest = DB::table($this->tableName)
      ->where('name', $name)
      ->where('loan_amount', '>', 0)
      ->where('is_approved', 0)
      ->where('is_denied', 0)
      ->whereNull('taken_on')
      ->sum('loan_amount');
    }


    return view('pages.loans.pending-loan-requests')
    ->with(compact('pendingRequests', 'no_of_pendingRequests','totalLoansInRequest'));

  }

  public function deniedLoanRequests()
  {

    $gateAdmin= Gate::inspect('isAdmin');
    $gateMember= Gate::inspect('isMember');

    if($gateAdmin->allowed()){

      $deniedRequests = DB::table($this->tableName)
      ->where('loan_amount', '>', 0)
      ->where('is_denied', 1)
      ->whereNotNull('denied_on')
      ->get();

      $no_of_deniedRequests = DB::table($this->tableName)
      ->where('loan_amount', '>', 0)
      ->whereNotNull('denied_on')
      ->count();

      $totalDeniedLoans = DB::table($this->tableName)
      ->where('loan_amount', '>', 0)
      ->whereNotNull('denied_on')
      ->sum('loan_amount');

    }

    if($gateMember->allowed()){

      $deniedRequests = DB::table($this->tableName)
      ->where('name', Auth::user()->name)
      ->where('loan_amount', '>', 0)
      ->where('is_denied', 1)
      ->whereNotNull('denied_on')
      ->get();

      $no_of_deniedRequests = DB::table($this->tableName)
      ->where('name', Auth::user()->name)
      ->where('loan_amount', '>', 0)
      ->whereNotNull('denied_on')
      ->count();

      $totalDeniedLoans = DB::table($this->tableName)
      ->where('name', Auth::user()->name)
      ->where('loan_amount', '>', 0)
      ->whereNotNull('denied_on')
      ->sum('loan_amount');


    }

    return view('pages.loans.denied-loan-requests')
    ->with(compact('deniedRequests', 'no_of_deniedRequests','totalDeniedLoans'));



  }

  public function approvedLoanRequests()
  {


    $gateAdmin= Gate::inspect('isAdmin');
    $gateMember= Gate::inspect('isMember');

    if($gateAdmin->allowed()){

      $approvedRequests = Loan::where('loan_amount', '>', 0)
      ->where('is_approved', 1)
      ->where('loan_balance', '>', 0)
      ->whereNotNull('approved_on')
      ->get();

      $no_of_approvedRequests = Loan::where('loan_amount', '>', 0)
      ->where('is_approved', 1)
      ->where('loan_balance', '>', 0)
      ->whereNotNull('approved_on')
      ->count();

      $totalApprovedLoans = Loan::where('loan_amount', '>', 0)
      ->where('is_approved', 1)
      ->where('loan_balance', '>', 0)
      ->whereNotNull('approved_on')
      ->sum('loan_amount');

    }

    if($gateMember->allowed()){

      $approvedRequests = Loan::where('loan_amount', '>', 0)
      ->where('name', Auth::user()->name)
      ->where('is_approved', 1)
      ->where('loan_balance', '>', 0)
      ->whereNotNull('approved_on')
      ->get();

      $no_of_approvedRequests = Loan::where('loan_amount', '>', 0)
      ->where('name', Auth::user()->name)
      ->where('is_approved', 1)
      ->where('loan_balance', '>', 0)
      ->whereNotNull('approved_on')
      ->count();

      $totalApprovedLoans = Loan::where('loan_amount', '>', 0)
      ->where('name', Auth::user()->name)
      ->where('is_approved', 1)
      ->where('loan_balance', '>', 0)
      ->whereNotNull('approved_on')
      ->sum('loan_amount');

    }

    return view('pages.loans.approved-loan-requests')
    ->with(compact('approvedRequests', 'no_of_approvedRequests','totalApprovedLoans'));


  }

  public function approveLoanRequest(Request $request, $id)
  {

    $this->validate($request,[
      'loanDueDate' => 'required',
    ]);

    $response = Gate::inspect('isLoansManager');
    $central = new CentralController;

    if($response->allowed()){

      $due_date = $request->input('loanDueDate');
      $data = Loan::find($id);
      $applicant = $data->name;
      $applicantEmail = $data->email;
      $amount = $data->loan_amount;
      $data->is_approved = 1;
      $data->approved_by =  Auth::user()->name;
      $data->approved_on = now();
      $data->taken_on = now();
      $data->is_denied = 0;
      $data->denied_by = "";
      $data->denied_on = null;
      $data->due_date = $due_date;
      $data->loan_balance = $amount;

      $approve_result = $data->save();

      if($approve_result)
      {

        $action = "approved ".$applicant."'s loan request
        of ".number_format($amount)."";
        LogsController::logger($action, now());

        if(isset($applicantEmail)){

          if($central->is_connectedToInternet() == 1)
          {
            $companyEmail = config('app.companyEmail');
            $subject = Str::title("approval of your loan request");

            $loanDataArray = array(
              'applicant' => $applicant,
              'amount' => number_format($amount),
              'due_date' => date('d-m-Y',strtotime($due_date)),
              'subject' => $subject,
              'companyEmail' => $companyEmail,
            );

            Mail::send('pages.mail.loan.approval-message',
            $loanDataArray, function($message)
            use ($applicant, $amount, $due_date,
            $subject, $applicantEmail, $companyEmail)
            {
              $message->from($companyEmail, 'Dallington');
              $message->to($applicantEmail)->subject($subject);
            });

            if(Mail::failures()){
              return back()
              ->with('success', "You have successfully ".$action." but an email has not been sent because of poor internet connection");
            }
            else
            {
              return back()
              ->with('success', "You have successfully ".$action." and an email has been automatically sent to ".$applicant." about approval of loan request");
            }

          } //end of if there is internet connection


          else if($central->is_connectedToInternet() == 0)
          {

            return back()
            ->with('success', "You have successfully ".$action." but an email has not been sent because of no internet connection");

          } // end of if there is no internet connection

        } //end of if loanee email is not null

        else {
          return back()
          ->with('success', "You have successfully ".$action." but an email has not been sent because the system has no record of ".$applicant."'s email");
        }

      } // end of if approval is successful

      else
      {
        return back()
        ->with('fail', "Approval for loan request
        of ".$applicant." has failed"
      );
    }

  } // end of Gate response


}



public function denyLoanRequest($id)
{

  $response = Gate::inspect('isLoansManager');
  $central = new CentralController;

  if($response->allowed()){

    $data = Loan::find($id);
    $amount = $data->loan_amount;
    $applicant = $data->name;
    $applicantEmail = $data->email;
    $data->is_denied = 1;
    $data->denied_by =  Auth::user()->name;
    $data->denied_on = now();
    $data->is_approved = 0;
    $data->approved_by = "";
    $data->approved_on = null;
    $data->taken_on = null;
    $data->due_date = null;
    $data->loan_balance = null;

    $deny_result = $data->save();

    if($deny_result)
    {

      $action = "denied ".$applicant."'s loan request
      of ".number_format($amount)."";
      LogsController::logger($action, now());

      if($applicantEmail){

        if($central->is_connectedToInternet() == 1)
        {
          $companyEmail = config('app.companyEmail');
          $subject = Str::title("rejection of your loan request");

          $loanDataArray = array(
            'applicant' => $applicant,
            'amount' => number_format($amount),
            'subject' => $subject,
            'companyEmail' => $companyEmail,
          );

          Mail::send('pages.mail.loan.denial-message',
          $loanDataArray, function($message)
          use ($applicant, $amount, $subject,
          $applicantEmail, $companyEmail)
          {
            $message->from($companyEmail, 'Dallington');
            $message->to($applicantEmail)->subject($subject);
          });

          if(Mail::failures()){
            return back()
            ->with('success', "You have successfully ".$action." but an email has not been sent because of poor internet connection");
          }
          else
          {
            return back()
            ->with('success', "You have successfully ".$action." and an email has been automatically sent to ".$applicant." about approval of loan request");
          }

        } //end of if there is internet connection

        else if($central->is_connectedToInternet() == 0)
        {

          return back()
          ->with('success', "You have successfully ".$action." but an email has not been sent because of no internet connection");

        } // end of if there is no internet connection

      } //end of if loanee email is not null

      else {
        return back()
        ->with('success', "You have successfully ".$action." but an email has not been sent because the system has no record of ".$applicant."'s email");
      }

    } //end of if loan request is denied

    else
    {
      return back()
      ->with('fail', "Denial for loan request
      of ".$applicant." has failed"
    );
  }

} //end of Gate response


}

public function importLoanees(Request $request)
{
  $this->validate($request,
  ['select_file' => 'required|mimes:xls,xlsx'],
  ['select_file.mimes' => 'Please select only excel files to import loanees']
);

$result = Excel::import(new ImportLoanees, request()->file('select_file'));

if($result){

  $action = "imported an excel file of loanees into the system";
  LogsController::logger($action, now());

  return back()
  ->with('success','Loanees from an excel file have been imported successfully');
}
else{
  return back()
  ->with('fail','Loanees not imported!');
}

}








}
