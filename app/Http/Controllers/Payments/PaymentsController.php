<?php

namespace MillionsSaving\Http\Controllers\Payments;

use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\CentralController;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;
use MillionsSaving\Models\Payments\Payment;
use Illuminate\Contracts\Encryption\DecryptException;
use MillionsSaving\User;
use Bmatovu\MtnMomo\Products\Collection;

class PaymentsController extends Controller
{

  private $tableA, $tableB, $tableC, $tableD;

  public function __construct()
  {
    $this->tableA = 'mainsavingacc';
    $this->tableB = 'educationsavingacc';
    $this->tableC = 'retirementsavingacc';
    $this->tableD = 'sharessavingacc';
    $this->middleware('auth');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     return view('pages.accounts.payments.mtnmomo');
   }

   public function momoIndex(){
    return view('pages.accounts.payments.mtnmomo');
  }


  public function creditCardIndex(){
    return view('pages.accounts.payments.credit-card');
  }

  public function accountFundsTransferIndex(){
   return view('pages.accounts.payments.account-funds-transfer');
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

        'SenderAcNo' => 'required',
        'SenderNames' => 'required',
        'ReceiverAcNo' => 'required',
        'ReceiverNames' => 'required',
        'Branch' => 'required',
        'reason' => 'required',
        'amount' => 'required',
        'telNo' => 'required',
        'paymentMode' => 'required',
        'AccPin' => 'required',
      ]);

      $accnoX = $request->input('SenderAcNo');
      $nameX = $depositor = $request->input('SenderNames');
      $accnoY = $request->input('ReceiverAcNo');
      $nameY = $request->input('ReceiverNames');
      $accPin = $request->input('AccPin');
      $branch = $request->input('Branch');
      $reasonId = $request->input('reason');
      $amount = $request->input('amount');
      $tel_no = $request->input('telNo');
      $paymentMode = $request->input('paymentMode');


      switch (true) {
       case $reasonId == 'one':
       $acc = 'main';
       $type = 'saving';
       $reason = 'deposit to main a/c';
       $table = $this->tableA;
       break;
       case $reasonId == 'two':
       $acc = 'education';
       $type = 'saving';
       $reason = 'deposit to education a/c';
       $table = $this->tableB;
       break;
       case $reasonId == 'three':
       $acc = 'retirement';
       $type = 'saving';
       $reason = 'deposit to retirement a/c';
       $table = $this->tableC;
       break;
       case $reasonId == 'four':
       $type = 'saving';
       $acc = 'shares';
       $reason = 'deposit to shares a/c';
       $table = $this->tableD;
       break;
       case $reasonId == 'five':
       $reason = 'paying loan';
       $type = 'loan';
       break;
       case $reasonId == 'six':
       $reason = 'others';
       $type = 'others';
       break;

     }


     if($accnoX == $accnoY){
       return back()
       ->with('fail',"Sorry, You have entered same account numbers, please enter different A/C numbers!");
     }

     else{

       $arrX =  $this->getUserdata($acc, $accnoX);
       $arrY =  $this->getUserdata($acc, $accnoY);
       $boolX = $arrX['boolean'];
       $boolY = $arrY['boolean'];

       if($boolX === false){
        return back()
        ->with('fail', "We could not find account number ".$accnoX." in the list of ".$acc." account numbers");
      }

      else if($boolY === false){
        return back()
        ->with('fail', "We could not find account number ".$accnoY." in the list of ".$acc." account numbers");

      }

      else if($boolX === true && $boolY === true){

       $payerId = $arrX['id'];
       $payeeId = $arrY['id'];

       $assArrX = $this->getAccountDetails($payerId, $acc);
       $assArrY = $this->getAccountDetails($payeeId, $acc);

       $payer_accno = $assArrX['accountNo'];
       $payee_accno = $assArrY['accountNo'];
       $encryptedUserPin = $this->getUserPin($acc, $payer_accno);
       try
       {
         $decryptedPin = decrypt($encryptedUserPin);
       }
       catch(DecryptException $ex){

       }

       $payer_name = $assArrX['accountName'];
       $payee_name = $assArrY['accountName'];

       if($decryptedPin == $accPin){

         $senteX = $this->getStmtValues($payerId, $acc);
         $senteY = $this->getStmtValues($payeeId, $acc);

         $tot_depositsX = $senteX['deposits'];
         $tot_withdrawalsX = $senteX['withdrawals'];
         $balanceX = $tot_depositsX - $tot_withdrawalsX;

         $lowest_amount = 5000;

         if(($balanceX-$amount) > $lowest_amount){

           $tot_depositsY = $senteY['deposits'];
           $tot_withdrawalsY = $senteY['withdrawals'];
           $balanceY = $tot_depositsY - $tot_withdrawalsY;

           $deposit1 = 0;
           $withdrawal1 = $amount;
           $balance1 = $balanceX - $amount;

           $deposit2 = $amount;
           $withdrawal2 = 0;
           $balance2 = $balanceY + $amount;

           $dataArr1 = array(
            'payer_accno' => $payer_accno,
            'payer_name' => $payer_name,
            'payee_accno' => $payee_accno,
            'payee_name' => $payee_name,
            'branch' => $branch,
            'depositor' => $depositor,
            'amount' => $amount,
            'mode' => $paymentMode,
            'reason' => $reason,
            'contact' => $tel_no,
            'date' => now(),
          );

           $action = "transfered ".number_format($amount)." to ".$acc." A/C No. ".$payee_accno." in the A/C names of ".$payee_name." from
           your account ".$payer_accno." in the names of ".$payer_name."";
           $companyEmail = config('app.companyEmail');
           $payer_email = $this->getUserEmail($acc, $payer_accno);
            $subject = "Transfer of funds";

           $dataArr2 = array(
            'table' => $table,
            'id' => $payerId,
            'accno' => $payer_accno,
            'accname' => $payer_name,
            'type' => $type,
            'reason' => Str::replaceFirst('deposit to', 'withdraw from', $reason),
            'deposit' => $deposit1,
            'withdrawal' => $withdrawal1,
            'names' => $payer_name,
            'balance' => $balance1,
            'date' => now(),
            'subject' => $subject,
            'action' => $action,
            'companyEmail' => $companyEmail,
            'payee_email' => $payer_email,
            'action' => $action,

          );

           $dataArr3 = array(
            'table' => $table,
            'id' => $payeeId,
            'accno' => $payee_accno,
            'accname' => $payee_name,
            'type' => $type,
            'reason' => $reason,
            'deposit' => $deposit2,
            'withdrawal' => $withdrawal2,
            'balance' => $balance2,
            'date' => now(),
          );

           $resX = $this->addPayment($dataArr1);
           $resY = $this->makeSaving($dataArr2);
           $resZ = $this->makeSaving($dataArr3);


           if($resX == true && $resY === true && $resZ === true)
           {
   // Logs this activity
            LogsController::logger($action, now());

            if($payer_email != null){
              $page = "pages.mail.payments.momo-deposit";
              $central = new CentralController;
              $response = $central->sendMail($dataArr2,$page);
              if($response == 1){
               return back()->with('success',"You have successfully ".$action." but an email has been sent about this transaction");
             }
             if($response == -1){
              return back()->with('success',"You have successfully ".$action." but an email has not been sent because of poor internet connection");
            }
            if($response == 0){
             return back()->with('success',"You have successfully ".$action." but an email has not been sent because of no internet connection");
           }
         }
         else
         {
           return back()
           ->with('success',"You have successfully ".$action."");
         }



       }
       else{
         return back()
         ->with('fail',"Payment not made, please try again!");
       }

     }
     else{
      return back()
      ->with('fail',"Insufficient account balance. You cannot transfer amount that will leave your balance less than ".number_format($lowest_amount).". Your current A/C balance is ".number_format($balanceX)."");

    }

  }

  else{
    $reset_pinUrl = url('/account-settings');
    $pinfailMessage = "You have entered incorrect pin,please try again or reset your pin at ";
    $pinErr = array(
     'message' => $pinfailMessage,
     'url' => $reset_pinUrl,
   );

    return view('pages.accounts.payments.account-funds-transfer')
    ->with(compact('pinErr'));

  }
}
}

}


