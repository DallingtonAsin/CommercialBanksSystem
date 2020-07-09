<?php

namespace MillionsSaving\Http\Controllers\Messages;

use MillionsSaving\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Notifications\Messages\NexmoMessage;

class SmsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        return view('pages.mail.sms');
    }

    public function SendSMS(){
       

    }
}
