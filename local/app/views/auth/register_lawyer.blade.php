@extends('layout')


@section('content')
<div class="container">
	<h3>Lawyer Registration</h3>
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

					<form role="form" method="POST" action="{{ URL::route('register_lawyer') }}">
						<h4>Basic Information</h4>
						<div class="row">
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
								<label>E-Mail Address <small>(Please use your bar email)</small></label>
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

						<h4 class="top30" >Business Information</h4>
						<div class="row">
							<div class="col-xs-12 form-group">
								<label>Firm Name *</label>
								{{ Form::text('firm_name', '', ['class'=>'form-control', 'required'=>'']) }}
							</div>
						</div>

						<?php $count = 0;?>
						<div class="fields">
							<div class="practice_area_div">
	 							<hr>
									<p><strong>Practice Area <span class="practice-counter">{{ ++$count }}</span> <a href="#" class="removePracticeArea"><span class="pull-right">&times;</span></a></strong></p>

		 						<div class="row">
		 							<div class="col-xs-6 form-group">
										<label></label>
										<select class="practice_areas" name="practice_areas[]">
											<option value="" disabled selected>Select Practice Area</option>
										@foreach($practice_areas as $practice_area)
											<option value="{{ $practice_area['id'] }}">{{ $practice_area['name'] }}</option>
										@endforeach
										</select>
									</div>

									<div class="col-xs-4 form-group">
										<label>Street</label>
										<input type="text" value="" placeholder="Street" name="street[]" class="form-control">
									</div>

									<div class="col-xs-2 form-group">
										<label>Zip</label>
										<input type="text" value="" placeholder="ZIP" name="zip[]" class="form-control">
									</div>

									<div class="col-xs-3 col-xs-offset-6 form-group">
										<label>State</label>
										<select class="form-control state" name="state[]">
											<option value="" disabled selected>State</option>
										@foreach($states as $state)
											<option value="{{ $state['id'] }}">{{ $state['text'] }}</option>
										@endforeach
										</select>
									</div>

									<div class="col-xs-3 form-group">
										<label>City</label>
										<select class="form-control city" name="city[]">
											<option value="" disabled selected>City</option>
										</select>
									</div>
		 						</div>
							</div>
						</div>

						<a href="#" id="add-practice-area" class="btn btn-default">+ Add Practice Area</a>

						<h4 class="top30" >License Information</h4>
						<div>
							<div class="col-xs-6 form-group">
								<label>License ID #</label>
								{{ Form::text('license_id', '', ['class'=>'form-control', 'required'=>'']) }}
							</div>

							<div class="col-xs-3 form-group">
								<label>State Issued</label>
								<select class="selectize" name="state_issued" required>
									<option value=""></option>
								@foreach($states as $state)
									<option value="{{ $state['id'] }}">{{ $state['text'] }}</option>
								@endforeach
								</select>
							</div>

							<div class="col-xs-3 form-group">
								<label>Date Issued</label>
								<div class='input-group date'>
					                {{ Form::text('date_issued', '', ['class'=>'form-control', 'required'=>'']) }}
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

					<!-- CLONE -->
					<div class="hide clone-field">
						<div class="practice_area_div">
							<hr>
							<p><strong>Practice Area <span class="practice-counter"></span></strong> <a href="#" class="removePracticeArea"><span class="pull-right">&times;</span></a></h4>
							<div class="row">
								<div class="col-xs-6 form-group">
									<label></label>
									<select name="practice_areas[]">
										<option value="" disabled selected>Select Practice Area</option>
									@foreach($practice_areas as $practice_area)
										<option value="{{ $practice_area['id'] }}" required>
											{{ $practice_area['name'] }}
										</option>
									@endforeach
									</select>
								</div>

								<div class="col-xs-4 form-group">
									<label>Street</label>
									<input type="text" placeholder="Street" name="street[]" class="form-control" required>
								</div>

								<div class="col-xs-2 form-group">
									<label>Zip</label>
									<input type="text" placeholder="ZIP" name="zip[]" class="form-control" required>
								</div>

								<div class="col-xs-3 col-xs-offset-6 form-group">
									<label>State</label>
									<select class="form-control state" name="state[]" required>
										<option value="" disabled selected>State</option>
										@foreach($states as $state)
											<option value="{{ $state['id'] }}">{{ $state['text'] }}</option>
										@endforeach
									</select>
								</div>

								<div class="col-xs-3 form-group">
									<label>City</label>
									<select class="form-control city" name="city[]" required>
										<option value="" disabled selected>City</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<!-- END OF CLONE -->
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<ul class="checked">
						<li>Select practice areas and locations served</li>
						<li>Get in front of highly targeted customers</li>
						<li>Collect helpful information about each inquiry to better serve your customers</li>
						<li>Real time bidding marketplace / free market</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
<script>
	$('.practice_areas').selectize({
	    persist: false,
	    createOnBlur: true,
	});

    $('#state-personal').on('change', function(){
		var state_id = $(this).find(':selected').val();
		$("#city-personal").children().remove();

		$("#city-personal").append($('<option>', {
			value: "",
			text: "",
		}));

		$.ajax({
			type: "GET",
			url: "{{ URL::route('getCityByState') }}",
			data: { state_id: state_id }
		}).done(function( result ) {
			$(result).each(function(){
				$("#city-personal").append($('<option>', {
					value: this.id,
					text: this.name,
				}));
			})
		});
	});

	$(".container").on("click", "#add-practice-area", function(e){
		e.stopPropagation();
		e.preventDefault();

		var practice_counter = parseInt($('.fields span.practice-counter:last').html()) + 1;

		if(practice_counter <= 3) {
			var append_att = $(".clone-field");
			
			append_att.find('.practice-counter').html(practice_counter);

			$(".fields").append(append_att.html());

			$('.fields .practice_area_div').last().find('[name="practice_areas[]"]').selectize({
			    persist: false,
			    createOnBlur: true,
			});
		} else {
			displayNotifit( "Maximum of 3 practice area only." , false );
		}
	}).on('change', '.state', function(){
    	var state_id = this.value;
		var city = $(this).parents('.practice_area_div').find('.city');

		city.children().remove();

		city.append($('<option>', {
			value: "",
			text: "",
		}));

		$.ajax({
			type: "GET",
			url: "{{ URL::route('getCityByState') }}",
			data: { state_id: state_id }
		}).done(function( result ) {
			$(result).each(function(){

				city.append($('<option>', {
					value: this.id,
					text: this.name,
				}));
			})
		});
	}).on('click', '.removePracticeArea', function(e){
		e.preventDefault();
		$(this).parents('.practice_area_div').remove();
	});
</script>
@endsection