<?php

namespace MillionsSaving\Http\Controllers\Auth;

use MillionsSaving\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class CustomAuthController extends Controller
{
  


public function Logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }


}
