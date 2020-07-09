<?php

namespace MillionsSaving\Http\Controllers\Accounts\Applications;

use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\CentralController;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use MillionsSaving\User;
use MillionsSaving\Models\User\Role;
use MillionsSaving\Models\Applications\Application;

class ApplicationsController extends Controller
{


  public function __construct(){

    $this->middleware('auth');

  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }


    public function approved()
    {

      $gateX = Gate::inspect('isAdmin');
      $gateY = Gate::inspect('isMember');
      $memberId = Auth::user()->id;

      if($gateX->allowed()){

       $rows = Application::where('is_approved', 1)
       ->paginate();
       $number_of_approvedApplications
       = Application::where('is_approved', 1)
       ->count();
     }

     if($gateY->allowed()){

       $rows = Application::where('is_approved', 1)
       ->where('member_id', $memberId)
       ->get();
       $number_of_approvedApplications
       = Application::where('is_approved', 1)
       ->where('member_id', $memberId)
       ->count();
     }


     return view('pages.accounts.applications.approved-applications')
     ->with(compact('rows','number_of_approvedApplications'));
   // $res = $this->generateAccountNo();
   // dd($res);
   }

   public function pending()
   {

    $gateX = Gate::inspect('isAdmin');
    $gateY = Gate::inspect('isMember');
    $memberId = Auth::user()->id;

    if($gateX->allowed()){

     $rows = DB::table('applications')->where('is_approved', 0)
     ->where('is_denied', 0)
     ->get();

     $number_of_pendingApplications
     = Application::where('is_approved', 0)
     ->where('is_denied', 0)
     ->count();

   }

   if($gateY->allowed()){
     $rows = Application::where('is_approved', 0)
     ->where('is_denied', 0)
     ->where('member_id', $memberId)
     ->get();

     $number_of_pendingApplications
     = Application::where('is_approved', 0)
     ->where('is_denied', 0)
     ->where('member_id', $memberId)
     ->count();

   }

   return view('pages.accounts.applications.pending-applications')
   ->with(compact('rows','number_of_pendingApplications'));
 }

 public function rejected()
 {

   $gateX = Gate::inspect('isAdmin');
   $gateY = Gate::inspect('isMember');
   $memberId = Auth::user()->id;

   if($gateX->allowed()){
     $denied_apps = Application::where('is_denied', 1)
     ->get();
     $number_of_deniedApplications
     = Application::where('is_denied', 1)
     ->count();
   }

   if($gateY->allowed()){

     $denied_apps = Application::where('is_denied', 1)
     ->where('member_id', $memberId)
     ->get();
     
     $number_of_deniedApplications
     = Application::where('is_denied', 1)
     ->where('member_id', $memberId)
     ->count();
   }

   return view('pages.accounts.applications.denied-applications')
   ->with(compact('denied_apps','number_of_deniedApplications'));





 }


