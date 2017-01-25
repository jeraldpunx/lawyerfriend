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
	Work
	<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progressPoints,$numberOfTextfield) }})</small></span>
</h3>
<div class="progress">
	<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progressPoints,$numberOfTextfield) }}"></div>
</div>
<form role="form" method="POST" action="{{ URL::route('profile/work') }}">
	<div class="row">
		<div class="col-xs-6 form-group">
			<label>Are you currently employed?</label>
			<div class="input-group btn-group" data-toggle="buttons">
				<label class="btn btn-default {{ ($profile->work_employed == "true") ? 'active' : ''; }}"><input type="radio" name="work_employed" value="true" {{ ($profile->work_employed == "true") ? 'checked="checked"' : ''; }} >Yes</label>
				<label class="btn btn-default {{ ($profile->work_employed == "false") ? 'active' : ''; }}"><input type="radio" name="work_employed" value="false" {{ ($profile->work_employed == "false") ? 'checked="checked"' : ''; }} >No</label>
			</div>
		</div>

		<div class="col-xs-6 form-group">
			<label>Are you currently retired?</label>
			<div class="input-group btn-group" data-toggle="buttons">
				<label class="btn btn-default {{ ($profile->work_retired == "true") ? 'active' : ''; }}"><input type="radio" name="work_retired" value="true" {{ ($profile->work_retired == "true") ? 'checked="checked"' : ''; }} >Yes</label>
				<label class="btn btn-default {{ ($profile->work_retired == "false") ? 'active' : ''; }}"><input type="radio" name="work_retired" value="false" {{ ($profile->work_retired == "false") ? 'checked="checked"' : ''; }} >No</label>
			</div>
		</div>

		<div class="col-xs-6 form-group">
			<label>What is your position?</label>
			{{ Form::text('work_position', $profile->work_position, ['class'=>'form-control']) }}
		</div>

		<div class="col-xs-6 form-group">
			<label>What is your salary?</label>
			{{ Form::text('work_salary', $profile->work_salary, ['class'=>'form-control']) }}
		</div>

		<div class="col-xs-6 form-group">
			<label>What is your household income?</label>
			{{ Form::text('work_income', $profile->work_income, ['class'=>'form-control']) }}
		</div>
	</div>
	<div class="pull-right">
		<input type="submit" class="btn btn-success btn-lg" value="Save" />
	</div>
</form>
@endsection

@section('script')
@endsection