private function getUserPin($account, $accountNo)
{

 switch (true) {
  case $account == 'main':
  $encryptedPin = User::where('acc_noM', $accountNo)
  ->value('pin');
  break;
  case $account == 'education':
  $encryptedPin = User::where('acc_noE', $accountNo)
  ->value('pin');
  break;
  case $account == 'retirement':
  $encryptedPin = User::where('acc_noR', $accountNo)
  ->value('pin');
  break;
  case $account == 'shares':
  $encryptedPin = User::where('acc_noS', $accountNo)
  ->value('pin');
  break;
}

return $encryptedPin;


}


//Mobile money payment 
public function transactMomo(Request $request)
{

  $this->validate($request, [
   'AcName' => 'required',
   'AcNo' => 'required',
   'Depositor' => 'required',
   'MomoPin' => 'required',
   'Branch' => 'required',
   'reason' => 'required',
   'amount' => 'required',
   'telNo' => 'required',
   'paymentMode' => 'required',
 ]);

  $accno = $request->input('AcNo');
  $account_name = $request->input('AcName');
  $depositor = $request->input('Depositor'); //get mobile money names
  $momoPin = $request->input('MomoPin');
  $branch = $request->input('Branch');
  $reasonId = $request->input('reason');
  $amount = $request->input('amount');
  $tel_no = $request->input('telNo');
  $paymentMode = $request->input('paymentMode');

  // try {
  //   $collection = new Collection();

  //  // Request a user to pay
  //   $momoTransactionId = $collection->transact('transactionId', $tel_no, $amount);

  // }
  //  catch(CollectionRequestException $e)
  //  {
  //   do {
  //     printf("\n\r%s:%d %s (%d) [%s]\n\r",
  //       $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode(), get_class($e));
  //   } while($e = $e->getPrevious());

  // }

  // dd($momoTransactionId);
  


  switch (true) {
   case $reasonId == 'one':
   $acc = 'main';
   $type = 'saving';
   $reason = 'deposit to main a/c';
   $table = $this->tableA;
   break;
   case $reasonId == 'two':
   $acc = 'education';
   $type = 'saving';
   $reason = 'deposit to education a/c';
   $table = $this->tableB;
   break;
   case $reasonId == 'three':
   $acc = 'retirement';
   $type = 'saving';
   $reason = 'deposit to retirement a/c';
   $table = $this->tableC;
   break;
   case $reasonId == 'four':
   $type = 'saving';
   $acc = 'shares';
   $reason = 'deposit to shares a/c';
   $table = $this->tableD;
   break;
   case $reasonId == 'five':
   $reason = 'paying loan';
   $type = 'loan';
   break;
   case $reasonId == 'six':
   $reason = 'others';
   $type = 'others';
   break;

 }

 //$dataArray =  $this->getUserdata('main', $payer_accno);
//method with an array amgst with boolean that checks if account no is avail

 $arr =  $this->getUserdata($acc, $accno);
 $bool = $arr['boolean'];

 if($bool === false)
 {
  return back()
  ->with('fail', "we could not find account number ".$accno." in the list of ".$acc." account numbers");
}

else if($bool === true)
{

 $payerId = Auth::user()->id;
 $payeeId = $arr['id'];
 $acc_name = $arr['user'];

 $deposit = $amount;
 $withdrawal = 0;

//Retrieving payer's account balance from an array
 $senteX = $this->getStmtValues($payerId, $acc);
 $tot_depositsX = $senteX['deposits'];
 $tot_withdrawalsX = $senteX['withdrawals'];
 $payer_balance = $tot_depositsX - $tot_withdrawalsX;

//Retrieving payee's account balance from an array
 $senteY = $this->getStmtValues($payeeId, $acc);
 $tot_depositsY = $senteY['deposits'];
 $tot_withdrawalsY = $senteY['withdrawals'];
 $payee_balance = $tot_depositsY - $tot_withdrawalsY;

 $dtArr = $this->getAccountDetails($payerId, $acc);
 $accNo = $dtArr['accountNo'];

// compution of balance
 if($accNo == $accno)
 {
  $payee_accno = $accNo;
  $payee_name = $dtArr['accountName'];
  $balance = $payer_balance + $amount;
}
else
{
       //$assArrX = $this->getAccountDetails($payerId, $acc);
       //$assArrX['accountNo']; 
        //$assArrX['accountName'];

 $assArrY = $this->getAccountDetails($payeeId, $acc);

 $payee_accno = $assArrY['accountNo'];
 $payee_name = $assArrY['accountName'];
 $balance = $payee_balance + $amount;
}

$subject = "Deposit of amount ".number_format($amount)." to account ".$payee_accno."";

$action = "deposited ".number_format($amount)." to ".$acc." A/C No.
".$payee_accno." in the names of ".$payee_name." from your mobile money account";

$companyEmail = config('app.companyEmail');
$payee_email = $this->getUserEmail($acc, $payee_accno);

$dataArrX = array(
  'payer_accno' => null,
  'payer_name' => null,
  'payee_accno' => $payee_accno,
  'payee_name' => $payee_name,
  'names' => $acc_name,
  'branch' => $branch,
  'depositor' => $depositor,
  'amount' => $amount,
  'mode' => $paymentMode,
  'reason' => $reason,
  'contact' => $tel_no,
  'date' => now(),
  'balance' => $balance,
  'subject' => $subject,
  'action' => $action,
  'companyEmail' => $companyEmail,
  'payee_email' => $payee_email,
  'action' => $action,
); 



$dataArrY = array(
  'table' => $table,
  'id' => $payeeId,
  'accno' => $payee_accno,
  'accname' => $payee_name,
  'date' => now(),
  'type' => $type,
  'reason' => $reason,
  'deposit' => $deposit,
  'withdrawal' => $withdrawal,
  'balance' => $balance,
  'subject' => $subject,

);

 $resX = $this->addPayment($dataArrX); // insert transaction to payment tbl
 $resY = $this->makeSaving($dataArrY); //make transaction to corresponding a/c


 if($resX === true && $resY === true)
 {

   // Logs this activity
  LogsController::logger($action, now());

  if($payee_email != null){
    $page = "pages.mail.payments.momo-deposit";
    $central = new CentralController;
    $response = $central->sendMail($dataArrX,$page);
    if($response == 1){
     return back()->with('success',"You have successfully ".$action." but an email has been sent about this transaction");
   }
   if($response == -1){
    return back()->with('success',"You have successfully ".$action." but an email has not been sent because of poor internet connection");
  }
  if($response == 0){
   return back()->with('success',"You have successfully ".$action." but an email has not been sent because of no internet connection");
 }
}
else
{
 return back()
 ->with('success',"You have successfully ".$action."");
}


}
else
{
 return back()
 ->with('fail',"Payment not made, please try again!");
}

}

}

