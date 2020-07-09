<?php

namespace MillionsSaving\Http\Controllers\Loans;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Middleware\Authenticate;
use MillionsSaving\Http\Controllers\Controller;


class DefaultersController extends Controller
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

        $today = date('Y-m-d');

        $defaulters = DB::table($this->tbl)
                    ->where('is_paid', false)
                    ->where('due_date', '<', $today)
                    ->whereNotNull('taken_on')
                    ->get();

        $number_of_defaulters = DB::table($this->tbl)
                    ->where('is_paid', false)
                    ->where('due_date', '<', $today)
                    ->whereNotNull('taken_on')
                    ->count();

        $totalLoans = DB::table($this->tbl)
                    ->where('is_paid', false)
                    ->where('due_date', '<', $today)
                    ->whereNotNull('taken_on')
                    ->sum('loan_amount');

        return view('pages.loans.defaulters')
        ->with(compact('defaulters', 'totalLoans','number_of_defaulters'));
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
