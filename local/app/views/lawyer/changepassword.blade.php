@extends('layout')


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<div class="row">
				<div class="sidebar-offcanvas">
					<div class="list-group list-group-root well">
						<a href="{{ URL::route('profile/personal') }}" class="list-group-item">
							<i class="fa fa-user"></i> Profile
						</a>
						<a href="{{ URL::route('contacts') }}" class="list-group-item">
							<i class="fa fa-group"></i> Contacts
						</a>
						<a href="{{ URL::route('setting') }}" class="list-group-item">
							<i class="fa fa-cog"></i> Settings
						</a>
						<div class="list-group">
							<a href="{{ URL::route('getAddCredits') }}" class="list-group-item">
								<i class="fa fa-money"></i> Add Credits
							</a>
							<a href="{{ URL::route('getChangePassword') }}" class="list-group-item active">
								<i class="fa fa-key"></i> Change Password
							</a>
					    </div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-9">
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
					<h3>Change Password</h3>
					<form role="form" method="POST" action="{{ URL::route('setting') }}">
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div>
									<p>In order to change your Password, you must first enter your old Password:</p>
									<p>A strong password…</p>
									<ol>
										<li>is minimum of 6 in length</li>
										<li>is a combination of letters (lower case or upper case) and at least one number or other non-letter character</li>
									</ol>
								</div>

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
</div> <!-- /container -->
@endsection	