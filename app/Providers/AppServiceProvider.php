<?php

namespace MillionsSaving\Providers;
use MillionsSaving\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use MillionsSaving\Http\View\Composers\ComposerNotifications;
use MillionsSaving\Http\View\Composers\ComposerOverview;
use MillionsSaving\Http\View\Composers\Telescope;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

   

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

           Schema::defaultStringLength(191);
       //Option1: Every single view
       //View::share('type', $messages);


       //Option2: View Composer you can attach data to specific views
       // View::composer(['pages.main.suppliers','pages.main.customers'], function($view){
       //   $user = User::find(1);
       //   $messages = array();
       //   foreach ($user->notifications as $notification) {
       //     $rows = $notification->data;
       //     $messages  = array($rows);
       //   }
       //   $view->with('type', $messages);
       // });


     //Option3: Dedicated class
     // View::composer(['pages.*'], ComposerNotifications::class);
       View::composer(['layouts.sidebar-header','pages.*'], ComposerOverview::class);
       View::composer(['pages.*'], Telescope::class);




    }

   
}
