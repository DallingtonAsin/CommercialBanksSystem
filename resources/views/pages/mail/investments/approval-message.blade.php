 <!-- Scripts -->
<script src="{{ asset('vendors/js/jquery-3.3.1.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!--  Styles -->
<link href="{{ asset('css/css.css') }}" rel="stylesheet">
<link href="{{ asset('css/nunito.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

<div class="card">
	<div class="card-header bg-success text-white">
		{{ $subject }} at {{ config('app.name') }}
	</div>
	<div class="card-body">
		<p>Hi <br>
			Running Investment in asset {{ $asset }}
			whose capital was {{ $capital }} has been terminated
			<br>Details: {{ $details }}
			<br>Reason: 
		</p>
		
		Thanks & Regards,<br>
		{{ __('Communication Department') }}<br>
		{{ $companyEmail }}
		{{ config('app.name') }}<br>

	</div>

</div>

