jQuery(document).ready(function($) {
	$('#fblink').click(function(){
		$(this).hide();
		$(this).after('<p id="fblink-loading">Logging in...</p>');
	});
	//I had to use on because these elements are dynamic
	//holy shit, when I include the following code, it works, but then if i comment it out, the redirect still works! I
	//can't fucking get rid of it unless I return false from the following function without redirecting to facebook!! WTF??
	//Its like facebook knows that these buttons are supposed to lead to the login, and it makes the link itself!AHHH!!!
	$("#postlist").on("click", "button.post-up-vote", function(event){
		$('#fblink').hide();
		$('#fblink').after('<p id="fblink-loading">Logging in...</p>');
		//window.location = 'http://www.google.com';
		window.location = $('#fblink').attr('href');
		return false;
	});
});