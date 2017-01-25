@extends('layout')


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<div class="row">
				<div class="sidebar-offcanvas">
					<div class="list-group">
						<a href="{{ URL::route('profile/personal') }}" class="list-group-item active">
							<i class="fa fa-user"></i> Profile
						</a>
						<a href="{{ URL::route('contacts') }}" class="list-group-item">
							<i class="fa fa-group"></i> Contacts
						</a>
						<a href="{{ URL::route('setting') }}" class="list-group-item">
							<i class="fa fa-cog"></i> Settings
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-9">
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
			<div class="col-md-12">
				<h3>Basic Information</h3>
				<ul class="btn-bar">
					<li class=""><a href="{{ URL::route('profile/personal') }}">Basic Information</a></li>
					<li class="active"><a href="{{ URL::route('profile/practice') }}">Practice Information</a></li>
				</ul>
			</div>
			<div class="col-md-12">
				<form role="form" method="POST" action="{{ URL::route('profile/practice') }}">
					<div class="row">
						<div class="col-xs-12 form-group">
							<label>Firm Name *</label>
							{{ Form::text('firm_name', $profile->firm_name, ['class'=>'form-control', 'required'=>'']) }}
						</div>

						<div class="col-xs-12 form-group">
							<label>Description</label>
							<textarea name="description" placeholder="Description" class="form-control">{{ $profile->firm_desc }}</textarea>
						</div>
					</div>
					
					<?php $count = 0;?>
					<div class="fields">
						@foreach($lawyer_pas as $lawyer_pa)
						<div class="practice_area_div">
 							<hr>
								<h4>Practice Area <span class="practice-counter">{{ ++$count }}</span> <a href="#" class="removePracticeArea"><span class="pull-right">&times;</span></a></h4>

	 						<div class="row">
	 							<div class="col-xs-6 form-group">
									<label></label>
									<select class="practice_areas" name="practice_areas[]">
										<option value="" disabled selected>Select Practice Area</option>
									@foreach($practice_areas as $practice_area)
										<option value="{{ $practice_area['id'] }}" {{ ($lawyer_pa['practice_area_id'] == $practice_area['id']) ? 'selected="selected"' : ''; }}>
											{{ $practice_area['name'] }}
										</option>
									@endforeach
									</select>
								</div>

								<div class="col-xs-4 form-group">
									<label>Street</label>
									<input type="text" value="{{ $lawyer_pa['street'] }}" placeholder="Street" name="street[]" class="form-control">
								</div>

								<div class="col-xs-2 form-group">
									<label>Zip</label>
									<input type="text" value="{{ $lawyer_pa['zip'] }}" placeholder="ZIP" name="zip[]" class="form-control">
								</div>

								<div class="col-xs-3 col-xs-offset-6 form-group">
									<label>State</label>
									<select class="form-control state" name="state[]">
										<option value="" disabled selected>State</option>
									@foreach($states as $state)
										<option value="{{ $state['id'] }}" {{ ($lawyer_pa['state_id'] == $state['id']) ? 'selected="selected"' : ''; }}>{{ $state['text'] }}</option>
									@endforeach
									</select>
								</div>

								<div class="col-xs-3 form-group">
									<label>City</label>
									<select class="form-control city" name="city[]">
										<option value="" disabled selected>City</option>
									@foreach($lawyer_pa['cities'] as $city)
										<option value="{{ $city['id'] }}" {{ ($lawyer_pa['city_id'] == $city['id']) ? 'selected="selected"' : ''; }}>{{ $city['name'] }}</option>
									@endforeach
									</select>
								</div>
	 						</div>
						</div>
					@endforeach
					</div>

					<a href="#" id="add-practice-area" class="btn btn-default">+ Add Practice Area</a>

					<div class="pull-right">
						<input type="submit" class="btn btn-success btn-lg" value="Save" />
					</div>
				</form>

				<!-- CLONE -->
				<div class="hide clone-field">
					<div class="practice_area_div">
						<hr>
						<h4>Practice Area <span class="practice-counter"></span>  <a href="#" class="removePracticeArea"><span class="pull-right">&times;</span></a></h4>
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
</div> <!-- /container -->
@endsection

@section('script')
<script type="text/javascript">
	$('.practice_areas').selectize({
	    persist: false,
	    createOnBlur: true,
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