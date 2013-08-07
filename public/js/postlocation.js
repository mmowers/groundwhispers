jQuery(document).ready(function($) {
	//perhaps the following vote ajax stuff should be in another file?
	//I had to use on because these elements are dynamic
	$("#postlist").on("click", "button.post-up-vote", function(event){
		if($('#curlatitude').val() && $('#curlongitude').val() && $('#sortby').val()){
			var querystring ='latitude='+$('#curlatitude').val()+ 
		                 '&longitude='+$('#curlongitude').val()+
		                    '&sortby='+$('#sortby').val()+
		                       '&pid='+$(this).val()+
		                       '&uid='+$('#current-uid').val()+
		                       '&csrf_token='+$('#vote-form').find('input[name="csrf_token"]').first().val()+
		                       '&value='+$('#vote-form').find('input[name="value"]').first().val()+
		                       '&ajax=yes';
			$.post(BASE+'/vote', querystring)
			.done(function(data){
				$('#postlist').html(data);
			});		
			return false;           			
		}
	});

	$('#showpostbutton').click(function(){
		//if($('#fblink').length){
		//	window.location.href = $('#fblink').attr('href');
		//	return false;
		//}
		$('#header').hide();
		$('#shout-form').show();
		$('#posttext').focus();
		return false;
	});
	$('#postbuttoncancel').click(function(){
		$('#shout-form').hide();
		$('#header').show();
		return false;
	});
	var firstWatch = true;
	var watchID = false;
	$('#postbutton').click(function(){
	  firstWatch = true;
		//if($('#posttext').val()){
		//if the string isn't just all whitespace
		if(/\S/.test($('#posttext').val())){
			$('#shout-form').hide();		
			$('#header').show();
			//perhaps we don't need to get the location again, or maybe we should ask if the user wants to update...
			if( navigator.geolocation ){
			  var timeoutVal = 20 * 1000;
			  // Call getCurrentPosition with success and failure callbacks
			  navigator.geolocation.getCurrentPosition( success, fail, { enableHighAccuracy: true, timeout: timeoutVal, maximumAge: 0 });
			  //watchID = navigator.geolocation.watchPosition( success, fail, { enableHighAccuracy: true, timeout: timeoutVal, maximumAge: 0 });
			}
			else{
			  alert("Sorry, geolocation is not supported by this browser :(");
			} 	
		}
		else{
			$('#posttext').val('');
			$('#posttext').attr('placeholder','You must enter a message before submitting');
		}
		return false;           
	});

	//success and fail functions for navigator.geolocation.getCurrentPosition().
	function success(position){
	  //using watchposition for 1.5 seconds after first reading (with setTimeout()) in case more accurate readings come in.
	  //if(firstWatch){
	    //firstWatch = false;
	    //setTimeout(function() {
	      //navigator.geolocation.clearWatch(watchID);
		    //if we get the position, and accuracy is good enough, then submit form
		    var accuracy_feet = position.coords.accuracy*3.28084;
		    //uncomment this if statement and the else statement below to make posts more accurate.
		    //if(accuracy_feet<250){
		    var roundnum = 1000000; //rounding to 6 decimal places
		    var lat = Math.round(position.coords.latitude*roundnum)/roundnum;
		    var lng = Math.round(position.coords.longitude*roundnum)/roundnum;
		    //adding form elements for latitude and longitude.  Not sure if there is a better way...
		    //$('#shout-form').append('<input type="number" name="latitude" id="latitude" style="display:none;">');
		    //$('#shout-form').append('<input type="number" name="longitude" id="longitude" style="display:none;">');
		    $('#latitude').val(lat);
		    $('#longitude').val(lng);
		
		    //$('#shout-form').submit(); //uncomment this line and comment ajax stuff to do regular submission.
		    //ajax submit
		    //$.post(BASE+'/posts/create', $("#shout-form").serialize())
		    $.post(BASE+'/posts/create', $("#shout-form").serialize()+'&sortby='+$('#sortby').val()+'&ajax=yes')
		    .done(function(data){
			    //Maybe just add this to the top of the list by copying <div class="post">...</div> from getposts.blade.php and prepending to #postlist.
			    //Another option is to redirect to getposts from posts/create given an ajax=yes input, but it seems like maybe getposts and posts/create should call yet another private function...
			    $('#postlist').html(data);
			    $("#shout-form")[0].reset(); //is this working? the form was not being reset, and when i tried to submit again it didn't post again. perhaps the token is f'd on second submission?
		    });
		    /*The following wasn't actually needed.  It is for retrieving another post form with a new token.
		    $.get(BASE+'/postform')
		    .done(function(data){
			    $("#shout-form")[0].reset(); //this is not working. the form is not being reset, and when i try to submit again it doesn't post again. perhaps the token is f'd on second submission?
			    $('#postform-outer').html(data);
		    });
		    */


		    //}
		    //The following can be used for getCurrentPosition but not watchPosition, cuz this function is called many times
		    //else{
		    //	var inaccurate_msg_start = "Sorry, the estimate of your location is too inaccurate, and this post would be placed up to ";
		    //	var inaccurated_msg_end = " feet away from you. A mobile device with functioning GPS services works best. Please try again."
		    //	var accuracy_feet_rounded = Number(accuracy_feet.toPrecision(2));
		    //	alert(inaccurate_msg_start+accuracy_feet_rounded+inaccurated_msg_end);
		    //}

		    /*removing ajax posting for now
		    var inputtext = $('textarea[name="posttext"]').val();
		    $.post(BASE+'/posts/create', {
			    posttext: inputtext,
			    latitude: lat,
			    longitude: lng
		    }, function(data) {
			    $('#postlist').after(data);
		    });
            */
      //}, 1500);
    //}
	}
	function fail(error){
	  var errors = { 
      0: 'Sorry, there was a problem getting your location. Please try again.',
      1: 'Sorry, your location was not retrieved. Please allow the location request.',
      2: 'Sorry, your current position is unavailable. You may need to enable location services on your browser.',
      3: 'Sorry, the location request timed out. Please try again.'
	  };
	  alert(errors[error.code]);
	}

});


//(IFNULL(ACOS($coslat*COS(RADIANS({$tbl_alias}latitude))*($coslong*COS(RADIANS({$tbl_alias}longitude)) + $sinlong*SIN(RADIANS({$tbl_alias}longitude))) + $sinlat*SIN(RADIANS({$tbl_alias}latitude))), 0.00000)*$radius)