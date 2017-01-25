@extends('layout')


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-2">
			<div class="row">
				<div class="sidebar-offcanvas">
					<div class="list-group">
						<a href="{{ URL::route('overview') }}" class="list-group-item">
							<i class="fa fa-tasks"></i> Overview
						</a>
						<a href="{{ URL::route('profile/personal') }}" class="list-group-item active">
							<i class="fa fa-user"></i> Profile
						</a>
						<a href="{{ URL::route('setting') }}" class="list-group-item">
							<i class="fa fa-cog"></i> Settings
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-10">
			<div class="col-md-12">
				<ul class="btn-bar">
					<li class="{{ Helper::setActive('profile/personal') }}">
						<a href="{{ URL::route('profile/personal') }}">Personal</a>
					</li>
					<li class="{{ Helper::setActive('profile/contact') }}">
						<a href="{{ URL::route('profile/contact') }}">Contact</a>
					</li>
					<li class="{{ Helper::setActive('profile/work') }}">
						<a href="{{ URL::route('profile/work') }}">Work</a>
					</li>
					<li class="{{ Helper::setActive('profile/transportation') }}">
						<a href="{{ URL::route('profile/transportation') }}">Transportation</a>
					</li>
					<li class="{{ Helper::setActive('profile/education') }}">
						<a href="{{ URL::route('profile/education') }}">Education</a>
					</li>
					<li class="{{ Helper::setActive('profile/criminal') }}">
						<a href="{{ URL::route('profile/criminal') }}">Criminal</a>
					</li>
				</ul>
			</div>
			<br class="top30"  />
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						@yield('panel-body')
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!-- /container -->
@endsection