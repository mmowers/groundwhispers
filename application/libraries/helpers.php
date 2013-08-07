<?php

class Helpers {

	public static function fbLogin(){
  	$facebook = IoC::resolve('facebook-sdk');
    $params = array(
            'redirect_uri' => 'http://laravel.dev/fblogin',
//	    			'display' => 'popup',
	    			'scope' => 'email'
		);
		
		return $facebook->getLoginUrl($params);
  }
}