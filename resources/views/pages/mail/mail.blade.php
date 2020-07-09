@extends('layouts.sidebar-header')

@section('content')

<div class="row">
    <div class="col-lg-12 nunito-font">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong class="text-info">
                 <i class="zmdi zmdi-email text-dark icon-i f-13"></i>Send email</strong>
            </div>
            <div class="panel-body">
                <form class="form" method="post" action="{{ route('mail.store') }}" enctype='multipart/form-data'>
                  @csrf

                    <div class="row">

                        <div class="col-6 form-group">
                            <span class="control-label mb-1">Subject</span>
                            <div class="input-group">
                            <span class="input-group-addon f-13">subject</span>
                            <input id="cc-pament" name="subject" type="text" class="form-control" aria-required="true" aria-invalid="false" value="" placeholder="Enter subject of your message">
                        </div>
                        </div>


                          <div class="col-6 form-group">
                            <span for="cc-exp" class="control-label mb-1">
                            Receiver's Email</span>
                            <div class="input-group">
                             <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <input id="cc-exp" name="receiverEmail" type="tel" class="form-control cc-exp" value="" data-val="true" data-val-required="Please enter the card expiration"
                            data-val-cc-exp="Please enter a valid month and year" placeholder="Enter email you are sending an email..."
                            autocomplete="cc-exp">
                        </div>
                    </div>

                        <div class="col-6 form-group">
                            <span for="cc-exp" class="control-label mb-1">Your Email</span>
                            <div class="input-group">
                             <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <input id="cc-exp" name="senderEmail" type="tel" class="form-control cc-exp" value="" data-val="true" data-val-required="Please enter the card expiration"
                            data-val-cc-exp="Please enter a valid month and year" placeholder="Enter your email"
                            autocomplete="cc-exp">
                        </div>
                    </div>



                   
                 <div class="col-lg-6 form-group">
                    <label for="file-input" class=" form-control-label">Attachment</label> <small class="text-danger">* optional</small>
                    <input type="file" id="file-input" name="email-attachment" class="form-control-file">
                </div>

             
                <div class="col-lg-12 form-group">
                    <label for="comments" class="control-label mb-1 f-14">Write your mail message here</label>
                    <textarea id="mail-message" name="message" type="text" class="form-control cc-name valid" data-val="true" data-val-required="Please enter the name on card"
                    autocomplete="cc-name" aria-required="true" aria-invalid="false" aria-describedby="cc-name-error"></textarea>

                    <span class="help-block field-validation-valid" data-valmsg-for="cc-name" data-valmsg-replace="true"></span>
                </div>

             

                <div class="col-lg-6 form-group">
                 <button type="submit" class="btn btn-primary btn-md">
                 <i class="fa fa-dot-circle-o"></i> Submit
                </button>    
                <button type="reset" class="btn btn-danger btn-md">
                <i class="fa fa-ban"></i> Reset
                </button>
                </div>

            </div>

        </form>
    </div>
</div>
</div> 
</div>  

<script>
    $(document).ready(function(){
      var config = {};
      config.placeholder = 'some value'; 
      CKEDITOR.replace('mail-message', config);
      // $('.event-time').wickedpicker({
      //     twentyFour:true,
      // });
  });
</script>               

@endsection



