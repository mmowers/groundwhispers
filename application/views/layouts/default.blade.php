<!DOCTYPE html>
<html>
<head>
	<title>{{ $title }}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
	<script type="text/javascript">var BASE = "{{URL::base()}}";</script>
	<!--<link href="css/deletethis.css" rel="stylesheet">-->
	{{ Asset::styles()}}
	{{ Asset::container('headerend')->scripts() }}	

</head>
<body>
	<div id="body-outer-div">
		@if(Session::has('message'))
			<p style="color: green;"> {{ Session::get('message') }}</p>
		@endif

		@yield('content')

		<!-- Placed at the end of the document so the pages load faster -->
		{{ Asset::container('footerbegin')->scripts() }}
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
		<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
		{{ Asset::container('footerend')->scripts() }}
	</div>
</body>
</html>