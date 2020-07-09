<?php

namespace MillionsSaving\Http\Controllers\Loans;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Models\Loans\Loan;

class PaidLoansController extends Controller
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
        $GateAdmin = Gate::inspect('isAdmin');
        $GateMember = Gate::inspect('isMember');

        if($GateAdmin->allowed()){
            $paidLoans = Loan::where('is_paid',true)
            ->where('loan_balance', 0)
            ->get();

            $totalPaidLoans =  Loan::where('is_paid',true)
            ->where('loan_balance', 0)
            ->sum('loan_amount');

            $number_of_paidLoans = Loan::where('is_paid',true)
            ->where('loan_balance', 0)
            ->count();

            return view('pages.loans.paid-loans')
            ->with(compact('paidLoans','totalPaidLoans','number_of_paidLoans'));
        }

        if($GateMember->allowed()){
            $paidLoans = Loan::where('is_paid',true)
            ->where('name', Auth::user()->name)
            ->where('loan_balance', 0)
            ->get();

            $totalPaidLoans = Loan::where('is_paid',true)
            ->where('loan_balance', 0)
            ->where('name', Auth::user()->name)
            ->sum('loan_amount');

            $number_of_paidLoans = Loan::where('is_paid',true)
            ->where('name', Auth::user()->name)
            ->where('loan_balance', 0)
            ->count();

            return view('pages.loans.paid-loans')
            ->with(compact('paidLoans','totalPaidLoans','number_of_paidLoans'));
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
       $request->validate([
        'amount' => 'required',
        'paidOn' => 'required',
        'approvedBy' => 'required',
        'receivedBy' => 'required',
    ]);

       $amount = $request->input('amount');
       $paid_on = $request->input('paidOn');
       $approvedBy = $request->input('approvedBy');
       $receivedBy = $request->input('receivedBy');

       $data = Loan::findorFail($id);
       $data->loan_amount = $amount;





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
