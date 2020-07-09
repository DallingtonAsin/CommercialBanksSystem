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
			Your application for {{ $account }} account type has been approved successfully<br><br>
			<strong>Your default account details are:</strong> <br>
			Account Number: {{ $acc_no }}<br>
			Pin Number: {{ $pin }}<br>
			Username: {{$username }}<br>
			Password: {{ $defaultPassword }}
			
		</p>
		<p>
			<strong class="text-primary">You are advised to change these default login credentials 
			as soon as possible to ensure a secure and reliable account.</strong>
		</p>
		<p>
		Otherwise thank you so much for having thought about being our member
        </p>
		Thanks & Regards,<br>
		{{ __('Communication Department') }}<br>
		{{ $companyEmail }}<br>
		{{ config('app.name') }}<br>

	</div>

</div>

