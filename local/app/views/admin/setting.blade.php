@extends('layout')


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<h3>Settings</h3>

					<div class="row">
						<div class="col-md-6">
							@if (count($errors) > 0 && Session::has('validated_profile_form') == true)
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

							@if (Session::has('flash.message') && Session::has('validated_profile_form') == true)
								<div class="alert alert-{{Session::get('flash.type')}}">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									{{ Session::get('flash.message') }}
								</div>
							@endif
							<h4>Profile</h4>

							<form role="form" method="POST" action="{{ URL::route('setting') }}">
								<div class="form-group">
									<label>First Name:</label>
									<input class="form-control first_name" name="first_name" value="{{ Auth::user()->first_name }}" required="required"/>
								</div>
								<div class="form-group">
									<label>Last Name:</label>
									<input class="form-control last_name" name="last_name" value="{{ Auth::user()->last_name }}" required="required"/>
								</div>
								<div class="form-group">
									<label>Email:</label>
									<input class="form-control email" name="email" value="{{ Auth::user()->email }}" required="required"/>
								</div>

								<div class="form-group">
									<label>Birthdate:</label>
									<div class='input-group date'>
										<input type="text" class="form-control birthdate" value="{{ date("m/d/Y", strtotime( Auth::user()->birthdate )) }}" name="birthdate" required="required"/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
											</span>
										</span>
									</div>
								</div>

								<div class="pull-right">
									<button type="submit" name="submit" value="profile" class="btn btn-success btn-lg">Save</button>
								</div>
							</form>
						</div>

						<div class="col-md-6">
							@if (count($errors) > 0 && Session::has('validated_password_form') == true)
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

							@if (Session::has('flash.message') && Session::has('validated_password_form') == true)
								<div class="alert alert-{{Session::get('flash.type')}}">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									{{ Session::get('flash.message') }}
								</div>
							@endif
							<h4>Change Password</h4>

							<form role="form" method="POST" action="{{ URL::route('setting') }}">
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

								<div class="pull-right">
									<button type="submit" name="submit" value="password" class="btn btn-success btn-lg">Save</button>
								</div>
							</form>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div> <!-- /container -->
@endsection	