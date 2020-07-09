 <!-- Scripts -->
<script src="{{ asset('vendors/js/jquery-3.3.1.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!--  Styles -->
<link href="{{ asset('css/css.css') }}" rel="stylesheet">
<link href="{{ asset('css/nunito.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

<div class="card">
	<div class="card-header bg-success text-white">
		<strong>{{ $subject }} at {{ config('app.name') }}</strong>
	</div>
	<div class="card-body">
		<p>Hi {{ $applicant }} <br>
			We have received a loan request application of amount
			{{ $amount }}.<br>You will receive an email about loan
			approval or denial in a few days from now.<br>Thank you!
			
		</p>
		Thanks & Regards,<br>
		{{ __('Communication Department') }}<br>
		{{ $companyEmail }}<br>
		{{ config('app.name') }}<br>

	</div>

</div>
