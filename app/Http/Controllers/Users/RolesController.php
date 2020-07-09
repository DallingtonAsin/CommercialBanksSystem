<?php

namespace MillionsSaving\Http\Controllers\Users;

use Illuminate\Http\Request;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Middleware\Authenticate;
use MillionsSaving\User;
use MillionsSaving\Models\User\Role;
use MillionsSaving\Imports\ImportRoles;
use Excel;



class RolesController extends Controller
{

 protected $date_of_action;
 private $tbl;

 public function __construct()
 {
    $this->tbl = 'roles';
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
        $response = Gate::inspect('isSuperAdmin');

        if($response->allowed()){
           $roles = Role::all();
           $number_of_roles = Role::count();
           return view('pages.users.main.roles')
           ->with(compact('roles', 'number_of_roles'));
       }

   }

   public function getPermissions($user_role){

    $value = DB::table($this->tbl)
    ->where('role_id', $user_role)
    ->value('is_admin');

    return $value;

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
          'role' => 'required'
      ]);

       $role = $request->input('role');
       $isAdminPriv = $request->input('isAdmin');
       $isSuperAdminPriv = $request->input('isSuperAdmin');

       ($request->has('isAdmin'))
       ? $isAdminPriv = 1
       : $isAdminPriv = 0;

       ($request->has('isSuperAdmin'))
       ? $isSuperAdminPriv = 1
       : $isSuperAdminPriv = 0;

       $data = new Role();
       $data->role = $role;
       $data->is_admin = $isAdminPriv;
       $data->isSuperAdmin = $isSuperAdminPriv;

       $res = $data->save();
       if($res){

        $action = "recorded role ".$role."";
        LogsController::logger($action, $this->date_of_action);

        return back()->with('success',"You have successfully
          ".$action."!");
    }
    else
    {
        return back()->with('fail',"Role not added");
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
        $this->validate($request, [
            'Role' => 'required'
        ]);

        $oldrole = preg_replace('/\s+/', '', $this->getRole($id));
        $newrole = preg_replace('/\s+/', '', $request->input('Role'));
        if($oldrole != $newrole){
        $updateRes = DB::table($this->tbl)
                ->where('role_id', $id)
                ->update(['role' => $newrole]);
        if($updateRes){
           $action = "updated role from ".$oldrole." to ".$newrole."";
           LogsController::logger($action, $this->date_of_action);
           return back()->with('success',"You have successfully
              ".$action."!");
       }
       else
       {
        return back()->with('fail',"Role ".$oldrole." not updated!");
       }

   }
   else
   {
     return back()->with('warning',"Submitted role ".$newrole." is same as existing role ".$oldrole." so it has not been updated!");
   }


}

public function getRole($id){

    $role = Role::where('role_id', $id)->value('role');
    return $role;
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

       $role = $this->getRole($id);
       $deleteResult = DB::table($this->tbl)
       ->where('role_id', $id)->delete();

       if($deleteResult){

         $action = "deleted role ".$role." from list of roles";
         LogsController::logger($action, $this->date_of_action);
         return back()->with('success',"You have successfully
          ".$action."!");
     }
     else
     {
        return back()->with('fail',"Role ".$role." not deleted!");
    }

}


public function changeAdminPrivileges($id, $priv, $role){

    switch(true){

      case ($priv == true):
      $lowered = Role::where('role_id', $id)->update(['is_admin' => false]);
      if($lowered){

        $action1 = "lowered privileges of role ".$role." to non-admin";
        LogsController::logger($action1, $this->date_of_action);

        return back()->with('success',"You have successfully
          ".$action1."!");

    } else{
        return back()->with('fail','Lowering privileges to non-admin failed!');
    }
    break;

    case ($priv == false):
    $raised = Role::where('role_id', $id)->update(['is_admin' => true]);
    if($raised){

      $action2 = "raised privileges of role ".$role." to Admin";
      LogsController::logger($action2, $this->date_of_action);

      return back()->with('success',"You have successfully
        ".$action2."!");

  } else{
    return back()->with('fail','Raising privileges to Admin failed!');
}

break;

}

}



public function changeSuperAdminPrivileges($id, $priv, $role){

    switch(true){

      case ($priv == true):
      $lowered = Role::where('role_id', $id)->update(['isSuperAdmin' => false]);
      if($lowered){

        $action1 = "lowered privileges of role ".$role." to non-SuperAdmin";
        LogsController::logger($action1, $this->date_of_action);

        return back()->with('success',"You have successfully
          ".$action1."!");

    } else{
        return back()->with('fail','Lowering privileges to non-SuperAdmin failed!');
    }
    break;

    case ($priv == false):
    $raised = Role::where('role_id', $id)->update(['isSuperAdmin' => true]);
    if($raised){

      $action2 = "raised privileges of role ".$role." to Super Admin";
      LogsController::logger($action2, $this->date_of_action);

      return back()->with('success',"You have successfully
        ".$action2."!");

  } else{
    return back()->with('fail','Raising privileges to  SuperAdmin failed!');
}

break;

}

}

 public function importRoles(Request $request)
     {
       $this->validate($request,
        ['select_file' => 'required|mimes:xls,xlsx'],
        ['select_file.mimes' => 'Please select only excel files to import roles']
      );

       $result = Excel::import(new ImportRoles, request()->file('select_file'));

       if($result){

        $action = "imported an excel file of roles into the system";
        LogsController::logger($action, now());

        return back()
               ->with('success','Roles from an excel file have been imported successfully');
      }
      else{
       return back()
              ->with('fail','Roles not imported!');
     }

   }







}
