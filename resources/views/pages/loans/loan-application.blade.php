@extends('layouts.sidebar-header')

@section('content')


<div class="card">
  <div class="card-body">
<div class="row nunito-font">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <span class="text-dark bolded f-400">
          <img src="{{ asset('images/icon/usd.png') }}" class="icon-i mb-2">
          Loan application form
        </span>
      </div>
      <div class="panel-body bg-white">


        <div class="nunito-font text-center">
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
            <strong>Opps!</strong> {{ session()->get('fail') }}
          </div>
          @endif
        </div>

        <form action="{{ Route('loan.store') }}" method="post" class="pt-3" 
            enctype="multipart/form-data">
          @csrf
          <div class="form-group row">

            <div class="col-lg-6">
              <span for="firstName" class="control-label mb-1 text-muted">
                <i class="text-danger">*</i> First Name</span>
                <div class="input-group">
                  <span class="input-group-addon">first name</span>
                  @if(Auth::user()->middle_name)
                  <input id="firstName" name="firstName" type="text" class="form-control bg-white" placeholder="Enter your first and middle name"
                  value="{{ Auth::user()->first_name }}{{ Auth::user()->middle_name }}" required readonly>
                  @else
                  <input id="firstName" name="firstName" type="text" class="form-control bg-white" placeholder="Enter your first name"
                  value="{{ Auth::user()->first_name }}" required readonly>
                  @endif
                </div>
              </div>



              <div class="col-6">
                <span for="lastName" class="control-label text-muted">
                  <i class="text-danger">*</i> Last Name</span>

                  <div class="input-group">
                   <span class="input-group-addon">
                    last name
                  </span>
                  <input id="lastName" name="lastName" type="text" class="form-control  bg-white"  placeholder="Enter your last name" value="{{ Auth::user()->last_name }}" required readonly>
                </div>
              </div>

              <div class="col-6">
                <span for="address" class="control-label mb-1 text-muted">
                  <i class="text-danger">*</i> Address</span>
                  <div class="input-group">
                    <span class="input-group-addon">address</span>
                    <input id="address" name="address" type="text" class="form-control" value="{{ Auth::user()->address }}" placeholder="Enter your address" required>
                  </div>
                </div>

                <div class="col-lg-3">
                  <span for="dateOfBirth" class="control-label mb-1 text-muted">
                    <i class="text-danger">*</i> Date of Birth</span>
                    <div class="input-group">
                      <span class="input-group-addon">DOB</span>
                      <input id="dateOfBirth" name="dob" type="date" class="form-control" value="{{ Auth::user()->date_of_birth }}"  placeholder="Enter your date of birth" required>
                    </div>
                  </div>

                  @php
                  if(Auth::user()->gender == 'male'){
                  $checked = 'false';
                }
                else{
                $checked = 'true';
              }
              @endphp


              <div class="col-lg-3 pt-2 ">
                <span for="name" class="col-form-label text-muted text-md-right"><i class="text-danger">*</i>
                {{ __('Gender') }}</span>
                <div class="form-inline pt-2">
                  <input  type="radio" name="Gender" value="female" checked="{{ $checked }}">
                  <span class="pl-2">Female</span>&nbsp;
                  <input  type="radio" name="Gender" value="male">
                  <span   class="pl-2" checked="{{ $checked }}">Male</span>
                  @error('gender')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
              </div>

              <div class="col-lg-6">
                <span for="occupation" class="control-label mb-1 text-muted">
                  <i class="text-danger">*</i> Occupation</span>
                  <div class="input-group">
                    <span class="input-group-addon">occupation</span>
                    <input id="occupation" name="occupation" type="text" class="form-control" placeholder="Enter your Occupation" value="{{ Auth::user()->occupation }}" required>
                  </div>
                </div>

                <div class="col-lg-6">
                  <span for="email" class="control-label mb-1 text-muted">Email<small class="text-danger pl-2">*optional</small>
                  </span>
                  <div class="input-group">
                   <span class="input-group-addon">
                    <i class="fa fa-envelope"></i>
                  </span>
                  <input id="email" name="email" type="email" class="form-control" value="{{ Auth::user()->email }}"  placeholder="Enter your email">
                </div>
              </div>

              <div class="col-lg-6">
                <span for="email" class="control-label mb-1 text-muted">
                 <i class="text-danger">*</i> Phone number</span>
                 <div class="input-group">
                   <span class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </span>
                  <input name="phone1" type="text" class="form-control"  placeholder="Enter your telephone number" value="{{ Auth::user()->tel_no }}" required>
                </div>
              </div>


              <div class="col-lg-6">
                <span class="control-label mb-1 text-muted">
                  Alternative phone number <small class="text-danger pl-2">*optional</small></span>
                  <div class="input-group">
                   <span class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </span>
                  <input name="phone2" type="tel" class="form-control" value="{{ Auth::user()->alt_telno }}"  placeholder="Enter your alternative number">
                </div>
              </div>

              <div class="col-lg-6">
                <span class="control-label mb-1 text-muted">
                  <i class="text-danger">*</i> Loan amount</span>
                  <div class="input-group">
                    <span class="input-group-addon">amount</span>
                    <input name="loanAmount" type="number" class="form-control" value="" placeholder="Enter amount" required>
                  </div>
                </div>

                <div class="col-lg-6">
                  <span class="control-label mb-1 text-muted">
                    <i class="text-danger">*</i> Duration</span>
                   
                    <div class="input-group">
                      <span class="input-group-addon">duration</span>
                      <input name="loanDuration" type="number" class="form-control " value="" placeholder="Enter how long you need to use this loan" required>
                    </div>

                    <div>
                    <input  type="radio" name="durationIn" value="years" checked>
                    <span class="pl-2">years</span>&nbsp;
                    <input  type="radio" name="durationIn" value="months">
                    <span   class="pl-2" checked="">months</span>
                    <input  type="radio" name="durationIn" value="days">
                    <span   class="pl-2" checked="">days</span>
                    </div>
               

                  </div>

                  <div class="col-lg-6">
                    <span class="control-label mb-1 text-muted">
                      <i class="text-danger">*</i> Collateral</span>
                      <div class="input-group">
                        <span class="input-group-addon">security</span>
                        <input name="loanSecurity" type="text" class="form-control" value="" placeholder="Enter your collateral or property" required>
                      </div>
                    </div>

                    <div class="col-lg-6 pt-2">
                      <label for="file-input" class=" form-control-label text-muted"><i class="text-danger">*</i> Your CV</label>
                      <input type="file" id="file-input" name="loan-file-input" class="form-control-file">
                    </div>
                  </div>

                  <div class="row form-group">

                    <div class="col-lg-6">
                      <label class="control-label mb-1 text-muted">
                        <i class="text-danger">*</i> Write a statement why you need a loan</label>
                        <textarea id="mail-message" name="loanStmt" type="text" class="form-control" required></textarea>
                      </div>

                      <div class="col-lg-6 pt-5">
                       <button type="submit" class="btn overview-item--c2 text-white nunito-font btn-md">
                         <i class="fa fa-dot-circle-o"></i> Submit Application
                       </button>    
                       <button type="reset" class="btn btn-danger btn-md">
                        <i class="fa fa-ban"></i> Reset info
                      </button>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div> 
        </div>  
      </div>
    </div>
  

        <script>
          $(document).ready(function(){
          // var config = {};
          // config.placeholder = 'some value'; 
          // CKEDITOR.replace('mail-message', config);
      // $('.event-time').wickedpicker({
      //     twentyFour:true,
      // });
    });
  </script>               

  @endsection



