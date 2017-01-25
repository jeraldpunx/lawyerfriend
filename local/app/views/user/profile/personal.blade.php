@extends('user.profile.layout')


@section('panel-body')
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
<h3>
	Personal
	<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progressPoints,$numberOfTextfield) }})</small></span>
</h3>
<div class="progress">
	<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progressPoints,$numberOfTextfield) }}"></div>
</div>
<form role="form" method="POST" action="{{ URL::route('profile/personal') }}">
	<div class="row">
		<div class="col-xs-6 form-group">
			<label>First Name</label>
			{{ Form::text('first_name', Auth::user()->first_name, ['class'=>'form-control', 'required'=>'']) }}
		</div>

		<div class="col-xs-6 form-group">
			<label>Last Name</label>
			{{ Form::text('last_name', Auth::user()->last_name, ['class'=>'form-control', 'required'=>'']) }}
		</div>

		<div class="col-xs-6 form-group">
			<label>Birthdate</label>
			<div class='input-group date'>
                {{ Form::text('birthdate', date("m/d/Y", strtotime( Auth::user()->birthdate )), ['class'=>'form-control', 'required'=>'']) }}
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar">
                    </span>
                </span>
            </div>
		</div>

		<div class="col-xs-6 form-group">
			<label>Ethnicity</label>
			<select class="form-control" name="ethnicity">
				<option value="" selected="selected"></option>
			@foreach($ethnicities as $ethnicity)
				<option value="{{ $ethnicity['id'] }}" {{ (Auth::user()->ethnicity == $ethnicity['id']) ? 'selected="selected"' : ''; }}>{{ $ethnicity['name'] }}</option>
			@endforeach
			</select>
		</div>

		<div class="col-xs-6 form-group">
			<label>Gender</label>
			<div class="input-group btn-group" data-toggle="buttons">
				<label class="btn btn-default {{ (Auth::user()->gender == "male") ? 'active' : ''; }}"><input type="radio" name="gender" value="male" {{ (Auth::user()->gender == "male") ? 'checked="checked"' : ''; }} >Male</label>
				<label class="btn btn-default {{ (Auth::user()->gender == "female") ? 'active' : ''; }}"><input type="radio" name="gender" value="female" {{ (Auth::user()->gender == "female") ? 'checked="checked"' : ''; }} >Female</label>
			</div>
		</div>
	</div>
	<div class="pull-right">
		<input type="submit" class="btn btn-success btn-lg" value="Save" />
	</div>
</form>
@endsection