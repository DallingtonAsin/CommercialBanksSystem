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
		<p>Hi <span class="text-primary">{{ $applicant }}</span> <br>
			Your loan request for amount worth <strong>{{ $amount }}</strong> has been rejected<br>
			We are sorry about that!
			
		</p>
		Thanks & Regards,<br>
		{{ __('Communication Department') }}<br>
		{{ $companyEmail }}<br>
		{{ config('app.name') }}<br>

	</div>

</div>

