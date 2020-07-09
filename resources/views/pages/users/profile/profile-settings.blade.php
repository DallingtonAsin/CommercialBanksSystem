@extends('layouts.sidebar-header')


@section('content')

<div class="card nunito-font">

 <div class="card-title">
   <h6 class="col-lg-10 text-success pt-4">
    <i class="fa fa-gear"></i> 
    <span class="text-dark">
      <strong>Settings / Edit your profile</strong>
    </span>
  </h6>
</div>

<div class="card-body">
  <div class="panel panel-default">

    <div class="panel-body">

      <form class="form" method="post" action="{{ route('profile.update', Auth::user()->id) }}"
        enctype='multipart/form-data'>
        @method('PATCH')
        @csrf

        <div class="form-group">
          <span class="text-muted">Username</span>
          <input type="text"  class="form-control" spellcheck="off"
          name="Username" value="{{Auth::user()->username}}" autocomplete="off"
          >
        </div>

        <div class="form-group">
         <span class="text-muted">Email</span>
         <input type="text" class="form-control" name="Email" value="{{Auth::user()->email}}" spellcheck="off" 
         autocomplete="off"  required>
       </div>

       <div class="form-group">
        <span class="text-muted">Telephone No.</span>
        <input type="text" class="form-control" name="Contact" value="{{Auth::user()->tel_no }}" required
        autocomplete="off"
        >
      </div>

      <div class="form-group">
        <span class="text-muted">Address</span>
        <input type="text" class="form-control" name="Address" value="{{Auth::user()->address}}"
        autocomplete="off">
      </div required>

    
    <div class="form-group">
      <span class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Need to change your password? click here
        </button>
      </span>
    </div>

    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">

      <div class="form-group">
        <span class="input-group">
         <span class="input-group-addon text-muted">
          Old password 
        </span>
        <input type="password" id="oldpassword" name="OldPassword" class="form-control" 
        placeholder = "Enter your old password">
        <span class="input-group-addon" id="showpassword1" required>
          <i class="fa fa-eye"></i>
        </span>
      </div>


      <div class="form-group">
        <span class="input-group">
         <span class="input-group-addon text-muted">
          <i class="glyphicon glyphicon-eye"></i>New password 
        </span>
        <input type="password" id="newpassword" name="NewPassword" class="form-control"
        placeholder="Enter your new password">
        <span class="input-group-addon" id="showpassword2" required>
          <i class="fa fa-eye"></i>
        </span>
      </div>

      <div class="form-group">
        <span class="input-group">
         <span class="input-group-addon text-muted">
          <i class="glyphicon glyphicon-eye"></i>Confirm password
        </span>
        <input type="password" id="confirmpassword" name="PasswordConfirm" class="form-control" 
        placeholder="Confirm your password">
        <span class="input-group-addon" id="showpassword3" required>
          <i class="fa fa-eye"></i>
        </span>
      </div>

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
       <input type="submit" class="btn btn-success col-lg-2" value="Update Profile">
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

  });
</script>
@endsection

