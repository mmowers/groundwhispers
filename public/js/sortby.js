jQuery(document).ready(function($) {
	$('#sortby').change(function(){
		$(this).hide();
		$(this).after('<p id="sortby-loading">Resorting...</p>');
		//$('#vote-form').remove();
		lat = $('#user-lat').text();
		lng = $('#user-lng').text();
		sort = $(this).val();
		$.get(BASE+'/getposts', {
			latitude: lat,
			longitude: lng,
			sortby: sort
		}).done(function(data){
			$('#postlist').html(data);
			$('#sortby').show();
			$('#sortby-loading').remove();
		});
	});
});