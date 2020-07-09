<?php

namespace MillionsSaving\Http\Controllers\Benefits;

use Illuminate\Http\Request;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\CentralController;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use MillionsSaving\Models\Benefits\Investment;
use MillionsSaving\Imports\ImportInvestments;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Excel;

class InvestmentsController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //Load approved or already running investments
    public function index()
    {

        $rows = Investment::whereNotNull('approved_on')
                           ->whereNotNull('approved_by')
                           ->get();

        $number_of_runningInvestments = Investment::whereNotNull('approved_on')
                                                   ->whereNotNull('approved_by')
                                                   ->count();
        $totl_investmentCapital = Investment::whereNotNull('approved_on') 
                                              ->whereNotNull('approved_by')
                                              ->sum('capital');
        $totl_returns = Investment::whereNotNull('approved_on')
                                    ->whereNotNull('approved_by')
                                    ->sum('returns');

        return view('pages.benefits.running-investments')
        ->with(compact('rows', 'number_of_runningInvestments',
            'totl_investmentCapital', 'totl_returns'));

    }

//Load investments that need approval
    public function unapprovedInvestments()
    {

        $rows = Investment::whereNull('approved_on')->get();
        $number_of_runningInvestments = Investment::whereNull('approved_on')
                                                   ->whereNull('approved_by')
                                                   ->count();
        $totl_investmentCapital = Investment::whereNull('approved_on')
                                             ->whereNull('approved_by')
                                             ->sum('capital');
        $totl_returns = Investment::whereNull('approved_on')
                                   ->whereNull('approved_by')
                                   ->sum('returns');

        return view('pages.benefits.unapproved-investments')
        ->with(compact('rows', 'number_of_runningInvestments',
            'totl_investmentCapital', 'totl_returns'));

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

        $this->validate($request, [
            'asset' => 'required',
            'capital' => 'required',
            'returns' => 'required',
        ]);

        $data = new Investment;
        $asset = $request->input('asset');
        $capital = $request->input('capital');
        $returns = $request->input('returns');

        $data->asset = $asset;
        $data->capital = $capital;
        $data->returns = $returns;

        ($request->has('details'))
        ?  $data->details = $request->input('details')
        : $data->details = null;

        if($request->has('date-of-approval'))
       {
           $date_of_approval = $request->input('date-of-approval');
           $data->approved_on = $date_of_approval;
       }
       if($request->has('approvedby'))
       {
           $approved_by = $request->input('approvedby');
           $data->approved_by = $approved_by;
       }

        $res = $data->save();
        if($res){
            $action = "added a new approved investment of asset ".$asset." worth ".number_format($capital)."";
            LogsController::logger($action, now());
            return back()
            ->with('success', "You have successfully ".$action."");
        }else{
            return back()
            ->with('fail', "Addition of new investment has failed");
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
       $this->validate($request, [
        'asset' => 'required',
        'capital' => 'required',
        'returns' => 'required',
    ]);

       $data = Investment::find($id);
       $asset = $request->input('asset');
       $capital = $request->input('capital');
       $returns = $request->input('returns');
      
       

       $data->asset = $asset;
       $data->capital = $capital;
       $data->returns = $returns;
       if($request->has('date-of-approval'))
       {
           $date_of_approval = $request->input('date-of-approval');
           $data->approved_on = $date_of_approval;
       }
       if($request->has('approvedby'))
       {
           $approved_by = $request->input('approvedby');
           $data->approved_by = $approved_by;
       }
    
       ($request->has('details'))
       ?  $data->details = $request->input('details')
       : $data->details = null;

       $res = $data->save();
       if($res){
        $action = "updated details of an investment of asset ".$asset." worth ".number_format($capital)."";
        LogsController::logger($action, now());
        return back()
        ->with('success', "You have successfully ".$action."");
    }else{
        return back()
        ->with('fail', "Updating of new investment has failed");
    }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Investment::find($id);
        $asset = $data->asset;
        $worth = number_format($data->capital);
        $date = $data->approved_on;

        ($date == null)
        ? $key = "planned"
        : $key = "running";

        $res = $data->delete();

        if($res){
            $action = "deleted a ".$key." investment of asset ".$asset." whose capital was ".$worth."";
            LogsController::logger($action, now());
            return back()
                   ->with('success', "You have successfully ".$action."");
        }

    }


public function approveInvestment(Request $request, $id){

  $response = Gate::inspect('isAdmin');
  $central = new CentralController;

  if($response->allowed()){

    $data = Investment::find($id);
    $asset = $data->asset;
    $capital = $data->capital;
    $data->approved_by = Auth::user()->name;
    $data->approved_on = date('Y-m-d');

    $email = "asingwire50dallington@gmail.com";

    $approved = $data->save(); 

    if($approved)
    {

     $action = "approved investment idea of ".$asset." of amount worth ".number_format($capital)."";

     LogsController::logger($action, now());

       if($central->is_connectedToInternet() == 1)
       {
        $companyEmail = config('app.companyEmail');

        $subject = Str::title("approval of investment idea");

        $dataArr = array(
         'asset' => $asset,
         'details' => $details,
         'capital' => $capital,
       );

        Mail::send('pages.mail.investments.approval-message', 
         $dataArr, function($message) 
         use ($asset, $details, $capital)
         {   
          $message->from($companyEmail, 'Dallington');
          $message->to($email)->subject($subject);
        }); 

        if(Mail::failures()){
          return back()
          ->with('success', "You have successfully ".$action." but an email has not been sent because of poor internet connection");
        }
        else
        {
          return back()
          ->with('success', "You have successfully ".$action." and an email has been automatically sent to ".$applicant." about approval of loan request"); 
        } 

    } //end of if there is internet connection

    else if($central->is_connectedToInternet() == 0)
    {

      return back()
      ->with('success', "You have successfully ".$action." but an email has not been sent because of no internet connection"); 

    } // end of if there is no internet connection

   
   } //end of if investment idea is approved

   else
   {
    return back()
    ->with('fail', "Termination of running investment
      of  asset ".$asset." has failed"
    );
  }

} //end of Gate response


}



public function denyInvestment($id){

  $response = Gate::inspect('isAdmin');
  $central = new CentralController;

  if($response->allowed()){

    $data = Investment::find($id);
    $asset = $data->asset;
    $capital = $data->capital;
    $data->approved_by = null;
    $data->approved_on = null;

    $email = "asingwire50dallington@gmail.com";

    $denied = $data->save(); 

    if($denied)
    {

     $action = "disapproved running investment of ".$asset." of amount worth ".number_format($capital)."";

     LogsController::logger($action, now());

       if($central->is_connectedToInternet() == 1)
       {
        $companyEmail = config('app.companyEmail');

        $subject = Str::title("termination of investment idea");

        $dataArr = array(
         'asset' => $asset,
         'details' => $details,
         'capital' => $capital,
       );

        Mail::send('pages.mail.investments.denial-message', 
         $dataArr, function($message) 
         use ($asset, $details, $capital)
         {   
          $message->from($companyEmail, 'Dallington');
          $message->to($email)->subject($subject);
        }); 

        if(Mail::failures()){
          return back()
          ->with('success', "You have successfully ".$action." but an email has not been sent because of poor internet connection");
        }
        else
        {
          return back()
          ->with('success', "You have successfully ".$action." and an email has been automatically sent to ".$applicant." about approval of loan request"); 
        } 

    } //end of if there is internet connection

    else if($central->is_connectedToInternet() == 0)
    {

      return back()
      ->with('success', "You have successfully ".$action." but an email has not been sent because of no internet connection"); 

    } // end of if there is no internet connection

   
   } //end of if loan request is denied

   else
   {
    return back()
    ->with('fail', "Termination of running investment
      of  asset ".$asset." has failed"
    );
  }

} //end of Gate response

}

 public function importInvestments(Request $request)
     {
       $this->validate($request,
        ['select_file' => 'required|mimes:xls,xlsx'],
        ['select_file.mimes' => 'Please select only excel files to import investments']
      );

       $result = Excel::import(new ImportInvestments, request()->file('select_file'));

       if($result){

        $action = "imported an excel file of investments into the system";
        LogsController::logger($action, now());

        return back()
               ->with('success','Investments from an excel file have been imported successfully');
      }
      else{
       return back()
              ->with('fail','Investments not imported!');
     }

   }


















}
