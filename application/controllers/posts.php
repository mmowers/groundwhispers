<?php

class Posts_Controller extends Base_Controller{
	
	public $restful = true;	
	
	public function get_index(){
		Asset::add('posts', 'css/posts.css');
		Asset::container('footerbegin')->add('getlocation', 'js/getlocation.js');
		Asset::container('footerend')->add('sortby', 'js/sortby.js');
		$user = Auth::user(); //returns null if not logged in
		$postform = "";
		if(Auth::check()){
			Asset::container('footerend')->add('postlocation', 'js/postlocation.js');
			//is this shitty programming?- I am making a new view (postform.blade.php) so that I can run another ajax call on it.
			$postform = View::make('posts.postform')
									->with('user', $user);			
		}
		else{
			Asset::container('footerend')->add('fblogin', 'js/fblogin.js');
		}
		return View::make('posts.index')
		->with('title','Groundwhispers')
		->with('user', $user)
		->with('postform', $postform);
		//->with('posts', Post::order_by('created_at')->get());
	}
	public function get_getposts(){
    $user_lat = Input::get('latitude'); //security holes? do i need to sanitize or do preg_match or use Validator()?
    $user_lng = Input::get('longitude');
    $sort_by = Input::get('sortby');

    $results = Post::getPosts($user_lat, $user_lng, $sort_by);
    if($results=="error"){
    	return '<p class="text-error">Sorry! There was an error getting your location.</p>';    	
    }
    
		$user = Auth::user(); //returns null if not logged in
		return View::make('posts.getposts')
		->with('user_lat', $user_lat)
		->with('user_lng', $user_lng)
		->with('user', $user)
		->with('posts', $results);
	}
	public function post_create() {
		$validation = Post::validate(Input::all());

		if($validation->fails()){
			//return $validation->errors;	
		} else {
			Post::create(array(
				'posttext'=>Input::get('posttext'),
				'latitude'=>Input::get('latitude'),
				'longitude'=>Input::get('longitude'),
				'uid' => Input::get('uid')
			));
		}
		if (Input::get('ajax')=='yes'){
      $results = Post::getPosts(Input::get('latitude'), Input::get('longitude'), Input::get('sortby'));
      $user = Auth::user(); //returns null if not logged in
      return View::make('posts.getposts')
								->with('user_lat', Input::get('latitude'))
								->with('user_lng', Input::get('longitude'))
								->with('user', $user)
								->with('posts', $results);
    }
    
    return Redirect::to_route('posts');
	}	
	public function get_postform() {
    return View::make('posts.postform')
							->with('user', Auth::user());
	}
    
	public function post_vote() {
		$validation = Vote::validate(Input::all());

		if($validation->fails()){
			//return $validation->errors;	
		} else {
			$vote = Vote::where('uid', '=', Input::get('uid'))
									->where('pid', '=', Input::get('pid'))
									->first();
			if(is_null($vote)){
				Vote::create(array(
					'uid' => Input::get('uid'),
					'pid' => Input::get('pid'),
					'value' => 1
				));	
			}
			else{
        $vote->value = 1;
        $vote->save();
        $vote->touch();
        //Vote::where('uid', '=', Input::get('uid'))
				//		->where('pid', '=', Input::get('pid'))
				//		->update(array('value' => Input::get('value')));
			}
		}
		if (Input::get('ajax')=='yes'){
      $results = Post::getPosts(Input::get('latitude'), Input::get('longitude'), Input::get('sortby'));
      $user = Auth::user(); //returns null if not logged in
      return View::make('posts.getposts')
								->with('user_lat', Input::get('latitude'))
								->with('user_lng', Input::get('longitude'))
								->with('user', $user)
								->with('posts', $results);
    }
		return Redirect::to_route('posts');
	}	
}