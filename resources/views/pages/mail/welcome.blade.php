<p>
	<strong class="text-success">
		{{ $subject }} at {{ config('app.name') }}
	</strong>
</p>

<p>Hi {{ $username }} <br>
You have been registered as a {{ $user_position }} today at {{ $created_at }}  </p>

<p>You will be able to login into the system using the following details<br>
<strong>Username</strong>: <span>{{ $username }}</span><br>
<strong>Password</strong>: <span>{{ $password }}</span><br>
</p>

<br>
<a href="{{ url('/') }}">Click this link to login and start using the system</a>
<br>


Thanks & Regards,<br>
{{ $registra }}<br>
{{ $registraPosition }}, {{ config('app.name') }}<br>
{{ $registraEmail }}