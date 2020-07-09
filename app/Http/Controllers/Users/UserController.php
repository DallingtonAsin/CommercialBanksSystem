<?php

namespace MillionsSaving\Http\Controllers\Users;

use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
use MillionsSaving\User;
use MillionsSaving\Models\User\Role;
use MillionsSaving\Models\Accounts\EducationSaving;
use MillionsSaving\Models\Accounts\MainSaving;
use MillionsSaving\Models\Accounts\SharesSaving;
use MillionsSaving\Models\Accounts\RetirementSaving;
use MillionsSaving\Imports\ImportStaff;
use Excel;


class UserController extends Controller
{
  protected $date_of_action;
  private $staff;
  public function __construct(){
    $this->date_of_action = now();

    $this->staff = ['Administrator', 'SaccoManager',
                    'Teller','Accountant','Cashier'];
    $this->middleware('auth');
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
     $users = User::all();
     $number_of_users = User::count();
     return view('pages.users.main.index')
     ->with(compact('users','number_of_users'));
   }
/**
 Display listing of members in the system

*/
 public function membersIndex()
 {
  $role = 'member';
  $id = $this->getUserId($role);
  $users = User::where('user_role', $id)->get();
  $number_of_members = User::where('user_role', $id)->count();
  return view('pages.users.main.members')
  ->with(compact('users','number_of_members'));
}


public function staffIndex()
{
  $roleIdArr = $rolesArr = array();
  foreach($this->staff as $role){
    $roleIdArr[] = $this->getUserId($role);
  }

  $users = User::whereIn('user_role', $roleIdArr)->get();
  foreach ($users as $user) {
    $rolesArr[] = $this->getRole($user->user_role);
  }

  $number_of_admin = User::whereIn('user_role', $roleIdArr)->count();
  return view('pages.users.main.admin')
  ->with(compact('users','number_of_admin','rolesArr'));
}


public function inactiveUsersbyAdmin()
{
  $users = User::where('active', 0)
  ->whereNotIn('inactivated_by',['system'])
  ->whereNotNull('inactivated_by')
  ->get();

  $number_of_inactiveUsers = User::where('active', 0)
  ->whereNotIn('inactivated_by',['system'])
  ->whereNotNull('inactivated_by')
  ->count();

  return view('pages.users.main.inactiveusers-by-admin')
  ->with(compact('users', 'number_of_inactiveUsers'));

}

public function inactiveUsersbySystem()
{

  $users = User::where('active', 0)
  ->whereNotNull('inactivated_by')
  ->where('inactivated_by', 'system')
  ->get();

  $number_of_inactiveUsers = User::where('active', 0)
  ->whereNotNull('inactivated_by')
  ->where('inactivated_by', 'system')
  ->count();

  return view('pages.users.main.inactiveusers-by-system')
  ->with(compact('users', 'number_of_inactiveUsers'));

}

protected function getRole($id)
{
  $role = Role::where('role_id', $id)->value('role');
  return $role;
}

protected function getUserId($role)
{
  $id = Role::where('role', $role)->value('role_id');
  return $id;
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
      $this->validate($request, [
        'firstName' => 'required',
        'lastName' => 'required',
        'username' => 'required',
        'gender' => 'required',
        'email' => 'required',
        'occupation' => 'required',
        'mobileno' => 'required',
        'address' => 'required',
        'city' => 'required',
        'state' => 'required',
        'dob' => 'required',
      ]);

      $data = User::find($id);
      $user = $data->name;
      $data->first_name = $fname = Str::title($request->input('firstName'));
      $data->last_name = $lname = Str::title($request->input('lastName'));
      $data->username = $request->input('username');
      $data->gender = $request->input('gender');
      $data->email = $request->input('email');
      $data->occupation = $request->input('occupation');
      $data->tel_no = $request->input('mobileno');
      $data->address = $request->input('address');
      $data->city = $request->input('city');
      $data->state = $request->input('state');
      $data->date_of_birth = $request->input('dob');

      if($request->has('middleName')){
        $data->middle_name = $mname =
        Str::title($request->input('middleName'));
        $name = Str::title($fname." ".$mname." ".$lname);
      }
      else{
        $name = Str::title($fname." ".$lname);
      }

      $data->name = $name;
      if($request->has('company')){
        $data->company  = $request->input('company');
      }
      if($request->has('mobileno2')){
        $data->alt_telno  = $request->input('mobileno2');
      }

      $res = $data->save();

      if($res){
        $action = "updated ".$user."'s account";
        LogsController::logger($action, $this->date_of_action);
        return back()
        ->with('success', "You have successfully ".$action."");
      }
      else
      {
        return back()
        ->with('fail', "Update of user account failed");
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

      $data = User::find($id);
      $user = $data->name;

      EducationSaving::where('member_id', $id)->delete();
      MainSaving::where('member_id', $id)->delete();
      SharesSaving::where('member_id', $id)->delete();
      RetirementSaving::where('member_id', $id)->delete();

      $res =  $data->delete();

      if($res)
      {
        $action =  "deleted ".$user." from the system";
        LogsController::logger($action, $this->date_of_action);
        return back()
        ->with('success', "You have successfully ".$action."");
      }
      else
      {
        return back()
        ->with('fail', "
         deletion of ".$user." from the system has failed");
      }


    }


    public function ChangeAccountStatus($id, $status, $name){
      $admin = Auth::user()->name;
      switch(true){

        case ($status == true):
        $deactivated = User::where('id', $id)
        ->update(['active' => false, 'inactivated_by' => $admin]);
        if($deactivated){

          $action = "Deactivated user ".$name."'s account";
          LogsController::logger($action, $this->date_of_action);

          return back()->with('success',"You have successfully
            deactivated ".$name."'s account!");

        } else{
          return back()->with('fail','Account deactivation failed!');
        }
        break;

        case ($status == false):
        $activated = User::where('id', $id)
        ->update(['active' => true, 'inactivated_by' => $admin]);
        if($activated){

          $action = "Activated user ".$name."'s account";
          LogsController::logger($action, $this->date_of_action);

          return back()->with('success',"You have successfully
            activated ".$name."'s account!");

        } else{
          return back()->with('fail','Account deactivation failed!');
        }

        break;

      }

    }

public function importStaff(Request $request)
     {
       $this->validate($request,
        ['select_file' => 'required|mimes:xls,xlsx'],
        ['select_file.mimes' => 'Please select only excel files to import staff']
      );

       $result = Excel::import(new ImportStaff, request()->file('select_file'));

       if($result){

        $action = "imported an excel file of staff into the system";
        LogsController::logger($action, now());

        return back()
               ->with('success','Staff members from an excel file have been imported successfully');
      }
      else{
       return back()
              ->with('fail','Staff members not imported!');
     }

   }








  }
