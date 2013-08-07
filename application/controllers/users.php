<?php

class Users_Controller extends Base_Controller{
	
	public $restful = true;	
	
	public function get_register(){
		return View::make('users.register')
		->with('title','Register');
	}
	public function post_postregister() {
		$validation = User::validate(Input::all());
		if($validation->fails()){
			//return $validation->errors;	
		} else {
			$email = Input::get('email');
			$activation = Str::random(64);
			//$activation = md5(uniqid(rand(), true)).md5(uniqid(rand(), true));
			$user = User::create(array(
				'email'=>$email,
				'confirmcode'=>$activation,
			));
			//send email
			if($user){
        $message = " To activate your account, please click on this link:\n\n";
        $message .= URL::base() . '/confirm?id='.$user->id.'&email=' . urlencode($email) . "&key=$activation";
        //$message .= URL::base() . '/confirm?id='.$user->attributes['id'].'&email=' . urlencode($email) . "&key=$activation";
        mail($email, 'Registration Confirmation', $message, 'From: ismaakeel@gmail.com');        
      }
		}

		return Redirect::to_route('posts');
	}
	public function get_confirm() {
		$id = Input::get('id');
    $email = Input::get('email');
		$confirmcode = Input::get('key');
    $user = User::where('id', '=', $id)
                ->where('email', '=', $email)
                ->where('confirmcode', '=', $confirmcode)
                ->first();
    if($user){
      Auth::login($id);
    }
    return Redirect::to_route('posts');
    
	}	
	public function get_login() {
		return View::make('users.login')
		->with('title','Login');
	}
	public function post_postlogin() {
		// get the username and password from the POST
		// data using the Input class
		$email = Input::get('email');
		$password = Input::get('password');
    $credentials = array('username' => $email, 'password' => $password);
		// call Auth::attempt() on the username and password
		// to try to login, the session will be created
		// automatically on success
		if ( Auth::attempt($credentials) )
		{
			// it worked, redirect to the admin route
			return Redirect::to('/');
		}
		else
		{
			// login failed, show the form again and
			// use the login_errors data to show that
			// an error occured
			return Redirect::to('login')
				->with('login_errors', true);
		}		
	}
	public function get_logout(){
		// call the logout method to destroy
		// the login session
		Auth::logout();

		// redirect back to the home page
		return Redirect::to('/');			
	}	

	public function get_oauth(){
		if(Input::get('error')){
		    return Redirect::to('/');
		}
		$facebook = IoC::resolve('facebook-sdk');
		$uid = $facebook->getUser();
		$fbuser = $facebook->api('/me');
		$user = User::where('oauth_uid', '=', $uid)->or_where('email', '=', $fbuser['email'])->first();
		if(is_null($user)){
			$user = User::create(array(
				'email'=>$fbuser['email'],
				'oauth_uid'=>$uid,
			));
		}
    Auth::login($user->id);
    //Auth::login($user->attributes['id']);
    return Redirect::to('/');      
	}	
  
}