@extends('layout')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-2">
			<div class="row">
				<div class="sidebar-offcanvas">
					<div class="list-group">
						<a href="{{ URL::route('overview') }}" class="list-group-item {{ Helper::setActive('overview') }}">
							<i class="fa fa-tasks"></i> Overview
						</a>
						<a href="{{ URL::route('profile/personal') }}" class="list-group-item {{ Helper::setActive('profile/personal') }}">
							<i class="fa fa-user"></i> Profile
						</a>
						<a href="{{ URL::route('setting') }}" class="list-group-item active">
							<i class="fa fa-cog"></i> Settings
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-10">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						@if (count($errors) > 0)
							<div class="alert alert-danger">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<strong>Whoops!</strong> There were some problems with your input.<br><br>
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						@if (Session::has('flash.message'))
							<div class="alert alert-{{Session::get('flash.type')}}">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								{{ Session::get('flash.message') }}
							</div>
						@endif
						<h3>Settings</h3>
						<form role="form" method="POST" action="{{ URL::route('setting') }}">
							<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-8">
									<div class="form-group">
										<label>Current Password</label>
										{{ Form::password('current_password', ['class'=>'form-control', 'required'=>'']) }}
									</div>

									<div class="form-group">
										<label>New Password</label>
										{{ Form::password('password', ['class'=>'form-control', 'required'=>'']) }}
									</div>

									<div class="form-group">
										<label>Confirm Password</label>
										{{ Form::password('password_confirmation', ['class'=>'form-control', 'required'=>'']) }}
									</div>
								</div>
							</div>

							<div class="pull-right">
								<input type="submit" class="btn btn-success btn-lg" value="Save" />
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
@endsection