<?php

namespace MillionsSaving\Http\Controllers\Users;

use Illuminate\Http\Request;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\CentralController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Support\Authenticate;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use MillionsSaving\User;
use MillionsSaving\Models\Applications\Application;

class MembershipController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('pages.users.main.membershipRegistration');
   }

   protected function getMembership($year)
   {
    $number = DB::table('users')
    ->whereYear('created_at', $year)->count();
    return $number;
}

protected function getAccount($table, $year)
{

    $totl = DB::table($table)
    ->whereYear('date', $year)->sum('deposit');

    return $totl;
}


protected function getIncrement($x, $y)
{
    try{
        if($x==0){
            $result = null;
        }
        else{
        $increment = (($y-$x)/$x)*100;
        $result = $increment;
    }
    }
    catch(Exception $ex){
        $result = null;
    }
    return $result;
}



public function generalPerformance()
{



    $year = date('Y');
    $dataArr = $membershipArr = $yearsArr
    = $sharesArr = $educArr = $mainArr = $retirementArr = array();

    for($i=0; $i<2; $i++){

        $no = $this->getMembership($year-$i);
        $yearsArr[] = $year-$i;

        array_push($membershipArr,$no);
        array_push($sharesArr, $this->getAccount("sharessavingacc", $year-$i));
        array_push($educArr, $this->getAccount("educationsavingacc", $year-$i));
        array_push($mainArr, $this->getAccount("mainsavingacc", $year-$i));
        array_push($retirementArr, $this->getAccount("retirementsavingacc", $year-$i));

    }

    //dd($membershipArr[0]);

    $percent = round($this->getIncrement($membershipArr[1], $membershipArr[0]));
    array_push($membershipArr, $percent);

    $percentage = round($this->getIncrement($sharesArr[1], $sharesArr[0]));
    array_push($sharesArr, $percentage);

    $percentX = round($this->getIncrement($educArr[1], $educArr[0]));
    array_push($educArr, $percentX);

    $percentY = round($this->getIncrement($mainArr[1], $mainArr[0]));
    array_push($mainArr, $percentY);

    $percentZ = round($this->getIncrement($retirementArr[1], $retirementArr[0]));
   array_push($retirementArr, $percentZ);

    return view('pages.main.admin.general-performance')
    ->with(compact('membershipArr', 'sharesArr','yearsArr',
       'educArr', 'mainArr', 'retirementArr'));

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
     $this->validate($request, [
         'firstName' => 'required',
         'lastName' => 'required',
         'Gender' => 'required',
         'address' => 'required',
         'dateOfBirth' => 'required',
         'occupation' => 'required',

     ]);

     $data = new Application;
     $first_name = $request->input('firstName');
     $last_name = $request->input('lastName');
     $gender = $request->input('Gender');
     $address= $request->input('address');
     $dob = $request->input('dateOfBirth');
     $occupation = $request->input('occupation');
     $account = $request->input('account');

     $data->first_name = $first_name;
     $data->last_name = $last_name;
     $data->gender = $gender;
     $data->address = $address;
     $data->type = $account;
     $data->occupation = $occupation;
     $data->date_of_birth = $dob;
     $data->submitted_on = now();

     if($request->has('middleName')){
        $data->middle_name = $middle_name = $request->input('middleName');
    }

    if($request->has('email')){
        $data->email = $applicantEmail = $request->input('email');
    }

    if($request->has('company')){
        $data->company = $request->input('company');
    }

    ($request->has('city'))
    ? $data->city = $request->input('city')
    : $data->city = "";

    ($request->has('state'))
    ? $data->state = $request->input('state')
    : $data->state = "";

    ($request->has('zipcode'))
    ? $data->zipcode = $request->input('zipcode')
    : $data->zipcode = "";

    ($request->has('PrimaryTelNo'))
    ? $data->tel_no = $request->input('PrimaryTelNo')
    : $data->tel_no = "";

    ($request->has('Mobile2'))
    ? $data->alt_telno = $request->input('Mobile2')
    : $data->alt_telno= "";


    $result = $data->save();

    if($result){

        $action =  "applied for ".$account." account";

        $dataArr = array(
           'name' => $first_name." ".$last_name,
           'role' => 'applicant',
           'action' => $action,
       );

        LogsController::logApplicants($dataArr);
        $central = new CentralController();
          (isset($middle_name))
            ? $applicant = $first_name." ".$middle_name." ".$last_name
            : $applicant = $first_name." ".$last_name;

        if($central->is_connectedToInternet() == 1)
        {
            $account = "membership";
            $companyEmail = config('app.companyEmail');
            $subject = 'Application for Membership Account';

            $applicantDataArray = array(
             'applicant' => $applicant,
             'applicantEmail' => $applicantEmail,
             'account' => $account,
             'subject' => $subject,
             'companyEmail' => $companyEmail,
         );

            Mail::send('pages.mail.membership_application_message',
             $applicantDataArray, function($message)
             use ($applicant, $applicantEmail,$account,
              $companyEmail, $subject)
             {
                $message->from($companyEmail, 'Dallington');
                $message->to($applicantEmail)->subject($subject);
            });

            if(Mail::failures()){
               return back()
               ->with('success', "You have successfully ".$action.". You will be sent an email about your application status in a few days from now, thank you!");

           }
           else{
            return back()
            ->with('success', "You have successfully ".$action.". You have also received an email about this application and you will be sent an email about your application status in a few days from now, thank you!");

        }

    }


    else if($central->is_connectedToInternet() == 0)
    {

     return back()
     ->with('success', "You have successfully ".$action.". You will be sent an email about your application status in a few days from now, thank you!");

 }

}
else
{
    return back()
    ->with('fail', "Application not submitted");
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
        //
    }
}
