@extends('layouts.sidebar-header')

@section('content')
<div class="card">
  <div class="card-body">
<div class="row">
  <div class="col-lg-12 nunito-font">
    <div class="panel panel-default">
      <div class="panel-heading">
        <span class="text-info bolded f-400">
          <img src="{{ asset('images/icon/users.png') }}" class="icon-i mb-2">
            Account application form for members
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
            <i class="fa fa-frown pr-2"></i><strong>Sorry</strong> {{ session()->get('fail') }}
          </div>
          @endif

           @if(session()->get('warning'))
          <div class='alert alert-warning alert-dismissible' role='alert'>
           <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span></button>
            <i class="fa fa-info-circle"></i>
            <strong class="pr-3" >Notification </strong> {{ session()->get('warning') }}
            
          </div>
          @endif
        </div>

        <form action="{{ Route('applications.store') }}" method="post" class="pt-3">
          @csrf
          <div class="form-group row">

            <div class="col-lg-3">
              <span for="firstName" class="control-label mb-1 text-muted">
                <i class="text-danger">*</i> First Name</span>
                <div class="input-group">
                  <span class="input-group-addon">first name</span>
                  <input id="firstName" name="firstName" type="text" class="form-control bg-white" placeholder="Enter your first name"
                  value="{{ Auth::user()->first_name }}" required readonly>
                </div>
              </div>

              @if(Auth::user()->middle_name)
                 <div class="col-3">
                <span for="middleName" class="control-label bg-white text-muted">
                  <i class="text-danger">*</i>Middle Name</span>

                  <div class="input-group">
                   <span class="input-group-addon">
                    middle name
                  </span>
                  <input id="lastName" name="middleName" type="text" class="form-control bg-white"  placeholder="Enter your middle name" value="{{ Auth::user()->middle_name }}" readonly>
                </div>
              </div>
              @endif

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
                <span for="MainA/C" class="control-label text-muted">
                  <i class="text-danger">*</i> Main A/C no</span>

                  <div class="input-group">
                   <span class="input-group-addon">
                    Main A/C No.
                  </span>
                  <input id="MainA/C" name="MainA/CNo" type="text" class="form-control  bg-white"  placeholder="Enter your Main A/C No" value="{{ Auth::user()->acc_noM }}" required readonly>
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

                <div class="col-lg-6">
                  <span for="dateOfBirth" class="control-label mb-1 text-muted">
                    <i class="text-danger">*</i> Date of Birth</span>
                    <div class="input-group">
                      <span class="input-group-addon">DOB</span>
                      <input id="dateOfBirth" name="dateOfBirth" type="date" class="form-control" value="{{ Auth::user()->date_of_birth }}"  placeholder="Enter your date of birth" required>
                    </div>
                  </div>

                  @php
                  if(strtolower(Auth::user()->gender) == "male"){
                    $checkedm = true;
                    $checkedf = false;
                    $gender = 'Male';
                  }
                  else if(strtolower(Auth::user()->gender) == "female"){
                    $checkedf = true;
                    $checkedm = false;
                    $gender = 'Female';
                  }
                  @endphp

                  <div class="col-lg-3">
                    <span for="occupation" class="control-label mb-1 text-muted">
                      <i class="text-danger">*</i> Occupation</span>
                      <div class="input-group">
                        <span class="input-group-addon">occupation</span>
                        <input id="occupation" name="occupation" type="text" class="form-control" placeholder="Enter your Occupation" value="{{ Auth::user()->occupation }}" required>
                      </div>
                    </div>

                     <div class="col-lg-3">
                    <span for="company" class="control-label mb-1 text-muted">
                      <i class="text-danger">*</i> Company</span>
                      <div class="input-group">
                        <span class="input-group-addon">company</span>
                        <input id="company" name="company" type="text" class="form-control" placeholder="Enter your company" value="{{ Auth::user()->company }}" required>
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

                <div class="col-lg-6 pt-3">
                  <span class="control-label mb-1 text-muted">
                    <i class="text-danger">*</i> Account being applied for</span>
                    <div class="input-group">
                      <span>
                      <input type="radio" name="account" value="education" checked>
                      <span>Education A/C</span>
                    </span>
                    <span class="pl-3">
                       <input type="radio" name="account" value="retirement"><span class="pl-2">Retirement A/C</span>
                     </span>
                     <span class="pl-3">
                        <input type="radio" name="account" value="shares"><span class="pl-2">Shares A/C</span>
                      </span>
                    </div>
                  </div>

                   <div class="col-lg-6 pt-2">
                        <span for="name" class="col-form-label pr-3 text-muted text-md-right"><i class="text-danger">*</i>
                        {{ __('Gender') }}</span>
                        <input type="radio" name="Gender" value="male"  checked="true" class="pl-4">
                        <span class="pl-1">{{ $gender }}</span>
                        <input type="hidden" name="accountApp" value="accountApp">
                        @error('gender')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                  </div>

                 <!--  <div class="row form-group">
                    <div class="col-lg-6">
                    <label class="control-label mb-1 text-muted">
                      <i class="text-danger">*</i> Write a statement why you feel you need this account</label>
                      <textarea id="mail-message" name="loanStmt" type="text" class="form-control" required></textarea>
                      </div>

                       <div class="col-lg-6">
                      <label for="file-input" class=" form-control-label text-muted"><i class="text-danger">*</i> Your CV</label>
                      <input type="file" id="file-input" name="file-input" class="form-control-file">
                    </div>
                    </div> -->

                    <div class="row form-group">
                    <div class="col-lg-6">
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



