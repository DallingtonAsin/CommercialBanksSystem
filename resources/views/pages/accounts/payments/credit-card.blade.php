@extends('layouts.sidebar-header')

@section('content')


<div class="row">

  <div class="col-md-7 pt-2 pl-5">
    <i class="fa fa-dollar icon-dollar mb-2"></i>
     <strong class="text-info f-16">Payments <small>
      <i class="fas fa-angle-double-right text-dark"></i> Credit card </small>
     </strong> 
  </div>

   <div class="col-md-5 mr-0 pt-4 pl-5">
     <span class="f-13">Make payments to any account using your credit card</span>
  </div>
  
</div>

  <div class="card nunito-font">
  <div class="card-body">

    <div class="panel panel-default">
      <div class="panel-body">

        <form class="form" method="post" action="">
         @csrf
         <div class="form-group">
          <span class="text-muted">Payment amount</span>
          <span class="input-group">
            <span class="input-group-addon"><i class="fa fa-usd"></i></span>
            <input type="number"  class="form-control bg-white amount"
            name="Amount" placeholder="0.00" required>
          </span>
        </div>

        <div class="form-group">
         <span class="text-muted">Name on card</span>
         <span class="input-group">
          <span class="input-group-addon f-13">
           Name
          </span>
          <input type="text" class="form-control bg-white" name="NameonCard"
          autocomplete="off" placeholder="Enter the name on the card" required>    
        </span>
      </div>

      <div class="form-group">
       <span class="text-muted">Card Number</span>
       <span class="input-group">
        <span class="input-group-addon f-13">Number</span>
        <input type="text" class="form-control" name="CardNumber" 
        autocomplete="off" placeholder="Enter your card number" required>
      </span>
    </div>

    <div class="form-group">
       <span class="text-muted">Security code</span>
      <span class="input-group">
        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
        <input type="text" class="form-control" name="amount"
        placeholder ="Enter security code on the card"
        autocomplete="off" required>
      </span>
    </div>

        <div class="form-group">
      <span class="text-muted">Expiration</span>
      <span class="input-group">
        <span class="input-group-addon f-13">Exp date</span>
        <input type="number" class="form-control" name="amount"
        placeholder ="Enter expiration date of the card"
        autocomplete="off" required >
      </span>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-lg overview-item--c2 text-white f-15"><i class="fa fa-lock pr-1" ></i> Pay shs.<span class="pl-1 btn-amount">0.00</span> </button>
   </div>

   <div class="form-group">
     @if(session()->get('updateProfileSuccess'))
     <div class='alert alert-success alert-dismissible' role='alert'>
       <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span></button>
        <strong>Yello!</strong> {{ session()->get('updateProfileSuccess') }}
      </div>
      @endif

      @if(session()->get('updateProfileFailed'))
      <div class='alert alert-danger alert-dismissible' role='alert'>
       <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span></button>
        <strong>Opps!</strong> {{ session()->get('updateProfileFailed') }}
      </div>
      @endif

    </div>
  </form>


</div>
</div>
</div>
</div>



<script>

  var momoPin = document.getElementById("MomoPin");

  $('#showMomoPin').click(function(){
    if (momoPin.type === "password") {
     momoPin.type = "text";
   }
   else {
     momoPin.type = "password";
   } 
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


</script>




@endsection

