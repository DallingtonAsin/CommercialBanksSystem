@extends('layouts.app')

@section('content')


<div class="container">
  <div class="row justify-content-center">
    <div class="login-col
    col-md-5 mt-3 ">

     <h5 class="custom-family text-center text-white">
     {{ config('app.name') }}</h5>
     <h6 class="custom-family text-center text-white text-muted">
      <small>&copy; {{ config('app.name') }} {{ date('Y') }}</small>
    </h6>


    <div class="header green-shade nunito-font text-center pt-2">
      <center>
        <img src="{{ asset('images/brand.png') }}" class="d-dark co-icon-center pd-5 br-50">
      </center>
      <h5 class="col-form-label text-md-center">{{ __('Sign in to start your session') }}</h5>
    </div>

    <div class="bg-white pb-0 pt-2 px-5">
      <form method="POST" action="{{ route('authenticate') }}">
        @csrf

       <div class="form-group pt-4">
        <span class="login_span nunito-font">Username</span>
        <input id="login" type="text"
        class="form-control nunito-font
        @error('password') is-invalid @enderror
        " name="login" value="{{ old('username') ?: old('email') }}"
        placeholder="Enter your email or username"  required autocomplete="email" autofocus spellcheck="false">
      </div>

      <div class="form-group ">
        <span class="login_span nunito-font">Password</span>
        <input id="password" type="password" class="form-control  @error('password') is-invalid @enderror nunito-font" name="password" placeholder="Enter your password"  required autocomplete="current-password">
        @error('password')
        <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
        </span>
        @enderror
      </div>


      <div class="form-group">
        <div class="form-check">
          <input class="form-check-input" type="checkbox"
          name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

          <label class="form-check-label" for="remember">
            {{ __('Remember Me') }}
          </label>
        </div>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-sm btn-block green-shade text-dark">
         <strong> {{ __('Sign in') }}</strong>
        </button>
      </div>

      <div class="form-group px-0 py-0">
          @if($errors->any())
          <span class="login-error nunito-font">
            <span>{{$errors->first()}}</span>
          </span>
          @endif

          @if(session()->get('loginErr'))
          <span class="login-error nunito-font">
           {{ session()->get('loginErr') }}
         </span>
         @endif

            @if(session()->get('sessionExpiredMessage'))
          <span class="text-danger nunito-font">
           {{ session()->get('sessionExpiredMessage') }}
         </span>
         @endif

       </div>

      <div class="form-group">
        <div>
          @if (Route::has('password.request'))
          <a class="nunito-font text-decoration-none" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
          </a>
          @endif
        </div>

        <div>
          @if (Route::has('register'))
          <a class="nunito-font text-decoration-none" href="{{ route('membership.app') }}">{{ __("Don't have Member Account? Register") }}</a>
          @endif
        </div>
      </div>

      <div class="form-group row">


        <div class="col-lg-4">
          <h6 class="nunito-font text-muted">in Partnership with CST</h6>
        </div>

        <div class="col-lg-5">
          <h6>
           <img src="{{ asset('images/partner.png') }}" class="co-icon">
         </h6>
       </div>

       <div class="col-lg-4">
        <h6 class="text-muted"></h6>
      </div>

    </div>


  </form>
</div>
<!-- <h6 class="custom-family text-center text-white text-muted">
  <small>
    Copyright &copy; Code Solution Tech:: Dallington companies {{ date('Y')}}
  </small>
</h6> -->


</div>
</div>
</div>

@endsection
