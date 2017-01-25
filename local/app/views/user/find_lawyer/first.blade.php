@extends('layout')


@section('content')
<div class="container">
	<h1>Find A Lawyer</h1>
	<div class="panel wizard">
		<div class="panel-body">
			<div class="steps col-md-2">
				<ul>
					<li class="first current">
						<a href="{{ URL::route('find-a-lawyer', ['page'=>1]) }}">1. Basic Information</a>
					</li>
					<li class="disabled">
						<a href="#">2. Details</a>
					</li>
					<li class="disabled">
						<a href="#">3. View Rates</a>
					</li>
					<li class="disabled last">
						<a href="#">4. Matched Lawyers</a>
					</li>
				</ul>
			</div>
			<div class="content col-md-10">
			<form method="GET" action="{{ URL::route('find-a-lawyer') }}">
				<h4>Basic Information</h4>
				<p>Please tell us which legal practice area you need help with, and your current state and city of residence. Also, let us know if you would like to be contacted via phone or e-mail.</p>
				
				<div class="col-xs-3 form-group">
					<label>Practice area</label>
					<select class="selectize" name="practice_area" required>
						<option value=""></option>
					@foreach($practice_areas as $practice_area)
						<option value="{{ $practice_area['id'] }}" {{ ($practice_area['id'] == Input::get('practice_area')) ? 'selected="selected"' : '' }}>{{ $practice_area['name'] }}</option>
					@endforeach
					</select>
				</div>

				<div class="col-xs-3 form-group">
					<label>In what state(s)?</label>
					<select class="selectize" name="state" id="state" required>
						<option value=""></option>
					@foreach($states as $state)
						<option value="{{ $state['id'] }}" {{ ($state['id'] == Input::get('state')) ? 'selected="selected"' : '' }}>{{ $state['text'] }}</option>
					@endforeach
					</select>
				</div>

				<div class="col-xs-3 form-group">
					<label>City</label>
					<select class="form-control" name="city" id="city" required>
						<option value="" selected="selected"></option>
						@foreach($cities as $city)
							<?php var_dump($city); ?>
							<option value="{{ $city['id'] }}" {{ ($city['id'] == Input::get('city')) ? 'selected="selected"' : ''; }}>{{ $city['name'] }}</option>
						@endforeach
					</select>
				</div>

				<div class="col-xs-3 form-group">
					<label>I'd like to be contacted by:</label>
					<div class="input-group btn-group" data-toggle="buttons">
						<label class="btn btn-default {{ (Input::get('contact') == 'phone' ? 'active' : '') }}">
							<input type="radio" name="contact" value="phone" {{ (Input::get('contact') == 'phone' ? 'checked="checked"' : '') }} required>Phone
						</label>
						<label class="btn btn-default {{ (Input::get('contact') == 'email' ? 'active' : '') }}">
							<input type="radio" name="contact" value="email" {{ (Input::get('contact') == 'email' ? 'checked="checked"' : '') }}>Email
						</label>
					</div>
				</div>
				<input type="hidden" value="2" name="page" />
			</div>

			<div class="actions col-md-12">
				<div class="pull-right">
					<input type="submit" class="btn" value="Next" />
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="http://fgnass.github.io/spin.js/spin.min.js"></script>
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