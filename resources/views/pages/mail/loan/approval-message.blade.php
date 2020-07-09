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
			Your loan request for amount worth <strong>{{ $amount }}</strong> has been approved successfully<br>
			So you are informed that the interest amount is and 
			the due date for the loan is {{ $due_date }}
			
		</p>
		<p>
			You are advised to work hard to always pay the loan interest in time to avoid unnecessary charges.<br> You can use this system to pay your loans.
		</p>
		<p>
		Otherwise we wish you well over the use of this loan money
        </p>
		Thanks & Regards,<br>
		{{ __('Communication Department') }}<br>
		{{ $companyEmail }}<br>
		{{ config('app.name') }}<br>

	</div>

</div>

