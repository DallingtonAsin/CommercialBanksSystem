 <!-- Scripts -->
<script src="{{ asset('vendors/js/jquery-3.3.1.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!--  Styles -->
<link href="{{ asset('css/css.css') }}" rel="stylesheet">
<link href="{{ asset('css/nunito.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

<div class="card">
	<div class="card-header bg-success text-white">
		<strong>{{ $data['subject'] }} at {{ config('app.name') }}</strong>
	</div>
	<div class="card-body">
		<p>Hello {{ $data['names']}} <br>
			You have {{ $data['action'] }}.<br>
			Your new account balance is shs.<strong>{{ number_format($data['balance']) }}</strong>
			
		</p>
	
		<p>
		Thank you for transacting!
        </p>
		Thanks & Regards,<br>
		{{ __('Communication Department') }}<br>
		{{ $data['companyEmail'] }}<br>
		{{ config('app.name') }}<br>

	</div>

</div>

