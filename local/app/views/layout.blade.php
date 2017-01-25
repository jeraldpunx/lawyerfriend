<!DOCTYPE html>
<html>
<head>
	<title>{{$title}} | Lawyer Friend</title>
	<link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon" />
	<meta name="robots" content="noindex">
	{{-- <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/simplex/bootstrap.min.css" rel="stylesheet"/> --}}
	{{-- <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/paper/bootstrap.min.css" rel="stylesheet"/> --}}
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet"/>
	<link href="{{ asset('assets/css/jquery-ui-1.9.2.custom.css') }}" rel="stylesheet" type="text/css">
	<link href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.0.0/css/responsive.bootstrap.min.css">
	<link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/css/selectize.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/css/pace.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/css/notifIt.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/css/fileinput.min.css') }}" rel="stylesheet"/>
	@yield('style')
	<link href="{{ asset('assets/css/myStyle.css') }}" rel="stylesheet"/>
</head>
<body>
	<header>
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="{{ URL::route('home') }}">
			            <img src="{{ asset('assets/img/logo.png') }}"/>
			            Lawyer Friend
			        </a>
				</div>
				<div class="navbar-collapse collapse" id="navbar">
					<ul class="nav navbar-nav navbar-left">
						<li class="{{ Helper::setActive('home') }}"><a href="{{ URL::route('home') }}">HOME</a></li>
						<li class="{{ Helper::setActive('blogs') }}"><a href="{{ URL::route('blogs') }}">BLOG</a></li>
						<li class="{{ Helper::setActive('about') }}"><a href="{{ URL::route('about') }}">ABOUT</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">

						@if(Auth::check())
							@if(Auth::user()->role == 'customer')
								<li><p class="navbar-btn"><a href="{{ URL::route('find-a-lawyer') }}" class="btn btn-success"><i class="fa fa-search"></i> Find A Lawyer</a></p></li>
							@endif
							@if(Auth::user()->role == 'admin')
								<li class="{{ Helper::setActive('overview') }}"><a href="{{ URL::route('overview') }}"><i class="fa fa-list"></i> Admin</a></li>
								<li class="{{ Helper::setActive('setting') }}"><a href="{{ URL::route('setting') }}"><i class="fa fa-cog"></i> Settings</a></li>
							@else
								<li class="{{ Helper::setActive('overview') }}"><a href="{{ URL::route('overview') }}"><i class="fa fa-user"></i> Account</a></li>
							@endif
							<li><a href="{{ URL::route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
						@else
							<li class="{{ Helper::setActive('register') }}"><p class="navbar-btn"><a href="{{ URL::route('register') }}" class="btn btn-success">Find A Lawyer</a></p></li>
							<li class="{{ Helper::setActive('login') }}"><a href="{{ URL::route('login') }}">Login</a></li>
						@endif
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>
	</header>


	@yield('content')

	<footer id="footer-widgets" style="margin-top: 24px;">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="col-sm-4">
						<div class="widget">
							<h6 class="text-uppercase">About Lawyer Friend</h6>
							<ul class="list-unstyled">
								<li><a href="{{ URL::route('home') }}">Home</a></li>
								<li><a href="{{ URL::route('about') }}">How It Works</a></li>
								<li><a href="{{ URL::route('termsandconditions') }}">Terms & Conditions</a></li>
							</ul>
						</div>
					</div>

					<div class="col-md-5">
						<div class="widget">
							<h6 class="text-uppercase">Join Lawyer Friend</h6>
							<a class="btn btn-success" href="{{ URL::route('register') }}">Register</a>
						</div>
					</div>

					<div class="col-sm-2">
						<div class="widget">
							<h6 class="text-uppercase">Location:</h6>
							<p>721 Beville Rd.</p>
							<p>Daytona Beach, FL 32119</p>
							<h6 class="text-uppercase">Contact Us:</h6>
							<p><a href="mailto:alerts.lawyerfriend@gmail.com?Subject=Lawyer%20Friend%20Question">alerts.lawyerfriend@gmail.com</a></p>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="text-center">
						<p class="footer-company-name">Lawyer Friend &copy; 2015</p>
					</div>
				</div>
			</div>
		</div>
	</footer>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.0.0/js/dataTables.responsive.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.0.0/js/responsive.bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
	<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
	<script src="http://fgnass.github.io/spin.js/spin.min.js"></script>
	<script src="{{ asset('assets/js/pace.min.js') }}"></script>
	<script src="{{ asset('assets/js/notifIt.min.js') }}"></script>
	<script src="{{ asset('assets/js/selectize.min.js') }}"></script>
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	<script src="{{ asset('assets/js/fileinput.min.js') }}"></script>
	<script type="text/javascript">
		var spinner_option = { lines: 7 // The number of lines to draw
								, length: 0 // The length of each line
								, width: 18 // The line thickness
								, radius: 36 // The radius of the inner circle
								, scale: 0.75 // Scales overall size of the spinner
								, corners: 1 // Corner roundness (0..1)
								, color: '#000' // #rgb or #rrggbb or array of colors
								, opacity: 0.3 // Opacity of the lines
								, rotate: 0 // The rotation offset
								, direction: 1 // 1: clockwise, -1: counterclockwise
								, speed: 1.2 // Rounds per second
								, trail: 100 // Afterglow percentage
								, fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
								, zIndex: 2e9 // The z-index (defaults to 2000000000)
								, className: 'spinner' // The CSS class to assign to the spinner
								, top: '50%' // Top position relative to parent
								, left: '50%' // Left position relative to parent
								, shadow: false // Whether to render a shadow
								, hwaccel: true // Whether to use hardware acceleration
								, position: 'absolute' // Element positioning
							};

		function displayNotifit( msg , errorStatus ) {
			notif({
				msg: msg,
				position: "right",
				color: "#fff",
				bgcolor: errorStatus ? "#4caf50" : "#e51c23",
				multiline: true,
				autohide: true
			});
		}

		$('.selectize').selectize({
		    persist: false,
		    createOnBlur: true,
		});

		$('.date').datetimepicker({
	        format: 'MM/DD/YYYY'
	    });
	</script>
	@yield('script')
</body>
</html>