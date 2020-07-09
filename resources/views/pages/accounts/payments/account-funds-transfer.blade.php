@extends('layouts.sidebar-header')

@section('content')



<div class="panel panel-default">

  <div class="panel-heading">
    <div class="row">
      <div class="col-lg-7 pl-5">

       <label class="text-info f-14 nunito-font">
         Main A/C <span class="f-13 text-info">Funds Transfer</span>
       </label>
     </div>
     <div class="col-lg-5 mr-0 pt-1 nunito-font">
       <span class="f-14">
         Transfer funds to one of your accounts to any other account
       </span>
     </div>
   </div>
 </div>

 <div class="panel-body">
  <div class="panel panel-default">
    <div class="panel-body nunito-font">
      <form class="form" method="post" action="{{ Route('payments.store') }}">
       @csrf

       <div class="row">
         <div class="col-lg-12 nunito-font">
           @if(session()->get('success'))
           <div class='alert alert-success alert-dismissible text-center' role='alert'>
             <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span></button>
              <strong>Yello!</strong> 
              {{ session()->get('success') }}
              <i class="fa fa-check-circle"></i>
            </div>
            @endif

            @if(session()->get('fail'))
            <div class='alert alert-danger alert-dismissible text-left' role='alert'>
             <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span></button><i class="fa fa-frown pr-2"></i>{{ session()->get('fail') }}
              
            </div>
            @endif

            @isset($pinErr)
            @if($pinErr)
            <div class='alert alert-danger alert-dismissible text-left' role='alert'>
             <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span></button><i class="fa fa-frown pr-2"></i><strong>Sorry,</strong>{{ $pinErr['message'] }}
              <a class="text-info"> {{ $pinErr['url'] }} </a>
              
            </div>
            @endif
            @endisset
          </div>
        </div>


        <div class="col-lg-6">
         <span class="text-muted">Sender A/C No</span>
         <div class="input-group">
          <span class="input-group-addon f-12">A/C no</span>
          <input type="text" class="form-control" name="SenderAcNo"  placeholder="Enter your A/C Number"  value="{{ Auth::user()->acc_noM }}" 
          autocomplete="on"  required>
        </div>
      </div>

      <div class="col-lg-6">
       <span class="text-muted">Sender's Name</span>
       <span class="input-group">
        <span class="input-group-addon f-12">Name</span>
        <input type="text" class="form-control" name="SenderNames"  placeholder="Enter your depositor's name" value="{{ Auth::user()->name }}"
        autocomplete="on"  required>
      </span>
    </div>



    <div class="col-lg-6">
     <span class="text-muted">Receiver's A/C No</span>
     <div class="input-group">
      <span class="input-group-addon f-12">A/C no</span>
      <input type="text" class="form-control" name="ReceiverAcNo"  placeholder="Enter receiver's A/C Number" value="{{ old('ReceiverAcNo') }}" 
      id="receiverAccountNo" autocomplete="on"  required>
    </div>
  </div>

  <div class="col-lg-6">
   <span class="text-muted">Receiver's Name</span>
   <span class="input-group">
    <span class="input-group-addon f-12">Name</span>
    <input type="text" class="form-control bg-white text-success" name="ReceiverNames"  placeholder="Receiver's A/C names"  id="receiverNames" disabled 
    autocomplete="on" value="{{ old('ReceiverNames') }}" required>
  </span>
</div>

<div class="col-lg-6">
 <span class="text-muted">Branch Name</span>
 <span class="input-group">
  <span class="input-group-addon fa-13">Branch</span>
  <input type="text" class="form-control" name="Branch" placeholder="Enter branch name" value="Mobile/E-banking" 
  autocomplete="on"  required>
</span>
</div>


<div class="col-lg-6">
  <span class="text-muted">Reason</span>
  <div class="input-group">
    <span class="input-group-addon f-12">Reason</span>
    <select name="reason" class="form-control bg-white" required disabled>
      <!-- <option value="zero" >Please select reason</option> -->
      <option value="one">Saving to Main Saving A/C</option>
      <option value="two">Saving to Education Saving A/C</option>
      <option value="three">Saving to Retirement Saving A/C</option>
      <option value="four">Saving to Shares Saving A/C</option>
      <option value="five">Paying Loan</option> 
      <option value="six">Other</option>   
    </select>
  </div>
</div>

<div class="col-lg-6">
  <span class="text-muted">Funds to transfer</span>
  <span class="input-group">
    <span class="input-group-addon f-12">Amount</span>
    <input type="number" class="form-control amount" name="amount"
    placeholder ="Enter amount of money to send" value="{{ old('amount') }}" 
    autocomplete="on" required id="amount" >
  </span>
</div>

<div class="col-lg-6">
 <span class="text-muted">Tel No</span>
 <div class="input-group">
  <span class="input-group-addon"><i class="fa fa-phone"></i></span>
  <input type="tel" class="form-control bg-white" name="telNo" placeholder="Enter your mobile money telephone number" value="{{ Auth::user()->tel_no }}" autocomplete="on" readonly required>
</div>
</div>

<div class="form-group col-lg-6">
  <span class="text-muted">Account Pin</span>
  <div class="input-group">
   <span class="input-group-addon"><i class="fa fa-lock"></i></span>
   <input type="password" id="pin" name="AccPin" 
   class="form-control" placeholder = "Enter your Account pin">
   <input type="hidden" name="paymentMode" value="systemPay">
   <span class="input-group-addon showPin" required>
    <i class="fa fa-eye"></i></span>
  </div>
  <span class="pinErr text-danger f-14 nunito-font" ></span>
</div>

<div class="col-lg-4 mt-4 pt-2">
  <button type="submit" class="btn btn-lg overview-item--c2 text-white f-15"><i class="fa fa-lock pr-1"></i> Pay shs.<span class="pl-1 btn-amount">0.00</span>
  </button>
</div>


</form>
</div>
<div class="panel-footer nunito-font">
 <span>Transfer funds from one account to another account from here</span>
</div>
</div>
</div>
</div>



<script>

  $(document).ready(function(){

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var pin = document.getElementById("pin");

    $('.showPin').click(function(){
     pin.type === "password"
     ? pin.type = "text"
     : pin.type = "password";
   });

    var pinErrLength = 'Pin must be less than 4 digits';

    $('#pin').bind('input', function( event ){
      var pinValue = $('#pin').val();
      (pinValue.length > 4)
      ? $('.pinErr').text(pinErrLength)
      : $('.pinErr').text('');   
    });

    $('.amount').bind('input', function( event )
    {
     setAmt();
   });

    function setAmt()
    {
     var amount, amt;
     var amount = $('.amount').val();
     var amt = parseInt(amount).toLocaleString('us', {minimumFractionDigits: 0, maximumFractionDigits: 0});
     $('.btn-amount').text(amt);

   }

   var query = $('#receiverAccountNo').val();
   $("#receiverAccountNo").typeahead({
    source:function(query,result){
      $.ajax({
        url:"{{ Route('mainaccount.search') }}",
        method:'post',
        data:{
          query:query,
        },
        dataType:'json',
        success: function(data){
          result($.map(data, function(item){
            return item;
          }));
        },
        error:function(data){
          console.log(data);
        },
      });
    }
  });

     $('#receiverAccountNo').bind('change', function(){
      var input = $('#receiverAccountNo').val();
      $.ajax({
        url:"{{ Route('accountnames.search') }}",
        method:'post',
        data:{
          item:input,
        },
        dataType:'json',
        success: function(data){
          $('#receiverNames').val(data);
          console.log(data);
        },
        error:function(data){
          console.log('data');
        },
      });

    });


 });

</script>




@endsection

