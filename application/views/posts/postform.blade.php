{{ Form::open('posts/create','POST', array('id'=>'shout-form'))}}
	{{ Form::hidden('uid', $user->id) }}
	{{ Form::token() }}
	{{ Form::textarea('posttext', Input::old('posttext'), array('placeholder'=>'Say something helpful about your current location! (limit 300 characters)','maxlength'=>'300','rows'=>'2','cols'=>'60', 'id'=>'posttext')) }}
	{{ Form::hidden('latitude', '', array('id'=>'latitude')) }}
	{{ Form::hidden('longitude', '', array('id'=>'longitude')) }}
	<div id='postbutton-outer'>
		{{ Form::button('Post Shout!', array('id'=>'postbutton', 'type'=>'submit', 'class'=>'btn btn-primary')) }}
		{{ Form::button('Cancel', array('id'=>'postbuttoncancel', 'class'=>'btn btn-primary')) }}
	</div>
{{ Form::close() }}