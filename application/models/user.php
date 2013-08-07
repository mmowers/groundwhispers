<?php

class User extends Eloquent{
	public static $timestamps = true;
	public static $table = 'users'; //this line really isn't needed because we followed the plural and singular naming conventions.
	//commenting out $accessible here. Need this if we are going to do mass assignment, e.g. $user = new User(Input::all()); $user->save();
	//public static $accessible = array('username','email','password');

	public static $rules = array(
		'email'=>'required|max:128|email|unique:users',
		'password'=>'min:7|max:50',
		'username'=>'min:3|max:50|unique:users'
	);
	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	// a user can have many posts

	// lets use the has_many relationship for this
	public function posts()
	{
		return $this->has_many('Post');
	}
}