protected function getUserEmail($account, $account_no)
{
  switch (true) {
    case $account == 'main':
    $email = User::where('acc_noM', $account_no)
    ->value('email');
    break;
    case $account == 'education':
    $email = User::where('acc_noE', $account_no)
    ->value('email');
    break;
    case $account == 'retirement':
    $email = User::where('acc_noR', $account_no)
    ->value('email');
    break;
    case $account == 'shares':
    $email = User::where('acc_noS', $account_no)
    ->value('email');
    break;
  }

  return $email;
}

protected function addPayment($dataArr)
{

 $data = new Payment();
 $data->payer_accno = $dataArr['payer_accno'];
 $data->payer_accname = $dataArr['payer_name'];
 $data->payee_accno = $dataArr['payee_accno'];
 $data->payee_accname = $dataArr['payee_name'];
 $data->branch = $dataArr['branch'];
 $data->deposited_by = $dataArr['depositor'];
 $data->amount = $dataArr['amount'];
 $data->payment_mode = $dataArr['mode'];
 $data->reason = $dataArr['reason'];
 $data->telno = $dataArr['contact'];
 $data->date = $dataArr['date'];

 $res = $data->save();

 ($res)
 ? $bool = true
 : $bool = false;

 return $res;

}


protected function makeSaving($data)
{

  $table = $data['table'];
  $member_id = $data['id'];
  $type = $data['type'];
  $reason = $data['reason'];
  $deposit = $data['deposit'];
  $withdrawal = $data['withdrawal'];
  $balance = $data['balance'];
  $acc_no = $data['accno'];
  $acc_name = $data['accname'];

  $result = DB::table($table)->insert([
    'member_id' => $member_id,
    'acc_no' => $acc_no,
    'acc_name' => $acc_name,
    'type' => $type,
    'description' => $reason,
    'deposit' => $deposit,
    'withdrawal' => $withdrawal,
    'balance' => $balance,
    'date' => now(),
  ]);

  ($result)
  ? $bool = true
  : $bool = false;

  return $bool;

}


