@extends('layouts.sidebar-header')


@section('content')

<div class="card nunito-font">

 <div class="card-title">
   <h6 class="col-lg-10 text-success pt-4">
    <i class="fa fa-gear"></i> 
    <span class="text-dark">
      <strong>Settings / Account</strong>
    </span>
  </h6>
</div>

<div class="card-body">
  <div class="panel panel-default">

    <div class="panel-body">

      <form class="form" method="post" action="{{ route('account.update', Auth::user()->id) }}">
        @csrf
        <div class="form-group">
          <span class="text-muted">A/C Name</span>
          <input type="text"  class="form-control bg-white" spellcheck="off"
          name="Username" value="{{ Auth::user()->acc_name }}"
           autocomplete="off" readonly 
          >
        </div>

        <div class="form-group">
          <span class="text-muted">Main A/C No.</span>
          <input type="text"  class="form-control bg-white" spellcheck="off"
          name="Username" value="{{ Auth::user()->acc_noM }}"
           autocomplete="off" readonly 
          >
        </div>

          @if(Auth::user()->acc_noE)
        <div class="form-group">
         <span class="text-muted">Education A/C No</span>
         <input type="text" class="form-control bg-white" name="Email" value="{{ Auth::user()->acc_noE }}" spellcheck="off" 
         autocomplete="off"  readonly>
       </div>
       @endif

        @if(Auth::user()->acc_noR)
       <div class="form-group">
        <span class="text-muted">Retirement A/C No.</span>
        <input type="text" class="form-control bg-white" name="Contact" value="{{ Auth::user()->acc_noR }}" autocomplete="off" readonly 
        >
      </div>
      @endif

       @if(Auth::user()->acc_noS)
      <div class="form-group">
        <span class="text-muted">Shares A/C No.</span>
        <input type="text" class="form-control bg-white" name="Address" value="{{ Auth::user()->acc_noS }}" autocomplete="off" readonly>
      </div>
      @endif

      <div class="form-group">
        
          <span class="text-muted">Account pin</span>
          <div class="input-group">
            <input type="password" class="form-control pin" @error('pin') is-invalid @enderror name="pin" id="pin" 
            value="{{ $decryptedUserPin }}">
            <span class="input-group-addon overview-item--c2 f-13 showpin"><i class="fa fa-eye text-white"></i></span>
        </div>
        <span class="pinErr text-danger f-14 nunito-font" ></span>
          @if($errors->has('pin'))
            <span class="nunito-font text-danger">
            @foreach ($errors->get('pin') as $message) 
              <span>{{ $message }} </span>
            @endforeach
          </span>
          @endif
   
    </div>



    <div class="form-group">
      @if(session()->get('success'))
      <div class='alert alert-success alert-dismissible' role='alert'>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
         <span aria-hidden='true'>&times;</span></button>
         <strong>Yello!</strong> {{ session()->get('success') }}
         <i class="fa fa-check-circle"></i>
       </div>
       @endif

       @if(session()->get('fail'))
       <div class='alert alert-danger alert-dismissible' role='alert'>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
         <span aria-hidden='true'>&times;</span></button>
         <i class="fa fa-frown pr-2"></i><strong>Sorry,</strong> {{ session()->get('fail') }}
       </div>
       @endif

     </div>

     <div class="form-group">
       <input type="submit" class="btn btn-success col-lg-2" value="Update Account">
     </div>

   </form>
 </div>
</div>
</div>
</div>

<script>
  $(document).ready(function(){

    var pin = document.getElementById("pin");

    $('.showpin').click(function(){
      (pin.type === "password")
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

  });
</script>
@endsection

