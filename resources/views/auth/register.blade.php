@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card register-card">
                <div class="card-header green-shade">
                    <strong class="text-white">{{ __('Registration') }}</strong>
                </div>

                <div class="card-body">
                   
                          <center>
                            <img src="{{ asset('images/brand.png') }}" class="co-icon-1"><br>
                             <label class="">{{ config('app.name') }}</label>
                          </center>

                     
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                      <div class="form-group">
                        <label for="name" class="col-form-label text-md-right">{{ __('Name') }}</label>

                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Enter name" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                     
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-form-label text-md-right">{{ __('Email') }}</label>

                        <div class="">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" name="password" required autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="">
                            <button type="submit" class="btn btn-block green-shade text-white">
                                {{ __('Register') }}
                            </button>
                           
                        </div>
                         <div class="">
                            <a class="text-decoration-none" href="{{ route('login') }}">
                                {{ __('Already have Account? Login') }}
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
