<?php

class Vote extends Eloquent{
	public static $timestamps = true;
	public static $table = 'votes'; //this line really isn't needed because we followed the plural and singular naming conventions.
	//I tried to include an integer requirement for uid, pid, and vbut this did not work
	public static $rules = array(
		'uid'=>'required|integer',
		'pid'=>'required|integer',
		'value'=>'required|integer',
	);
	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	//belongs to etc. needs to be added!
}