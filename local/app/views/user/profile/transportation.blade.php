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
	Transportation
	<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progressPoints,$numberOfTextfield) }})</small></span>
</h3>
<div class="progress">
	<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progressPoints,$numberOfTextfield) }}"></div>
</div>
<form role="form" method="POST" action="{{ URL::route('profile/transportation') }}">
	<div class="row">
		<div class="col-xs-12 form-group">
			<label>Do you own a vehicle?</label>
			<div class="input-group btn-group" data-toggle="buttons">
				<label class="btn btn-default {{ ($profile->trans_own == "true") ? 'active' : ''; }}"><input type="radio" name="trans_own" value="true" {{ ($profile->trans_own == "true") ? 'checked="checked"' : ''; }} >Yes</label>
				<label class="btn btn-default {{ ($profile->trans_own == "false") ? 'active' : ''; }}"><input type="radio" name="trans_own" value="false" {{ ($profile->trans_own == "false") ? 'checked="checked"' : ''; }} >No</label>
			</div>
		</div>

		<div class="col-xs-6 form-group">
			<label>Vehicle Type</label>
			{{ Form::text('trans_type', $profile->trans_type, ['class'=>'form-control']) }}
		</div>

		<div class="col-xs-6 form-group">
			<label>Insurance Information</label>
			{{ Form::text('trans_insurance', $profile->trans_insurance, ['class'=>'form-control']) }}
		</div>

		<div class="col-xs-6 form-group">
			<label>Number of tickets</label>
			{{ Form::text('trans_tickets', $profile->trans_tickets, ['class'=>'form-control']) }}
		</div>
	</div>
	<div class="pull-right">
		<input type="submit" class="btn btn-success btn-lg" value="Save" />
	</div>
</form>
@endsection

@section('script')
@endsection