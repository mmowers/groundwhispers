{{ Form::open('vote','POST', array('id'=>'vote-form')) }}
{{ Form::token() }}
@if(Auth::check())
	{{ Form::hidden('uid', $user->id, array('id'=>'current-uid')) }}
@endif
{{ Form::hidden('value', 1) }}
@foreach($posts as $post)
	<div class="post">
		<!-- The following line does not work so I commented it. I'm not sure how to use the laravel form api if the button text 
	        includes html tags-->
		{{-- Form::button('<i class="icon-arrow-up icon-white"></i>', array('type'=>'submit', 'class'=>'post-up-vote btn btn-info')) --}}
		<div class="vote-button-div">
			<button type="submit" name="pid" value="{{ e($post->id) }}" class="post-up-vote btn btn-info"><i class="icon-arrow-up icon-white"></i></button>
			<br/>
			<div class="post-votes"><small>{{ (e($post->votecount) == 1)?e($post->votecount).' up':e($post->votecount).' ups'  }}</small></div>
		</div>
		<div class="post-outer">
			<!--<div class="post-username">{{ e($post->username) }}</div>-->
			<div class="post-text">{{ e($post->posttext) }}</div>
			<div class="post-distance-time">
					<span class="post-distance"><small>{{ e(round($post->distance)) }} feet away, </small></span>
					<span class="post-time"><small>{{ e($post->created_at) }}</small></span>
			</div>
		</div>
		{{-- e($post->latitude) }}, {{ e($post->longitude) --}}
	</div>
@endforeach
{{ Form::close() }}
<p id="user-lat">{{e($user_lat)}}</p>
<p id="user-lng">{{e($user_lng)}}</p>
<!--<p id="user-lat">{{Input::get('latitude')}}</p>-->
<!--<p id="user-lng">{{Input::get('longitude')}}</p>-->
