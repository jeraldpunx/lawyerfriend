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
	Criminal
	<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progressPoints,$numberOfTextfield) }})</small></span>
</h3>
<div class="progress">
	<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progressPoints,$numberOfTextfield) }}"></div>
</div>
<form role="form" method="POST" action="{{ URL::route('profile/criminal') }}">
	<div class="row">
		<div class="col-xs-6 form-group">
			<label>Have you ever been arrested?</label>
			<div class="input-group btn-group" data-toggle="buttons">
				<label class="btn btn-default {{ ($profile->crim_arrests == "true") ? 'active' : ''; }}"><input type="radio" name="crim_arrests" value="true" {{ ($profile->crim_arrests == "true") ? 'checked="checked"' : ''; }} >Yes</label>
				<label class="btn btn-default {{ ($profile->crim_arrests == "false") ? 'active' : ''; }}"><input type="radio" name="crim_arrests" value="false" {{ ($profile->crim_arrests == "false") ? 'checked="checked"' : ''; }} >No</label>
			</div>
		</div>
	</div>
	<div class="pull-right">
		<input type="submit" class="btn btn-success btn-lg" value="Save" />
	</div>
</form>
@endsection

@section('script')
@endsection