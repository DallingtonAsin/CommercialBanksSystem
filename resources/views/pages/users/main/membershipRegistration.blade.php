@extends('layouts.app')



@section('content')

<div class="container nunito-font">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card register-card">
                <div class="card-header green-shade pl-3">
                 <img src="{{ asset('images/icon/user-list.png') }}" class="icon-i text-info" />
                 <strong class="text-white">{{ __('Membership Application Form') }}</strong>
             </div>

             <div class="card-body">

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
                    <strong>Sorry!</strong> {{ session()->get('fail') }}
                </div>
                @endif

                <style type="text/css">
                    .previous-tab,
                    .next-tab{
                        display: inline-block;
                        border: 1px solid #444348;
                        border-radius: 3px;
                        margin: 5px;
                        text-align:  center !important;
                        font-size: 14px;
                        padding: 10px 15px;
                        cursor: pointer;
                        width: 90px !important;
                    }
                    .submit-btn,
                    .reset-btn{
                        display: inline-block;
                        text-align: center !important;
                        border: 1px solid #444348;
                        border-radius: 3px;
                        margin: 5px;
                        font-size: 14px;
                        padding: 10px 15px;
                        cursor: pointer;
                        width: 85px !important;
                    }
                </style>



                <form method="POST" class="px-3"
                action="{{ route('membership.store') }}">
                @csrf

                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


                <h4><i class="text-danger">*</i>
                 <span class="pr-2 nunito-font pb-1">Fill this registration form</span>
             </h4>
                @if($errors->any())
                       <h5 class="text-danger pl-3 nunito-font">
                        Registration failed, please check errors in the errors tab
                       </h5>
                    @endif


             <ul class="nav nav-tabs" id="myTabs" role="tablist">

                <li role="presentation" class="active"><a href="#step1" aria-controls="step1" role="tab" data-toggle="tab">Step1</a></li>
                <li role="presentation"><a href="#step2" aria-controls="step2" role="tab" data-toggle="tab">Step2</a></li>

                <li role="presentation"><a href="#step3" 
                    aria-controls="step3" role="tab" data-toggle="tab">Step3</a></li> 

                    @if($errors->any())
                    <li role="presentation"><a href="#step4" class="text-danger" 
                    aria-controls="step3" role="tab" data-toggle="tab">Errors</a></li> 
                    @endif

                </ul>



                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane active" id="step1">
                        <div class="form-group">
                            <label for="fname" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                            {{ __('First Name') }}</label>
                            <input id="fname" type="text" class="form-control" name="firstName" value="{{old('firstName')}}" placeholder="Enter your first name" autocomplete="name" autofocus>
                        </div>


                        <div class="form-group">
                            <label for="middleName" class="col-form-label text-muted text-md-right">{{ __('Middle Name') }}</label>

                            <input id="middleName" type="text" class="form-control" name="middleName" value="{{old('middleName')}}" placeholder="Enter your middle name">
                        </div>

                        <div class="form-group">
                            <label for="lastName" class="col-form-label  text-muted text-md-right"><i class="text-danger">*</i>
                            {{ __('Last Name') }}</label>

                            <input id="lastName" type="text" class="form-control" name="lastName" value="{{old('lastName')}}" placeholder="Enter your last name"  autofocus>
                        </div>

                        <div class="form-group">
                            <label for="address" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                            {{ __('Address') }}</label>

                            <input id="address" type="text" class="form-control" name="address" value="{{old('address')}}" placeholder="Enter your city" >
                        </div>



                        <div class="form-group">
                            <label for="name" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                            {{ __('City') }}</label>

                            <input id="city" type="text" class="form-control" name="city" value="{{old('city')}}" placeholder="Enter your city" >
                        </div>

                        <span class="next-tab green-shade text-white">Next</span>
                        <span class="previous-tab green-shade text-white">Previous</span>

                    </div>


                    <div role="tabpanel" class="tab-pane" id="step2">

                     <div class="form-group">
                        <label for="state" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                        {{ __('State') }}</label>

                        <input id="state" type="text" class="form-control" name="state" value="{{old('state')}}" placeholder="Enter your state" >
                    </div>

                    <div class="form-group">
                        <label for="zipcode" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                        {{ __('Zip Code') }}</label>

                        <input id="zipcode" type="text" class="form-control" name="zipcode" value="{{old('zipcode')}}" placeholder="Enter zip code">
                    </div>


                    <div class="form-group">
                        <label for="PrimaryTelNo" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                        {{ __('Primary Phone Number') }}</label>

                        <input id="PrimaryTelNo" type="text" class="form-control" name="PrimaryTelNo" value="{{old('PrimaryTelNo')}}" placeholder="Enter phone number"  autofocus>
                    </div>


                    <div class="form-group">
                        <label for="Mobile2" class="col-form-label text-muted text-md-right">{{ __('Alternative Phone Number') }}</label>

                        <input id="Mobile2" type="text" class="form-control" name="Mobile2" value="{{old('Mobile2')}}" placeholder="Enter another number">
                    </div>

                    <div class="form-group">
                        <label for="occupation" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                        {{ __('Occupation') }}</label>

                        <input id="occupation" type="text" class="form-control" name="occupation" value="{{old('occupation')}}" placeholder="Enter your occupation" >
                    </div>

                    <span class="next-tab green-shade text-white">Next</span>
                    <span class="previous-tab green-shade text-white">Previous</span>

                </div>


                <div role="tabpanel" class="tab-pane" id="step3">

                 <div class="form-group">
                    <label for="company" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                    {{ __('Company or Institution') }}</label>

                    <input id="company" type="text" class="form-control" name="company" value="{{old('company')}}" placeholder="Enter your company" >
                </div> 

                <div class="form-group">
                    <label for="dateOfBirth" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                    {{ __('Date of Birth') }}</label>

                    <input id="dob" type="date" class="form-control" name="dateOfBirth" value="{{old('dateOfBirth')}}" placeholder="Enter phone number"  autofocus>
                </div>

                <div class="form-group">
                    <label for="email" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                    {{ __('Email') }}</label>
                    <input id="email" type="email" class="form-control" placeholder="Enter your email" name="email" value="{{old('email')}}"  autocomplete="email">
                </div>

                <div class="row">
                    <div class="form-group col-lg-5">
                        <label for="name" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                        {{ __('Gender') }}</label>
                        <div class="form-inline">
                            <input  type="radio" name="Gender" value="female"  checked>
                            <span class="pl-2">Female</span>&nbsp;
                            <input  type="radio" name="Gender" value="male">
                            <span   class="pl-2">Male</span>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="cv" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                        {{ __('Your CV') }}</label>
                        <input id="cv" type="file" class="form-control-file" 
                        name="attached-file" value="" >
                        <input type="hidden" name="account" value="membership">
                    </div>
                </div>

                <div class="row">


                    <button type="submit" class="submit-btn btn-primary text-white">
                        {{ __('Submit') }}
                    </button>
                    <button type="reset" class="reset-btn btn-danger text-white">
                        {{ __('Reset') }}
                    </button>
                    <span class="next-tab green-shade text-white">Next</span>
                    <span class="previous-tab green-shade text-white">Previous</span>
                </div>


                <div class="row">
                    <a class="text-decoration-none px-2" href="{{ url('/') }}">
                        {{ __('Already have Member Account? Login') }}
                    </a>

                </div>

            </div>   

             @if ($errors->any())  
              <div role="tabpanel" class="tab-pane" id="step4">  
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
                @endif

        </div>

        <script>  

            var fname, lname, address, city;
            fname = $("#fname").val();
            lname = $("#lastName").val();
            address = $("#address").val();
            city = $("#city").val();

            if(fname.length==0 && lname.length==0 && address.length==0 && city.length==0){
             $('#next-tab').attr("disabled", true);
             $('#previous-tab').attr("disabled", true);
         }else{
            $('#next-tab').attr("disabled", false);
            $('#previous-tab').attr("disabled", false);
        }


              //$(".submit-btn").attr("disabled", true);
              jQuery('body').on('click','.next-tab', function(){
                  var next = jQuery('.nav-tabs > .active').next('li');
                  if(next.length){
                    next.find('a').trigger('click');
                }else{
                    jQuery('#myTabs a:first').tab('show');
                }
            });

              jQuery('body').on('click','.previous-tab', function(){
                  var prev = jQuery('.nav-tabs > .active').prev('li')
                  if(prev.length){
                    prev.find('a').trigger('click');
                }else{
                    jQuery('#myTabs a:last').tab('show');
                }
            });            

        </script>



    </form>
</div>
</div>
</div>
</div>
</div>
@endsection
