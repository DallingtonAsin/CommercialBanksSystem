<?php

namespace MillionsSaving\Http\Controllers\Logs;

use MillionsSaving\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use MillionsSaving\Models\Logs;
use MillionsSaving\Models\User\Role;
use MillionsSaving\Models\Logs\ErrorLogs;
use MillionsSaving\Models\Logs\RequestResponseLogs;

class LogsController extends Controller
{



    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

     $response = Gate::inspect('isMember');
     if($response->allowed()){
        $logs = Logs::where('name', Auth::user()->name)
        ->get();
        $number_of_logs = Logs::where('name', Auth::user()->name)
        ->count();
    }
    else
    {
        $logs = Logs::all();
        $number_of_logs = Logs::count(); 
    }

    return view('pages.logs.index')->with(compact('logs','number_of_logs'));

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     $response = Gate::inspect('isSuperAdmin');

     if($response->allowed()){

        $log = Logs::find($id);
        $res = $log->delete();
        if($res){
            return back()->with('log-deleted','log has been deleted successfully');
        }else{
         return back()->with('log-not-deleted','log has not been deleted');
     }

 }
 else
 {
    $message = $response->message();
    return back()->with('log-not-deleted',$message);
}
}


public function truncateLogs(){

    $response = Gate::inspect('isSuperAdmin');

    if($response->allowed()){
        $res = Logs::truncate();
        if($res){
            return back()->with('log-deleted','All logs have been deleted successfully');
        }else{
           return back()->with('log-not-deleted','logs have not been deleted');
       }
   }else{
      $message = $response->message();
      return back()->with('log-not-deleted',$message);
  }


}


public function readFileContents(){
    $filename = storage_path("logs/logs.log");
    $file_contents= array();

    if(file_exists($filename) && is_readable($filename)){
        $fileResource = fopen($filename, "r");
        if($fileResource){
            while(($line = fgets($fileResource)) !== false){
              $file_contents[] = $line;
          }
      }
  }   

}



public static function logger($action, $date){

    $newLog = new Logs();
    (Auth::check())
    ? $newLog->name =$name =  Auth::user()->name
    : $newLog->name = $name ='membership applicant';

    $newLog->role = $userPosition = LogsController::getUserRole();
    $newLog->action = $action;
    $newLog->ip_address = \Request::getClientIp();
    $newLog->date = $date;

    $newLog->save();
    Log::channel('poslogs')->notice("".$userPosition." ".$name." ".$action."");

}

public static function logApplicants($data){

    $newLog = new Logs;
    $name = $data['name'];
    $userPosition = $data['role'];
    $action = $data['action'];
    
    $newLog->name = $name;
    $newLog->role = $userPosition;
    $newLog->action = $action;
    $newLog->ip_address = \Request::getClientIp();
    $newLog->date = now();

    $newLog->save();
    Log::channel('poslogs')->notice("".$userPosition." ".$name." ".$action."");

}

public static function LogRequestResponse($data)
{

    try{
    $obj = new RequestResponseLogs(); // Model
    $obj->user = Auth::user()->name;
    $obj->role =  LogsController::getUserRole();
    $obj->method= $data["method"];
    $obj->method_type = $data["method_type"];
    $type = "XML";
    $obj->request = response($data["request"])
            ->header('Content-Type', $type);
            //response()->json($data["request"]);
    $obj->response = json_encode($data["response"]);
    $obj->ip_address = \Request::getClientIp(); 

    $obj->save();
}catch(Exception $ex){
    report($ex);
}

}

public static function LogError($data){

 $user = Auth::user()->name;
 $role = LogsController::getUserRole();

 try{

    $e_log = new ErrorLogs();
    $e_log->user = $user;
    $e_log->role = $role;
    $e_log->error = $error = $data['error'];
    $e_log->method = $method = $data['method'];
    $e_log->method_type = $data['method_type'];
    $e_log->ip_address = \Request::getClientIp();
    $e_log->save();
    Log::channel('errorLogs')->error("".now().", user ".$user." encountered a problem ".$error." on the method ".$method."");

}catch(Exception $ex){  
   Log::channel('errorLogs')->error("".now().", user ".$user." encountered a problem ".$error." on the method ".$method."");
   Log::error($ex->getMessage());
}

}

public static function getUserRole()
{

    (Auth::check())
    ? $role = Auth::user()->user_role
    : $role  = 1;

    $userRole = Role::where('role_id', $role)
    ->value('role');
    return $userRole;

}


}
