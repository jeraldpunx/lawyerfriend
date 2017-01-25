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
	Education
	<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progressPoints,$numberOfTextfield) }})</small></span>
</h3>
<div class="progress">
	<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progressPoints,$numberOfTextfield) }}"></div>
</div>
<form role="form" method="POST" action="{{ URL::route('profile/education') }}">
	<div class="row">
		<div class="col-xs-6 form-group">
			<label>High School</label>
			{{ Form::text('edu_hs_name', $profile->edu_hs_name, ['class'=>'form-control']) }}
		</div>

		<div class="col-xs-6 form-group">
			<label>College</label>
			{{ Form::text('edu_coll_name', $profile->edu_coll_name, ['class'=>'form-control']) }}
		</div>

		<div class="col-xs-6 form-group">
			<label>High school graduation date</label>
			<div class='input-group date'>
                {{ Form::text('edu_hs_gd', date("m/d/Y", strtotime( $profile->edu_hs_gd )), ['class'=>'form-control']) }}
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar">
                    </span>
                </span>
            </div>
		</div>

		<div class="col-xs-6 form-group">
			<label>College degree</label>
			{{ Form::text('edu_coll_degree', $profile->edu_coll_degree, ['class'=>'form-control']) }}
		</div>

		<div class="col-xs-offset-6 col-xs-6 form-group">
			<label>College graduation date</label>
			<div class='input-group date'>
                {{ Form::text('edu_coll_gd', date("m/d/Y", strtotime( $profile->edu_coll_gd )), ['class'=>'form-control']) }}
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar">
                    </span>
                </span>
            </div>
		</div>
	</div>
	<div class="pull-right">
		<input type="submit" class="btn btn-success btn-lg" value="Save" />
	</div>
</form>
@endsection