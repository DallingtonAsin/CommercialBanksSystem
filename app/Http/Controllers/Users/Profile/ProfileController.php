<?php

namespace MillionsSaving\Http\Controllers\Users\Profile;

use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\CentralController;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use MillionsSaving\User;

class ProfileController extends Controller
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
        return view('pages.users.profile.profile');

    }

    public function profileSettings()
    {
        return view('pages.users.profile.profile-settings');
    }

    public function accountSettings()
    {
        return view('pages.users.profile.account-settings');
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

    protected function indexOfItem()
    {

    }

    protected function updateAccount(Request $request, $id)
    {
       $this->validate($request,[
        'pin' => 'required|max:4',
    ]);

       $user = User::find($id);
       $user->pin = encrypt($request->input('pin'));
       $res = $user->save();

       if($res)
        {
        $gender = $this->getGender(Auth::user()->id);
        $action = "updated ".$gender." account";
        LogsController::logger($action, now());
        $act = Str::replaceFirst($gender, 'your', $action);

        return back()->with('success',
            "You have successfully ".$act."");
    }
    else
    {
        return  back()->with('fail','Account update failed');
    }

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
        $this->validate($request,[
            'Username' => 'required',
            'Email' => 'required',
            'Contact' => 'required',
            'Address' => 'required',
        ]);

        $user = User::find($id);
        $old_username = $user->username;
        $user->username = $new_username = $request->input('Username');
        $user->email = $request->input('Email');
        $user->tel_no = $request->input('Contact');
        $user->address = $request->input('Address');
        $OldPassword = $request->input('OldPassword');
        $NewPassword = $request->input('NewPassword');
        $ConfirmPassword = $request->input('PasswordConfirm');

        if($request->hasfile('image')){

            $this->validate($request, [
               'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           ]);

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); //getting image extension
            $filename = time().'.'.$extension;
            $file->move("uploads/images/".Auth::user()->role ."",$filename);
            $user->image = $filename;
        }
        else
        {
            //$user->image = '';
        }

        $central = new CentralController();
        $arr =  $this->getUsernamesArr();

        if(in_array($old_username, $arr))
        {
            for($i=0; $i<count($arr); $i++){
               if($arr[$i] == $old_username){
                  $index = $i;
                  break;
              }
              else{
                $index = -1;
            }
        }
        $newArr = Arr::except($arr, $index);

    }
    else {
        $newArr = $arr;
    }

    $bool = $central->is_inArr($newArr, $new_username);

    if($bool === true){
      return back()->with("fail", "this username ".$new_username." is already taken up, please enter a different one!");
  }
  else if($bool === false) {

    if(isset($OldPassword) && isset($NewPassword) && isset($ConfirmPassword) ){

        if(Hash::check($OldPassword, Auth::user()->password)){
         if($NewPassword == $ConfirmPassword){
             $user->password = Hash::make($ConfirmPassword);
         }
         else
         {
            return back()->with("fail", "Your new passwords
                don't match, please enter matching passwords");
        }
    }
    else
    {
        return back()->with("fail", "You have entered old password that
            doesn't match the current stored password, please try again!");
    }
}
else
{
    $user->password = Auth::user()->password;
}

$result = $user->save();

if($result) {
    $gender = $this->getGender(Auth::user()->id);
    $action = "updated ".$gender." profile";
    LogsController::logger($action, now());
    $actionx = Str::replaceFirst($gender, 'your', $action);
    return back()->with('success',
        "You have successfully ".$actionx."");
}
else
{
    return  back()->with('fail','Profile update failed');
}
}

}

protected function getUsernamesArr()
{

  $usernames = User::pluck('username');
  $dataArr = array();
  foreach($usernames as $username)
  {
    $dataArr[] = $username;
}
return $dataArr;

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

    public function getGender($id){

       $gender = User::where('id', $id)->value('gender');
       (strtolower($gender) == 'male')
       ? $value = 'his'
       : $value = 'her';
       return $value;

   }



}
