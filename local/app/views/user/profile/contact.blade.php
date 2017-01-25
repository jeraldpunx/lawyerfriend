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
	Contact
	<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progressPoints,$numberOfTextfield) }})</small></span>
</h3>
<div class="progress">
	<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progressPoints,$numberOfTextfield) }}"></div>
</div>
<form role="form" method="POST" action="{{ URL::route('profile/contact') }}">
	<div class="row">
		<div class="col-xs-6 form-group">
			<label>Home Phone</label>
			{{ Form::text('home_number', Auth::user()->home_number, ['class'=>'form-control']) }}
		</div>

		<div class="col-xs-6 form-group">
			<label>Mobile Phone</label>
			{{ Form::text('mobile_number', Auth::user()->mobile_number, ['class'=>'form-control']) }}
		</div>

		<div class="col-xs-6 form-group">
			<label>Email</label>
			{{ Form::text('email', Auth::user()->email, ['class'=>'form-control', 'required'=>'']) }}
		</div>


		<div class="col-xs-9 form-group">
			<label>Street Addres</label>
			{{ Form::text('street', Auth::user()->street, ['class'=>'form-control']) }}
		</div>

		<div class="col-xs-3 form-group">
			<label>Zip Code</label>
			{{ Form::text('zip', Auth::user()->zip, ['class'=>'form-control']) }}
		</div>

		<div class="col-xs-6 form-group">
			<label>State</label>
			<select class="form-control" name="state" id="state">
				<option value="" selected="selected"></option>
			@foreach($states as $state)
				<option value="{{ $state['id'] }}" {{ (Auth::user()->state_id == $state['id']) ? 'selected="selected"' : ''; }}>{{ $state['text'] }}</option>
			@endforeach
			</select>
		</div>

		<div class="col-xs-6 form-group">
			<label>City</label>
			<select class="form-control" name="city" id="city">
				<option value="" selected="selected"></option>
			@foreach($cities as $city)
				<option value="{{ $city['id'] }}" {{ (Auth::user()->city_id == $city['id']) ? 'selected="selected"' : ''; }}>{{ $city['name'] }}</option>
			@endforeach
			</select>
		</div>
	</div>
	<div class="pull-right">
		<input type="submit" class="btn btn-success btn-lg" value="Save" />
	</div>
</form>
@endsection

@section('script')
<script type="text/javascript">
	$('#state').on('change', function(){
		var state_id = $(this).find(':selected').val();
		$("#city").children().remove();

		$("#city").append($('<option>', {
			value: "",
			text: "",
		}));

		$.ajax({
			type: "GET",
			url: "{{ URL::route('getCityByState') }}",
			data: { state_id: state_id }
		}).done(function( result ) {
			$(result).each(function(){
				$("#city").append($('<option>', {
					value: this.id,
					text: this.name,
				}));
			})
		});
	});
</script>
@endsection