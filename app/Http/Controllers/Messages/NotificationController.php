<?php

namespace MillionsSaving\Http\Controllers\Messages;

use MillionsSaving\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MillionsSaving\CustomModels\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;

class NotificationController extends Controller
{


    public function __construct(){
	    $this->middleware('auth');
    }

    public function readNotification(Request $request){
    	Auth::user()->unreadNotifications()->find($request->id)->markAsRead;
    	return back();
    }

		public function markAllRead(){
			$user = Auth::user();
			foreach ($user->unreadNotifications as $notification) {
          $notification->markAsRead();
        }
			return back();
		}
}
