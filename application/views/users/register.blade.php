@layout('layouts.default')

@section('content')
	{{ Form::open('postregister','POST', array('class'=>'well', 'id'=>'shout-form'))}}

		<legend> Register </legend>
		{{ Form::token() }}

		<!-- username field -->
		<p>{{ Form::label('email', 'Email') }}</p>
		<p>{{ Form::text('email', null, array('maxlength'=>'128', 'id'=>'email')) }}</p>

		<!-- submit button -->
		<p>
			{{ Form::button('Register', array('id'=>'registerbutton', 'type'=>'submit', 'class'=>'btn btn-primary')) }}
		</p>

	{{ Form::close() }}
@endsection