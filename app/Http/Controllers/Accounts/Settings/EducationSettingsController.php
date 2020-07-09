<?php

namespace MillionsSaving\Http\Controllers\Accounts\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Middleware\Authenticate;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use MillionsSaving\Models\Accounts\MainAccSetting;
use MillionsSaving\Models\Accounts\EducAccSetting;
use MillionsSaving\Models\Accounts\SharesAccSetting;
use MillionsSaving\Models\Accounts\RetirementAccSetting;

class EducationSettingsController extends Controller
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

    public function index()
    {
     
        $data = DB::table('educaccsettings')->get();
        return view('pages.accounts.settings.education')
        ->with(compact('data'));
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

     $data = DB::table('educaccsettings');
     $key =  trim($request->input('Skey'));
     $value = trim($request->input('Svalue'));
     $account = 'Education A/C';
     $result = $data->insert([
        'setting_key' => $key,
        'setting_value' => $value,
    ]);

     if($result){
        $action = "added new account setting key ".$key." into ".$account." account";
        LogsController::logger($action, now());
        return back()
        ->with('success',"You have successfully ".$action."");
    }
    else{
       return back()
       ->with('fail',"Storage of an account setting has failed");
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

        $table = 'educaccsettings';
        $data = DB::table($table)->where('id', $id);
        $arr = $this->getKeyAndValue($table, $id);
        $account = 'Education A/C';

        $original_key =  $arr['key'];
        $original_value = $arr['value'];
        $newkey = trim($request->input('SettingKey'));
        $newvalue = trim($request->input('SettingValue'));

        if(($original_key != $newkey) || (doubleval($original_value) != doubleval($newvalue))){

            $changeX = "from key ".$original_key." to ".$newkey." in ".$account." account";
            $changeY = "from value ".$original_value." to ".$newvalue." in ".$account." account";
            $changeZ = $changeX." and ".$changeY;

            switch (true) {
             case $original_key != $newkey:
             $change = $changeX;
             break;
             case doubleval($original_value) != doubleval($newvalue):
             $change = $changeY;
             break;
             case (($original_key != $newkey) && (doubleval($original_value) != doubleval($newvalue))):
             $change = $changeZ;
             break;
         }

         $data->setting_key = $newkey;
         $data->setting_value = $newvalue;

         $result = $data->update([
            'setting_key' => $newkey,
            'setting_value' => $newvalue,
        ]);

         if($result){
            $action = "updated details of account setting key ".$original_key." ".$change."";
            LogsController::logger($action, now());
            return back()
            ->with('success',"You have successfully ".$action."");
        }
        else{
           return back()
           ->with('fail',"An update of account setting has failed");
       }
   }
   else{
    return back()
    ->with('warning',"Details of account setting have not been updated
        since they are similar to existing stored details");
}

}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
 
         $data = DB::table('educaccsettings')->where('id', $id);
        $key = $arr['key'];
        $result = $data->delete();

        if($result){
            $action = "deleted account setting key ".$key." from the system";
            LogsController::logger($action, now());
            return back()
            ->with('success',"You have successfully ".$action."");
        }
        else{
           return back()
           ->with('fail',"Deletion of account setting has failed");
       }
   }

   protected function getKeyAndValue($table, $id){

    $key = DB::table($table)
    ->where('id', $id)->value('setting_key');

    $value = DB::table($table)
    ->where('id', $id)->value('setting_value');

    $dataArr = array(
        'key' => $key,
        'value' => $value,

    );
    return $dataArr;

}


}
