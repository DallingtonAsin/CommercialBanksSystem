<?php


 namespace MillionsSaving\Http\View\Composers;
 use Illuminate\View\View;
use MillionsSaving\Models\User\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use MillionsSaving\User;

class ComposerOverview{

  public function compose(View $view){

  if(Auth::check()){
   $id = Auth::user()->id;
   $view->with('user_role', $this->getUserRole());
   $view->with('accArr', $this->getAccountNos($id));
 }
 else{
  return redirect('/home');
 }

  }

    public function getUserRole()
    {
      $userRole = DB::table('roles')
                 ->where('role_id', Auth::user()->user_role)
                  ->value('role');
      return $userRole;

    }

    public function getAccountNos($memberId){

      $acc_noM = User::where('id', $memberId)
                 ->value('acc_noM');
      $acc_noE = User::where('id', $memberId)
                 ->value('acc_noE');
      $acc_noR = User::where('id', $memberId)
                 ->value('acc_noR');
      $acc_noS = User::where('id', $memberId)
                 ->value('acc_noS');

      $dataArr = array(
                  'accnoM' => $acc_noM,
                  'accnoE' => $acc_noE,
                  'accnoR' => $acc_noR,
                  'accnoS' => $acc_noS,
                   );

      return $dataArr;
    }


}
