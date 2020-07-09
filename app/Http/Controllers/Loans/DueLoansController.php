<?php

namespace MillionsSaving\Http\Controllers\Loans;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
use MillionsSaving\Http\Controllers\Controller;

class DueLoansController extends Controller
{

    private $tbl;

    public function __construct(){
        $this->tbl = 'loans';
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $GateAdmin = Gate::inspect('isAdmin');
        $GateMember = Gate::inspect('isMember');

        if($GateAdmin->allowed()){

            $dueLoans = DB::table($this->tbl)
            ->where('is_paid',false)
            ->where('loan_balance', '>', 400)
            ->whereNotNull('taken_on')
            ->get();

            $totaldueLoans =  DB::table($this->tbl)
            ->where('is_paid',false)
            ->where('loan_balance', '>', 400)
            ->whereNotNull('taken_on')
            ->sum('loan_amount');

            $number_of_dueLoans = DB::table($this->tbl)
            ->where('is_paid', false)
            ->where('loan_balance','>', 400)
            ->whereNotNull('taken_on')
            ->count();

            return view('pages.loans.due-loans')
            ->with(compact('dueLoans','totaldueLoans','number_of_dueLoans'));


        }



        if($GateMember->allowed()){

            $dueLoans = DB::table($this->tbl)
            ->where('is_paid',false)
            ->where('loan_balance', '>', 400)
            ->whereNotNull('taken_on')
            ->where('name', Auth::user()->name)
            ->get();

            $totaldueLoans =  DB::table($this->tbl)
            ->where('is_paid',false)
            ->where('loan_balance', '>', 400)
            ->whereNotNull('taken_on')
            ->where('name', Auth::user()->name)
            ->sum('loan_amount');

            $number_of_dueLoans = DB::table($this->tbl)
            ->where('is_paid', false)
            ->where('loan_balance','>', 400)
            ->whereNotNull('taken_on')
            ->where('name', Auth::user()->name)
            ->count();

            return view('pages.loans.due-loans')
            ->with(compact('dueLoans','totaldueLoans','number_of_dueLoans'));

        }


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
        //
    }
}
