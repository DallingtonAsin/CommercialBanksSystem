@extends('layouts.sidebar-header')

@section('content')
<div class="container">
   <div class="row justify-content-center">
       <div class="col-md-4">
           <div class="card">
               <div class="card-header">{{ __('Paypal Card Payment') }}</div>


               <div class="card-body">
                   <form method="POST" action="{{ route('paypal-payment-form-submit') }}">
                       @csrf
                       <div class="form-group row">
                           <div class="col-md-12">
                               <input id="card_no" type="text" class="form-control @error('card_no') is-invalid @enderror" name="card_no" value="{{ old('card_no') }}" required autocomplete="card_no" placeholder="Card No." autofocus>
                               @error('card_no')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                               @enderror
                           </div>
                       </div>
                       <div class="form-group row">
                           <div class="col-md-6">
                               <input id="exp_month" type="text" class="form-control @error('exp_month') is-invalid @enderror" name="exp_month" value="{{ old('exp_month') }}" required autocomplete="exp_month" placeholder="Exp. Month (Eg. 02)" autofocus>
                               @error('exp_month')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                               @enderror
                           </div>
                           <div class="col-md-6">
                               <input id="exp_year" type="text" class="form-control @error('exp_year') is-invalid @enderror" name="exp_year" value="{{ old('exp_year') }}" required autocomplete="exp_year" placeholder="Exp. Year (Eg. 2020)" autofocus>
                               @error('exp_year')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                    </span>
                               @enderror
                           </div>
                       </div>
                       <div class="form-group row">
                           <div class="col-md-12">
                               <input id="cvv" type="password" class="form-control @error('cvv') is-invalid @enderror" name="cvv" required autocomplete="current-password" placeholder="CVV">
                               @error('cvv')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                               @enderror
                           </div>
                        </div>


                       <div class="form-group row mb-0">
                           <div class="col-md-12">
                               <button type="submit" class="btn btn-primary btn-block">
                                   {{ __('PAY NOW') }}
                               </button>
                           </div>
                       </div>
                   </form>
               </div>
           </div>
       </div>
   </div>
</div>
@endsection