<?php


namespace MillionsSaving\Http\View\Composers;
use Illuminate\View\View;
use MillionsSaving\Models\User\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use MillionsSaving\User;

class Telescope{

  public function compose(View $view){
    if(Auth::check()){
      $view->with('is_admin', $this->getPermissions(Auth::user()->user_role));
      $view->with('decryptedUserPin', $this->decryptPin());
    }
    else
    {
      return redirect('/home');
    }
  }

  public function getPermissions($user_role){

    $value = DB::table('roles')
    ->where('role_id', $user_role)
    ->value('is_admin');

    return $value;

  }

  protected function decryptPin()
  {

    if(Auth::check()){
      $encryptedValue = Auth::user()->pin;
      try {

        ($encryptedValue)
        ? $decrypted = decrypt($encryptedValue)
        : $decrypted = null;
        return $decrypted;



      } catch (DecryptException $e) {
    
      }


    }

    
  }

  


}
