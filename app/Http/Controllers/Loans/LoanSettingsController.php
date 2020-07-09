<?php

namespace MillionsSaving\Http\Controllers\Loans;

use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Carbon;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use MillionsSaving\Models\Loans\LoanSetting;
use MillionsSaving\Imports\ImportLoanRates;
use Excel;


class LoanSettingsController extends Controller
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
      $rows = LoanSetting::all();
      $no_of_settings = LoanSetting::count();
      return view('pages.loans.loan-settings')
             ->with(compact('rows', 'no_of_settings'));
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
         $data = new LoanSetting;
        $data->min_loanamt = $request->input('minAmt');
        $data->max_loanamt = $request->input('maxAmt');
        $data->interest_rate = $request->input('rate');
        $res = $data->save();
        if($res){
            $action = "recorded loan interest";
            LogsController::logger($action, now());
            return back()
            ->with('success', "You have successfully ".$action."");

        }
        else{
            return back()
            ->with('fail', "Storage of loan interest rate has failed");
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
        $data = LoanSetting::find($id);
        $data->min_loanamt = $request->input('minAmt');
        $data->max_loanamt = $request->input('maxAmt');
        $data->interest_rate = $request->input('rate');
        $res = $data->save();
        if($res){
            $action = "updated loan interest";
            LogsController::logger($action, now());
            return back()
            ->with('success', "You have successfully ".$action."");

        }else{
            return back()
            ->with('fail', "Update of loan interest rate has failed");
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
        $data = LoanSetting::find($id);
        $res = $data->delete();

        if($res){
            $action = "deleted loan interest record";
            LogsController::logger($action, now());
            return back()
            ->with('success', "You have successfully ".$action."");

        }else{
            return back()
            ->with('fail', "Deletion of loan interest record rate has failed");
        }
    }


 public function importLoanInterestRates(Request $request)
     {

       $this->validate($request,
        ['select_file' => 'required|mimes:xls,xlsx'],
        ['select_file.mimes' => 'Please select only excel files to import loan interest rates']
      );

       $result = Excel::import(new ImportLoanRates, request()->file('select_file'));

       if($result){

        $action = "imported an excel file of interest rates for loans into the system";
        LogsController::logger($action, now());

        return back()
               ->with('success','Interest rates for loans from an excel file have been imported successfully');
      }
      else{
       return back()
              ->with('fail','Loan Interest rates not imported!');
     }

   }













}
