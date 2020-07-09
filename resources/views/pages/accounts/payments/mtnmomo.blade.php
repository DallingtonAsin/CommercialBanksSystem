@extends('layouts.sidebar-header')

@section('content')

<div class="panel panel-default">

  <div class="panel-heading">
    <div class="row">
      <div class="col-lg-6 pl-5">

       <label class="text-info f-14 nunito-font">
         <!--  <img src="{{ asset('images/icon/sente.png') }}" class="mb-4 icon-i text-info" /> -->
         Payments:: 
         <span class="f-13 text-info">Mobile Money</span>
       </label>
     </div>
     <div class="col-lg-6 mr-0 pt-1">
       <span class="f-13">
         Deposit from your mobile money account to any sacco account
       </span>
     </div>
   </div>
 </div>


 <div class="panel-body">
  <div class="panel panel-default">
    <div class="panel-body nunito-font">
      <form class="form" method="post" action="{{ Route('momo.deposit') }}">
       @csrf

       <div class="row">
         <div class="col-lg-12 nunito-font text-center">
           @if(session()->get('success'))
           <div class='alert alert-success alert-dismissible' role='alert'>
             <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span></button>
              <strong>Yello!</strong> 
              {{ session()->get('success') }}
              <i class="fa fa-check-circle"></i>
            </div>
            @endif

            @if(session()->get('fail'))
            <div class='alert alert-danger alert-dismissible' role='alert'>
             <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span></button>
              <i class="fa fa-frown"></i> Sorry,{{ session()->get('fail') }}
            </div>
            @endif
          </div>
        </div>



        <div class="row">

         <div class="form-group col-lg-6">
           <span class="text-muted">A/C No</span>
           <div class="input-group">
            <span class="input-group-addon f-12">A/C no</span>
            <input type="text" class="form-control" name="AcNo" id="accNo"  placeholder="Enter your A/C Number"  value="{{ Auth::user()->acc_noM }}"  autocomplete="on"  required>
          </div>
        </div>

        <div class="form-group col-lg-6">
         <span class="text-muted">A/C Name</span>
         <span class="input-group">
          <span class="input-group-addon f-12">A/C name</span>
          <input type="text" class="form-control" name="AcName"  placeholder="Enter your A/C Name" value="{{ Auth::user()->acc_name }}"  id="accNames" 
          autocomplete="on"  required>
        </span>
      </div>

    </div>

    <div class="row">
      <div class="form-group col-lg-6">
       <span class="text-muted">Depositor's Name</span>
       <span class="input-group">
        <span class="input-group-addon f-12">Name</span>
        <input type="text" class="form-control" name="Depositor"  placeholder="Enter your depositor's name" value="{{ Auth::user()->name }}" 
        autocomplete="on"  required>
      </span>
    </div>

    <div class="form-group col-lg-6">
     <span class="text-muted">Branch Name</span>
     <span class="input-group">
      <span class="input-group-addon fa-13">Branch</span>
      <input type="text" class="form-control" name="Branch" placeholder="Enter branch name" value="Mobile/E-banking" 
      autocomplete="on"  required>
    </span>
  </div>
</div>

<div class="row">
 <div class="form-group col-lg-6">
  <span class="text-muted">Reason</span>
  <div class="input-group">
    <span class="input-group-addon f-12">Reason</span>
    <select name="reason" class="form-control" required>
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

<div class="form-group col-lg-6">
  <span class="text-muted">Amount</span>
  <span class="input-group">
    <span class="input-group-addon f-12">Amount</span>
    <input type="number" class="form-control amount" name="amount"
    placeholder ="Enter amount of money to send" value="{{ old('amount')}}" 
    autocomplete="on" required >
  </span>
</div>
</div>


<div class="row">

 <div class="form-group col-lg-6">
   <span class="text-muted">Tel No</span>
   <div class="input-group">
    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
    <input type="tel" class="form-control" name="telNo" placeholder="Enter your mobile money telephone number" value="{{ Auth::user()->tel_no }}" autocomplete="on"  required>
  </div>
</div>

<div class="form-group col-lg-6">
  <span class="text-muted">Mobile Money PIN</span>
  <div class="input-group">
   <span class="input-group-addon"><i class="fa fa-lock"></i></span>
   <input type="password" id="MomoPin" name="MomoPin" 
   class="form-control" placeholder = "Enter your mobile money pin" required>
   <input type="hidden" name="paymentMode" value="mobile money">
   <span class="input-group-addon" id="showMomoPin">
    <i class="fa fa-eye"></i>
  </span>
</div>
</div>
</div>

<div class="row">
  <div class="col-lg-4">
    <button type="submit" class="btn btn-lg overview-item--c2 text-white f-15">
      <i class="fa fa-lock pr-1" ></i> Pay shs.<span class="pl-1 btn-amount">0.00
      </span>
    </button>
  </div>
</div>

</form>
</div>
<div class="panel-footer nunito-font">
 <span>Deposit from your mobile money account to any sacco account</span>
</div>
</div>
</div>
</div>



<script>
  $(document).ready(function(){
    var momoPin = document.getElementById("MomoPin");

    $('#showMomoPin').click(function(){
      (momoPin.type === "password")
       ? momoPin.type = "text"
       : momoPin.type = "password";
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

   var query = $('#accNo').val();
   $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
   $("#item").typeahead({
    source:function(query,result){
      $.ajax({
        url:"",
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
          console.log('am not getting anything');
        },
      });
    }
  });

   $('#accNo').change(function(){
    var selected_item = $('#accNo').val();
    $.ajax({
      url:"",
      method:'post',
      data:{
        item:selected_item,
      },
      dataType:'json',
      success: function(data){
        $('#accNames').val(data);
        console.log(data);
      },
      error:function(data){
        console.log('am not getting any price');
      },
    });

  });



 });


</script>




@endsection