protected function getAccountDetails($memberId, $account)
{

  switch (true) {
    case $account == 'main':
    $accountno = User::where('id', $memberId)
    ->value('acc_noM');
    break;
    case $account == 'education':
    $accountno = User::where('id', $memberId)
    ->value('acc_noE');
    break;
    case $account == 'retirement':
    $accountno = User::where('id', $memberId)
    ->value('acc_noR');
    break;
    case $account == 'shares':
    $accountno = User::where('id', $memberId)
    ->value('acc_noS');
    break;
  }

  $account_names = User::where('id', $memberId)
  ->value('acc_name');
  $data = array(
    'accountNo' => $accountno,
    'accountName' => $account_names,       
  );

  return $data;

}

protected function getBalance($account, $id)
{

 switch (true) {
  case $account == 'main':
  $balance = DB::table($this->tableA)
  ->where('memberId', $id)
  ->latest('date')->value('balance');
  break;
  case $account == 'education':
  $balance = DB::table($this->tableB)
  ->where('memberId', $id)
  ->latest('date')->value('balance');
  break;
  case $account == 'retirement':
  $balance = DB::table($this->tableC)
  ->where('memberId', $id)
  ->latest('date')->value('balance');
  break;
  case $account == 'shares':
  $balance = DB::table($this->tableD)
  ->where('memberId', $id)
  ->latest('date')->value('balance');
  break;

}

($balance && $balance > 0)
? $value = $balance
: $value = 0;

return $value;

}

