@extends('layouts.sidebar-header')

@section('content')


<div class="card-table">


  <div class="card card-dashboard-table-six">
    <div class="card-title card-mail">

            <h6 class="text-success mail-title bolded nunito-font">
             <i class="fa fa-envelope"></i> Write & Send SMS
           </h6>
            </div> 

   <div class="card-body">

    <div class="panel panel-default">

      <div class="panel-body nunito-font">


        <form class="form" method="post" action="{{ route('sms.store') }}"
        enctype='multipart/form-data'>

        @csrf
        
         

          <div class="form-group">
            <span class="text-muted">Sender</span>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-phone" ></i></span>
              <input type="text"  class="form-control"  value="{{ Auth::user()->contact }}" 
              name="senderContact" placeholder="type your name" 
              autocomplete="on" spellcheck="false">
            </div>
            @error('senderContact')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

        
       

       <div class="form-group">
            <span class="text-muted">Recipient</span>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-phone" ></i></span>
              <input type="text"  class="form-control"  value="{{__('0772833275')}}" 
              name="receiverContact" placeholder="type recipient's name" 
              autocomplete="on" spellcheck="false">
            </div>
            @error('receiverName')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

      <div class="form-group">
        <span class="text-muted">Message</span>
        <textarea class="form-control sms-message"  id="email-message" name="message" spellcheck="true" placeholder="write your email message here"></textarea>
        @error('message')
        <span class="text-danger">{{ $message }}</span>
        @enderror
      </div >

      <div class="col-lg-3">
        <div class="form-group">
          <input type="submit" class="btn btn-success nunito-font" value="Send SMS">
        </div>
      </div>

      <div class="row">

        <div class="col-lg-6">
       <div class="form-group text-center">
          @if(session()->get('MailSendSuccess'))
          <div class='alert alert-success alert-dismissible' role='alert'>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
             <span aria-hidden='true'>&times;</span></button>
             <strong>Yello!</strong> <span>{{ session()->get('MailSendSuccess') }}</span>
           </div>
           @endif

           @if(session()->get('MailSendFailed'))
           <div class='alert alert-danger alert-dismissible' role='alert'>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
             <span aria-hidden='true'>&times;</span></button>
             <strong>Opps!</strong> <span>{{ session()->get('MailSendFailed') }}</span>
           </div>
           @endif


            @if(session()->get('NoInternetErr'))
           <div class='alert alert-danger alert-dismissible' role='alert'>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
             <span aria-hidden='true'>&times;</span></button>
             <strong>Opps!</strong> <span>{{ session()->get('NoInternetErr') }}</span>
           </div>
           @endif
         </div>
       </div>
     </div>
   </form>


 </div>
</div>
</div>
</div>
</div>

<script>
 $(document).ready(function(){
  });
</script>





@endsection

