<?php

namespace MillionsSaving\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Middleware\Authenticate;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\Logs\LogsController;

class CustomLoginController extends Controller
{

	private $tbl;
	protected $dateTime;

	public function __construct()
	{

		$this->tbl = 'users';
		$this->dateTime = now();
		$this->middleware('guest')->except('logout');
	}



	public function authenticate(Request $request)
	{

		($request->has('remember'))
		? $remembered = true
		: $remembered = false;

		$this->validate($request,[
			'login' => 'required',
			'password' => 'required',
		]);

		$login = $request->input('login');
		$password = $request->input('password');

		filter_var($login, FILTER_VALIDATE_EMAIL)
		? $fieldType = 'email' 
		: $fieldType = 'username';

		$user_id = $this->getUserId($login, $password);

		if(Auth::attempt([$fieldType => $login,
			'password' =>  $password,
			'active' => 1
		], $remembered)){

			$action = "logged into the system";
			LogsController::logger($action, $this->dateTime);

			return redirect('/home');	

		}

		else
		{
			$userId = $this->getUserId($login);
			$status = $this->findAccountStatus($userId);

			if(Auth::attempt([$fieldType => $login,
				'password' =>  $password
			])){

				if($status == 0){

					return redirect('/')
					->with('loginErr', 'Your account is inactivated, see your administrator');
				}


			}
			else{

				return redirect('/')
				->with('loginErr', 'Invalid login credentials, please try again');
			}

			$request->session()->flush();


		}


	}

	public function logout(Request $request){

		$action = "logged out the system";
		LogsController::logger($action, $this->dateTime);
		Auth::logout();
		Session::flush();
        $request->session()->forget(['login', 'password']);
        $request->session()->flush();
		return redirect('/');

	}

	public function getUserId($login)
	{
		filter_var($login, FILTER_VALIDATE_EMAIL)
		? $fieldType = 'email' 
		: $fieldType = 'username';

		$userId = DB::table($this->tbl)
		->where($fieldType, $login)
		->value('email');

		return $userId;
	}

	public function findAccountStatus($id){

		$accountStatus = DB::table($this->tbl)
		->where('email', $id)->value('active');
		return $accountStatus;

	}


	public function findUsername(){

		$login = request()->input('login');

		$fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

		request()->merge([$fieldType => $login]);
		if($fieldType){
			return $fieldType;  
		}

	}







}
