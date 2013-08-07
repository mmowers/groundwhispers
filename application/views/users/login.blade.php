@layout('layouts.default')

@section('content')
	{{ Form::open('postlogin','POST', array('class'=>'well', 'id'=>'shout-form'))}}

		<legend> Login </legend>
		{{ Form::token() }}
		<!-- check for login errors flash var -->
		@if (Session::has('login_errors'))
			<span class="text-error">Username or password incorrect.</span>
		@endif

		<!-- username field -->
		<p>{{ Form::label('email', 'Email') }}</p>
		<p>{{ Form::text('email', null, array('maxlength'=>'128', 'id'=>'email')) }}</p>

		<!-- password field -->
		<p>{{ Form::label('password', 'Password') }}</p>
		<p>{{ Form::password('password', array('maxlength'=>'64', 'id'=>'password')) }}</p>

		<!-- submit button -->
		<p>
			{{ Form::button('Login', array('id'=>'loginbutton', 'type'=>'submit', 'class'=>'btn btn-primary')) }}
		</p>

	{{ Form::close() }}
@endsection