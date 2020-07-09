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
			Your application for {{ $account }} account type has been denied
			<br>Reason:<strong> {{ $reason }}</strong>
		</p>

		<p>
		Otherwise thank you for having tried out on applying!
        </p>
		Thanks & Regards,<br>
		{{ __('Communication Department') }}<br>
		{{ $companyEmail }}<br>
		{{ config('app.name') }}<br>

	</div>

</div>

