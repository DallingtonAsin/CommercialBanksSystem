<p>
	<strong class="text-success">
		{{ $subject }} at {{ config('app.name') }}
	</strong>
</p>

<p>Hi user <br>
You are informed about a new event with 
the title {{ $title }}
  </p>

  <p>
  	About the event/Description<br>
  	{{ $description }}
  	<p>Will start on {{ $start_date }} at {{ $time }} and end on {{ $end_date }}</p>
  </p>

 <p>
 	This event has been recorded by {{ $registra }} and so for more details contact registra<br> of this event by calling on {{ $registraMobileNo }} or email at {{ $registra_email }}
 </p>

Thanks & Regards,<br>
{{ __('Communication Department') }}<br>
{{ config('app.name') }}<br>