 public function accountApplicationform()
 {
  return view('pages.accounts.forms.general-account');
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



    protected function hasAccount($userId, $account)
    {

      switch (true) {
        case $account == 'education':
        $account_no = User::where('id', $userId)->value('acc_noE');
        break;
        case $account == 'retirement':
        $account_no = User::where('id', $userId)->value('acc_noR');
        break;
        case $account == 'shares':
        $account_no = User::where('id', $userId)->value('acc_noS');
        break;
      }

      ($account_no)
      ? $bool = true
      : $bool = false;

      return $bool;

    }


    protected function hasApplied($memberId, $accountType)
    {

      $is_rowAvailable = Application::where('member_id', $memberId)
      ->where('type', $accountType)
      ->first();

      ($is_rowAvailable)
      ? $has_applied = true
      : $has_applied = false;
      
      return $has_applied;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $this->validate($request, [
       'firstName' => 'required',
       'lastName' => 'required',
       'address' => 'required',
       'city' => 'required',
       'state' => 'required',
       'zipcode' => 'required',
       'PrimaryTelNo' => 'required',
       'occupation' => 'required',
       'company' => 'required',
       'dateOfBirth' => 'required',
       'email' => 'required',
       'Gender' => 'required',

     ]);

      $data = new Application;
      $central = new CentralController;
      $user_id = Auth::user()->id;
      $account_names = Auth::user()->acc_name;
   
      $first_name = trim($request->input('firstName'));
      $last_name = trim($request->input('lastName'));
      $gender = trim($request->input('Gender'));
      $address= trim($request->input('address'));
      $dob = trim($request->input('dateOfBirth'));
      $occupation = trim($request->input('occupation'));
      $company = trim($request->input('company'));
      $account = trim($request->input('account'));

      ($request->has('email'))
      ? $applicantEmail = $request->input('email')
      : $applicantEmail = "";

      $isDone = $this->hasAccount($user_id, $account);
      $isAppAvail = $this->hasApplied($user_id, $account);

      if($request->has('middleName')){
        $data->middle_name = $middle_name = trim($request->input('middleName'));
        $name = $applicant = $first_name." ".$middle_name." ".$last_name;

      }
      else{
       $name = $applicant = $first_name." ".$last_name;
     }


     if($isDone === false){

      if($isAppAvail === false){

        $data->first_name = $first_name;
        $data->last_name = $last_name;
        $data->gender = $gender;
        $data->address = $address;
        $data->type = $account;
        $data->occupation = $occupation;
        $data->date_of_birth = $dob;
        $data->submitted_on = now();

        if($request->has('accountApp'))
        {
          $mainAccNo = trim($request->input('MainA/CNo'));
          $dataArr = $this->getUserdata($mainAccNo);
          $data->member_id = $dataArr['id'];
          $data->email = $dataArr['email'];
          $data->state = $dataArr['state'];
          $data->city = $dataArr['city'];
          $data->company = $dataArr['company'];
          $data->zipcode = $dataArr['zipcode'];
          $data->tel_no = $dataArr['tel_no'];
          $data->alt_telno = $dataArr['alt_telno'];
          $data->date_of_birth = $dataArr['dob'];
        }
        else {


          if($request->has('company')){
            $data->company = trim($request->input('company'));
          }

          ($request->has('city'))
          ? $data->city = trim($request->input('city'))
          : $data->city = "";

          ($request->has('state'))
          ? $data->state = trim($request->input('state'))
          : $data->state = "";

          ($request->has('zipcode'))
          ? $data->zipcode = trim($request->input('zipcode'))
          : $data->zipcode = "";

          ($request->has('PrimaryTelNo'))
          ? $data->tel_no = trim($request->input('PrimaryTelNo'))
          : $data->tel_no = "";

          ($request->has('Mobile2'))
          ? $data->alt_telno = trim($request->input('Mobile2'))
          : $data->alt_telno= "";

          $data->email = $applicantEmail;
        }

        $result = $data->save();
        if($result){

          $action =  "applied for ".$account." account";
          LogsController::logger($action, now());
          if($account == 'membership')
          {
            return redirect('/home');
          }
          else
          {

            LogsController::logger($action, now());

            if(isset($applicantEmail)){

              if($central->is_connectedToInternet() == 1)
              {
                $companyEmail = config('app.companyEmail');
                $subject = Str::title("Submission of an application for account ".$account."");

                $applicantDataArray = array(
                 'applicantEmail' => $applicantEmail,
                 'applicant' => $applicant,
                 'account' => $account,
                 'subject' => $subject,
                 'companyEmail' => $companyEmail,
               );

                Mail::send('pages.mail.membership_application_message', 
                 $applicantDataArray, function($message) 
                 use ($applicant, $applicantEmail, $account, $subject, $companyEmail)
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
                  ->with('success', "You have successfully ".$action." and an email has been automatically sent to you, ".$applicant." about application for account ".$account.""); 
                } 

              }
              else if($central->is_connectedToInternet() == 0)
              {

                return back()
                ->with('success', "You have successfully ".$action." but an email has not been sent because of no internet connection"); 

              }
            }
            else{
              dd($applicantEmail);
              dd("No email detected");
              return back()
              ->with('success', "You have successfully ".$action."");
            }

          }
        }
        else{
          return back()
          ->with('fail', "Application not submitted");
        }
      }

      else{
       return back()
       ->with('fail', "".$name.", You have already applied for ".$account." account, so there is no need to re-apply!");
     }


   }
   else{
     return back()
     ->with('warning', "".$name.", You already have ".$account." account so no need to apply for this account!");
   }


 }

 protected function getUserdata($account_no)
 {

  $data = User::where('acc_noM', $account_no)->get();
  $dataArray = array();

  foreach ($data as $row) {

    $dataArray = array(
      'id' => $row->id,
      'first_name' => $row->first_name,
      'middle_name' => $row->middle_name,
      'last_name' => $row->last_name,
      'name' => $row->name,
      'user_role' => $row->user_role,
      'email' => $row->email,
      'tel_no' => $row->tel_no,
      'alt_telno' => $row->alt_telno,
      'address' => $row->address,
      'city' => $row->city,
      'state' => $row->state,
      'zipcode' => $row->zipcode,
      'company' => $row->company,
      'dob' => $row->date_of_birth,
      'acc_name' => $row->acc_name,
    );

  }

  return $dataArray;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     $GateAdmin = Gate::inspect('isAdmin');

     if($GateAdmin->allowed()){
       $data = Application::find($id);
       $type = $data->type;
       $state_x = $data->is_approved;
       $state_y = $data->is_denied;
       $applicant = $data->first_name." ".$data->last_name;

       switch (true) {
         case $state_x == true:
         $state = 'an approved';
         break;
         case $state_y == true:
         $state = 'a denied';
         break;
         case ($state_x == false && $state_y == false):
         $state = 'a pending';
         break;
       }

       $res = $data->delete();
       if($res){
        $action = "deleted ".$state." application of ".$applicant."";
        LogsController::logger($action, now());
        return back()
        ->with('success', "You have successfully ".$action."");
      }else{
       return back()
       ->with('fail', "Deletion of ".$state." application
        has failed");
     }
   }


 }


 protected function generatePin($pin)
 {
  if($pin == null){
    $pin = random_int(1000, 9999);
    $encrypted = encrypt($pin);
  }
  else{
    $encrypted = $pin;
  }

  return $encrypted;
}


protected function approveApplication($id, $account)
{

  $response = Gate::inspect('isAdmin');
  $central = new CentralController();

  if($response->allowed()){

    $data = Application::find($id);

    ($data->middle_name)
    ? $applicant = Str::title($data->first_name." ".$data->middle_name." ".$data->last_name)
    : $applicant = Str::title($data->first_name." ".$data->last_name);

    $member = new User;
    $user = User::where('id', $data->member_id);

    if(strtolower($account) == 'membership'){

      $member->first_name = $fname = Str::title($data->first_name);
      $member->last_name = $lname = Str::title($data->last_name);

      if($data->middle_name)
      {
        $mname = $data->middle_name;
        $member->middle_name = Str::title($mname);
        $member->name = $name = $applicant;
      }
      else{
        $member->name = $name = $applicant;
      }

      $member->username = $username = 
      strtolower(Str::random(4).".".$fname);
      
      $member->gender = $data->gender;
      $member->email = $applicantEmail = $data->email;
      $member->user_role = $this->getMemberRoleId();
      $member->occupation = $data->occupation;
      $member->tel_no = $data->tel_no;
      $member->alt_telno = $data->alt_telno;
      $member->address = $data->address;
      $member->city = $data->city;
      $member->state = $data->state;
      $member->zipcode = $data->zipcode;
      $member->company = $data->company;
      $member->date_of_birth = $data->date_of_birth;
      $member->acc_name = $name;
      $member->acc_noM = $acc_no = $this->generateAccountNumber($account);
      $member->pin = $pinNumber = $this->generatePin($member->pin);
      $member->active = 1;
      $defaultPassword = Str::random(8);
      $member->password = Hash::make($defaultPassword);
      $approvedMembership = $member->save();
    }

    else
    {

      switch (true) {
       case strtolower($account) == 'education':
       $acc_no = $this->generateAccountNumber($account);
       $user->update(['acc_noE' => $acc_no]);
       break;
       case strtolower($account) == 'retirement':
       $acc_no = $this->generateAccountNumber($account);
       $user->update(['acc_noR' => $acc_no]);
       break;
       case strtolower($account) == 'shares':
       $acc_no = $this->generateAccountNumber($account);
       $user->update(['acc_noS' => $acc_no]);
       break;
     }
     $pinNumber = $this->generatePin($member->pin);
     $username = $this->getUsername($applicantEmail);

   }

   $data->is_approved = 1;
   $data->approved_by =  Auth::user()->name;
   $data->approved_on = now();
   $data->is_denied = 0;
   $data->denied_by = "";
   $data->denied_on = null;
   $approve_result = $data->save(); 

   if($approve_result)
   {

    $action = "approved ".$applicant."'s application for ".$account." account";
    LogsController::logger($action, now());
    if($central->is_connectedToInternet() == 1)
    {
      $companyEmail = config('app.companyEmail');
      $subject = Str::title("approval of your application for ".$account." account");


      $decryptedPinNo = decrypt($pinNumber);
      $applicantDataArray = array(
       'applicant' => $applicant,
       'username' => $username,
       'defaultPassword' => $defaultPassword,
       'applicantEmail' => $applicantEmail,
       'account' => $account,
       'acc_no' => $acc_no,
       'pin' => $decryptedPinNo,
       'subject' => $subject,
       'companyEmail' => $companyEmail,
     );

      Mail::send('pages.mail.approval-message', 
       $applicantDataArray, function($message) 
       use ($applicant, $username, $defaultPassword, $applicantEmail,
        $companyEmail, $subject, $account, $acc_no, $decryptedPinNo)
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
        ->with('success', "You have successfully ".$action." and an email has been automatically sent to ".$applicant." about approval of ".$account." application"); 
      } 

    }


    else if($central->is_connectedToInternet() == 0)
    {

      return back()
      ->with('success', "You have successfully ".$action." but an email has not been sent because of no internet connection"); 

    }

  }

  else if(!$approve_result)
  {
    return back()
    ->with('fail', "Approval for an application
      of ".$applicant." has failed"
    );
  }

}


}

protected function getUsername($email)
{
  $username = User::where('email', $email)->value('username');
  return $username;
}


protected function denyApplication(Request $request, $id, $account)
{

  $central = new CentralController();

  $response = Gate::inspect('isAdmin');
  if($response->allowed()){

    $data = Application::find($id);
    $user = User::where('id', $data->member_id);
    $applicantEmail = $data->email;
    
    if($data->middle_name){
      $mname = $data->middle_name;
      $applicant = $data->first_name." ".$mname." ".$data->last_name;
    }
    else
    {
      $applicant = $data->first_name." ".$data->last_name;
    }

    $accountType = strtolower($account);
    if($accountType == 'membership')
    {
      User::where('email', $applicantEmail)->delete();
    }
    else
    {

      switch (true) {
       case $accountType == 'education':
       $user->update(['acc_noE' => null]);
       break;
       case $accountType == 'retirement':
       $user->update(['acc_noR' => null]);
       break;
       case $accountType == 'shares':
       $user->update(['acc_noS' => null]);
       break;
     }

   }

   ($request->has('reason'))
   ? $reason = trim($request->input('reason'))
   : $reason = 'unspecified reason';


   $data->is_denied = 1;
   $data->denied_by =  Auth::user()->name;
   $data->denied_on = now();
   $data->is_approved = 0;
   $data->approved_by = "";
   $data->approved_on = null;

   $approve_result = $data->save(); 

   if($approve_result)
   {
    $action = "denied ".$applicant."'s application for ".$accountType." account type";
    LogsController::logger($action, now());
    if($central->is_connectedToInternet() == 1)
    {
      $companyEmail = config('app.companyEmail');
      $subject = Str::title("denial of your application for ".$account." account type ");

      $applicantDataArray = array(
       'applicant' => $applicant,
       'applicantEmail' => $applicantEmail,
       'account' => $account,
       'subject' => $subject,
       'reason' => $reason,
       'companyEmail' => $companyEmail,
     );

      Mail::send('pages.mail.denial-message', 
       $applicantDataArray, function($message) 
       use ($applicant, $applicantEmail, $reason,
        $companyEmail, $subject, $account)
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
        ->with('success', "You have successfully ".$action." and an email has been automatically sent to ".$applicant." about denial of ".$account." application"); 
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
    ->with('fail', "Denial for an application
      of ".$applicant." has failed"
    );
  }

  } //end of if Gate allows


}

protected function generateAccountNumber($account)
{

  $users = DB::table('users')->get();
  $account_noM = random_int(1000000000, 5000000000);
  $account_noE = random_int(5000000001, 6000000000);
  $account_noR = random_int(6000000001, 8000000000);
  $account_noS = random_int(8000000001, 9500000000);

  $accountNoMArr = $accountNoEArr = 
  $accountNoRArr = $accountNoSArr =  array();

  foreach ($users as $user) {

    array_push($accountNoMArr, $user->acc_noM);
    array_push($accountNoEArr, $user->acc_noE);
    array_push($accountNoRArr, $user->acc_noR);
    array_push($accountNoSArr, $user->acc_noS);

  }

  switch (true) {
    case $account=='membership':
    $account_no = $this->regenerateAccountNo($accountNoMArr, $account_noM,1000000000, 5000000000);
    break;

    case $account=='education':
    $account_no = $this->regenerateAccountNo($accountNoEArr, $account_noE,5000000001, 6000000000);
    break;

    case $account=='retirement':
    $account_no = $this->regenerateAccountNo($accountNoRArr, $account_noR,6000000001, 8000000000);
    break;

    case $account=='shares':
    $account_no = $this->regenerateAccountNo($accountNoSArr, $account_noS,8000000001, 9500000000);
    break;

  }

  return $account_no;
}


private function regenerateAccountNo($dataArr, $account_no, $rangeX, $rangeY)
{
 (Arr::has($dataArr, $account_no))
 ? $account_number = random_int($rangeX, $rangeY)
 : $account_number = $account_no;

 return $account_number; 

}

protected function truncateApprovedApps()
{
  $response = Gate::inspect('isAdmin');

  if($response->allowed()){

   $result = Application::where('is_approved', 1)
   ->delete();

   if($result)
   {
    $action = "deleted all approved applications";
    LogsController::logger($action, now());
    return back()
    ->with('success', "You have successfully ".$action."");
  }
  else{
    return back()
    ->with('fail', "Deletion of all approved applications has failed"
  );
  }
}
}

protected function truncateDeniedApps()
{
  $response = Gate::inspect('isAdmin');

  if($response->allowed()){

   $result = Application::where('is_denied', 1)
   ->delete();

   if($result)
   {
    $action = "deleted all rejected applications";
    LogsController::logger($action, now());
    return back()
    ->with('success', "You have successfully ".$action."");
  }
  else{
    return back()
    ->with('fail', "Deletion of all rejected applications has failed"
  );
  }
}
}

protected function truncatePendingApps()
{
  $response = Gate::inspect('isAdmin');

  if($response->allowed()){

   $result = Application::where('is_approved', 0)
   ->where('is_denied', 0)
   ->delete();

   if($result)
   {
    $action = "deleted all pending applications";
    LogsController::logger($action, now());
    return back()
    ->with('success', "You have successfully ".$action."");
  }
  else{
    return back()
    ->with('fail', "Deletion of all pending applications has failed"
  );
  }
}
}

protected function getApplicant($id)
{

  $first_name = Application::where('member_id', $id)
  ->value('first_name');
  $last_name = Application::where('member_id', $id)
  ->value('last_name');
  $applicant = $first_name." ".$last_name;
  return $applicant;

}

protected function getMemberRoleId()
{

  $id = Role::where('role', 'Member')->value('role_id');
  return $id;
}









}
