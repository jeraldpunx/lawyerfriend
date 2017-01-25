@extends('layout')

@section('content')
<div class="container">
	<h3>User Registration</h3>
	<div class="row top30">
		<div class="col-md-8">
			<div class="panel panel-default">
				<!-- <div class="panel-heading">Register</div> -->
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

					<form role="form" method="POST" action="{{ URL::route('register_user') }}">
						<div>
							<div class="col-xs-6 form-group">
								<label>First Name</label>
								{{ Form::text('first_name', '', ['class'=>'form-control', 'required'=>'']) }}
							</div>

							<div class="col-xs-6 form-group">
								<label>Last Name</label>
								{{ Form::text('last_name', '', ['class'=>'form-control', 'required'=>'']) }}
							</div>

							<div class="col-xs-6 form-group">
								<label>Password</label>
								{{ Form::password('password', ['class'=>'form-control', 'required'=>'']) }}
							</div>

							<div class="col-xs-6 form-group">
								<label>Confirm Password</label>
								{{ Form::password('password_confirmation', ['class'=>'form-control', 'required'=>'']) }}
							</div>
							
							<div class="col-xs-6 form-group">
								<label>E-Mail Address</label>
								{{ Form::text('email', '', ['class'=>'form-control', 'required'=>'']) }}
							</div>

							<div class="col-xs-6 form-group">
								<label>Birthdate</label>
								<div class='input-group date'>
					                {{ Form::text('birthdate', '', ['class'=>'form-control', 'required'=>'']) }}
					                <span class="input-group-addon">
					                    <span class="glyphicon glyphicon-calendar">
					                    </span>
					                </span>
					            </div>
							</div>
						</div>

						

						<br style="clear:both" />
						<center>
							<div class="top30">
								<button type="submit" class="btn btn-primary">
									Register
								</button>
							</div>
						</center>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<ul class="checked">
						<li>Free access to top rated lawyers</li>
						<li>Free options to save on legal fees</li>
						<li>Easy to earn points</li>
						<li>Always fixed prices</li>
						<li>Encrypted SSL - Your data is safe!</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection