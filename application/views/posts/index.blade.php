@layout('layouts.default')

@section('content')

	<div id="header">
		<div id="control-buttons">
			@if ( Auth::guest() )
			  {{ HTML::link(Helpers::fbLogin(), 'Login to Post', array('id'=>'fblink', 'class'=>'btn btn-large btn-primary')) }}
				{{-- HTML::link('login', 'Login') --}}
				{{-- HTML::link('register', 'Register') --}}
			@else
				<button id="showpostbutton" class="btn btn-large btn-primary"> Post! </button>
				{{ HTML::link('logout', 'Logout', array('class'=>'btn btn-large btn-primary')) }}
			@endif
		</div>
		<!--<h5 id="sortby-label">Sort By: </h5>-->
		<!--curlatitude and curlongitude are needed for ajax voting to work-->
		{{ Form::hidden('curlatitude', '', array('id'=>'curlatitude')) }}
		{{ Form::hidden('curlongitude', '', array('id'=>'curlongitude')) }}
		<div id="sortby-outer">
			<select id="sortby">
				<option value="time" selected="selected">Time</option>
				<option value="distance">Distance</option>
				<option value="votes">Up-Votes</option>
			</select>
		</div>
	</div>
	<div id="postform-outer">
		{{ $postform }}
	</div>
  <div id="postlist"><p>Allow location services to see nearby shouts!</p></div>
@endsection