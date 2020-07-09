<?php

namespace MillionsSaving\Providers;

use MillionsSaving\Policies\EventPolicy;
use MillionsSaving\CustomModels\Event;
use MillionsSaving\User;
use MillionsSaving\Models\User\Role;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\Response;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

      Event::class => EventPolicy::class,
        // 'MillionsSaving\Model' => 'MillionsSaving\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
      $this->registerPolicies();

        // define a admin user user_role
        // returns true if user user_role is set to admin

      Gate::define('isAdmin', function($user){

       $arr = $this->getPermissions($user->user_role);
       $permitX = $arr['isAdmin'];

       if($permitX == 1){
        return true;
      }
      return false;
    });

      Gate::define('isMember', function($user){
      

      // option 1
       $arr = $this->getPermissions($user->user_role);
       $permitX = $arr['isAdmin'];
        if($permitX == 0){
          return true;
        }
        else{
        return false;
       }
       
     });

         Gate::define('isLoansManager', function($user){

        $role = Str::singular($this->getUserRole($user->user_role));
        if(strtolower($role) == 'loansmanager'){
         return true;
       }
       else
       {
         return false;
       }

      });


      Gate::define('isSuperAdmin', function($user){
       
       $arr = $this->getPermissions($user->user_role);
       $permitX = $arr['isAdmin'];
       $permitY = $arr['isSuperAdmin'];

        if($permitX == 1 && $permitY == 1){
          return true;
        }
        return false;

       
      });

      Gate::define('isActive',function($user){
        return ($user->approved == 1)?
        Response::allow() : Response::deny('Your account is inactive, please 
          inform your super administrator');
      });


      Gate::define('update-event', function ($user, $event_registra) {
        if($user->name == $event_registra){
          return true;
        }
        return false;
      });


      Gate::define('delete-event', function ($user, $event_registra) {
        if($user->name == $event_registra){
          return true;
        }
        return false;
      });

    

    }


    public function getPermissions($user_role){

      $d1 = DB::table('roles')
      ->where('role_id', $user_role)
      ->value('is_admin');

      $d2 = DB::table('roles')
      ->where('role_id', $user_role)
      ->value('isSuperAdmin');

      $dataArr = array(
                    'isAdmin' => $d1,
                    'isSuperAdmin' => $d2
                     );

      return $dataArr;

    }

    protected function getUserRole($id)
    {
      $role = Role::where('role_id',$id)->value('role');
      return Str::singular($role);
    }





  }