protected function getStmtValues($memberId, $account)
{
  switch (true) {
    case $account == 'main':
    $deposits = DB::table($this->tableA)
    ->where('member_id', $memberId)
    ->sum('deposit');
    $withdrawals = DB::table($this->tableA)
    ->where('member_id', $memberId)
    ->sum('withdrawal');
    break;
    case $account == 'education':
    $deposits = DB::table($this->tableB)
    ->where('member_id', $memberId)
    ->sum('deposit');
    $withdrawals = DB::table($this->tableB)
    ->where('member_id', $memberId)
    ->sum('withdrawal');
    break;
    case $account == 'retirement':
    $deposits = DB::table($this->tableC)
    ->where('member_id', $memberId)
    ->sum('deposit');
    $withdrawals = DB::table($this->tableC)
    ->where('member_id', $memberId)
    ->sum('withdrawal');
    break;
    case $account == 'shares':
    $deposits = DB::table($this->tableD)
    ->where('member_id', $memberId)
    ->sum('deposit');
    $withdrawals = DB::table($this->tableD)
    ->where('member_id', $memberId)
    ->sum('withdrawal');
    break;
  }

  $dataArr = array(
    'deposits' => $deposits,
    'withdrawals' => $withdrawals,
  );

  return $dataArr;
}

protected function getUserdata($account, $accountno)
{
  $central = new CentralController();
  switch (true) {
    case $account == 'main':
    $accArr = $this->getAvailableAccNumbers($account);
    $bool = $central->is_inArr($accArr, $accountno);
    break;
    case $account == 'education':
    $accArr = $this->getAvailableAccNumbers($account);
    $bool = $central->is_inArr($accArr, $accountno);
    break; 
    case $account == 'retirement':
    $accArr = $this->getAvailableAccNumbers($account);
    $bool = $central->is_inArr($accArr, $accountno);
    break;
    case $account == 'shares':
    $accArr = $this->getAvailableAccNumbers($account);
    $bool = $central->is_inArr($accArr, $accountno);
    break;

  }

  if($bool == true)
  {
    switch (true) {
      case $account == 'main':
      $userId = User::where('acc_noM', $accountno)
      ->value('id');
      $user = User::where('acc_noM', $accountno)
      ->value('acc_name');
      break;
      case $account == 'education':
      $userId = User::where('acc_noE', $accountno)
      ->value('id');
      $user = User::where('acc_noE', $accountno)
      ->value('acc_name');
      break; 
      case $account == 'retirement':
      $userId = User::where('acc_noR', $accountno)
      ->value('id');
      $user = User::where('acc_noR', $accountno)
      ->value('acc_name');
      break;
      case $account == 'shares':
      $userId = User::where('acc_noS', $accountno)
      ->value('id');
      $user = User::where('acc_noS', $accountno)
      ->value('acc_name');
      break;
    }

    $dataArr = array(
      'boolean' => true,
      'id' => $userId,
      'user' => $user,
    );    

  }

  else if($bool == false)
  {
   $dataArr = array(
    'boolean' => false,
    'id' => null,
  );

 }

 return $dataArr;


}


protected function getAvailableAccNumbers($account)
{
  $accnoArr = array();

  switch (true) {
    case  $account == 'main':
    $accountno_List = User::pluck('acc_noM');
    break;
    case  $account == 'education':
    $accountno_List= User::pluck('acc_noE');
    break;
    case  $account == 'retirement':
    $accountno_List= User::pluck('acc_noR');
    break;
    case  $account == 'shares':
    $accountno_List = User::pluck('acc_noS');
    break;
  }

  foreach ($accountno_List as $accountno) 
  {
    $accnoArr[] = $accountno;
  }

  return $accnoArr;

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
