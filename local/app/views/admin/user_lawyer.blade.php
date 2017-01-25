@extends('layout')


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<div class="row">
				<div class="sidebar-offcanvas">
					<div class="list-group list-group-root well">
						<a href="{{ URL::route('users') }}" class="list-group-item">
							<i class="fa fa-users"></i> Users
						</a>
						<div class="list-group">
							<a href="{{ URL::route('users') }}" class="list-group-item active">
								<i class="fa fa-list"></i> Manage
							</a>
							<a href="{{ URL::route('lawyers') }}" class="list-group-item">
								<i class="fa fa-money"></i> Send Credits
							</a>
					    </div>
						<a href="{{ URL::route('friendlevels') }}" class="list-group-item">
							<i class="fa fa-line-chart"></i> Friend Levels
						</a>
						<a href="{{ URL::route('states') }}" class="list-group-item">
							<i class="fa fa-flag-o"></i> States
						</a>
						<a href="{{ URL::route('bloglists') }}" class="list-group-item">
							<i class="fa fa-group"></i> Blogs
						</a>
						<a href="{{ URL::route('practiceareas') }}" class="list-group-item">
							<i class="fa fa-balance-scale"></i> Practice Areas
						</a>
						<a href="{{ URL::route('forms') }}" class="list-group-item">
							<i class="fa fa-file-o"></i> Forms
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-9">
			<div class="col-md-12">
				<div class="row bot17">
					<a href="{{ URL::route('users') }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Back</a>
				</div>
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
				<div class="panel">
					<div class="panel-heading">
						<h4>Personal</h4>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-2">
								<div>
									<div class="img-container-background mb--1">
										<div class="img-container">
											<img src="{{ asset('uploads/'.$user->image) }}" class="img-responsive"/>
										</div>
									</div>
									<center>
										<a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#image_upload_modal"><span class="fa fa-picture-o"></span> upload</a>
									</center>
								</div>
							</div>
							<div class="col-md-10">
								<form role="form" method="POST" action="{{ URL::route('lawyer/personal', ['user_id'=>$user->id]) }}">
									<div class="row">
										<div class="col-xs-6 form-group">
											<label>First Name</label>
											{{ Form::text('first_name', $user->first_name, ['class'=>'form-control', 'required'=>'']) }}
										</div>

										<div class="col-xs-6 form-group">
											<label>Last Name</label>
											{{ Form::text('last_name', $user->last_name, ['class'=>'form-control', 'required'=>'']) }}
										</div>

										<div class="col-xs-6 form-group">
											<label>E-Mail Address</label>
											{{ Form::text('email', $user->email, ['class'=>'form-control', 'required'=>'']) }}
										</div>

										<div class="col-xs-6 form-group">
											<label>Birthdate</label>
											<div class='input-group date'>
								                {{ Form::text('birthdate', date("m/d/Y", strtotime( $user->birthdate )), ['class'=>'form-control', 'required'=>'']) }}
								                <span class="input-group-addon">
								                    <span class="glyphicon glyphicon-calendar">
								                    </span>
								                </span>
								            </div>
										</div>

										<div class="col-xs-6 form-group">
											<label>Gender</label>
											<div class="input-group btn-group" data-toggle="buttons">
												<label class="btn btn-default {{ ($user->gender == "male") ? 'active' : ''; }}"><input type="radio" name="gender" value="male" {{ ($user->gender == "male") ? 'checked="checked"' : ''; }} >Male</label>
												<label class="btn btn-default {{ ($user->gender == "female") ? 'active' : ''; }}"><input type="radio" name="gender" value="female" {{ ($user->gender == "female") ? 'checked="checked"' : ''; }} >Female</label>
											</div>
										</div>

										<div class="col-xs-6 form-group">
											<label>Ethnicity</label>
											<select class="selectize" name="ethnicity">
												<option value="" selected="selected"></option>
											@foreach($ethnicities as $ethnicity)
												<option value="{{ $ethnicity['id'] }}" {{ ($user->ethnicity == $ethnicity['id']) ? 'selected="selected"' : ''; }}>{{ $ethnicity['name'] }}</option>
											@endforeach
											</select>
										</div>

										<div class="col-xs-9 form-group">
											<label>Street</label>
											{{ Form::text('street', $user->street, ['class'=>'form-control']) }}
										</div>

										<div class="col-xs-3 form-group">
											<label>Zip Code</label>
											{{ Form::text('zip', $user->zip, ['class'=>'form-control']) }}
										</div>

										<div class="col-xs-6 form-group">
											<label>State</label>
											<select class="form-control" name="state" id="state-personal">
												<option value="" selected="selected"></option>
											@foreach($states as $state)
												<option value="{{ $state['id'] }}" {{ ($user->state_id == $state['id']) ? 'selected="selected"' : ''; }}>{{ $state['text'] }}</option>
											@endforeach
											</select>
										</div>

										<div class="col-xs-6 form-group">
											<label>City</label>
											<select class="form-control" name="city" id="city-personal">
												<option value="" selected="selected"></option>
											@foreach($cities as $city)
												<option value="{{ $city['id'] }}" {{ ($user->city_id == $city['id']) ? 'selected="selected"' : ''; }}>{{ $city['name'] }}</option>
											@endforeach
											</select>
										</div>

										<div class="col-xs-6 form-group">
											<label>Home Number</label>
											{{ Form::text('home_number', $user->home_number, ['class'=>'form-control']) }}
										</div>

										<div class="col-xs-6 form-group">
											<label>Mobile Number</label>
											{{ Form::text('mobile_number', $user->mobile_number, ['class'=>'form-control']) }}
										</div>
									</div>
									<div class="pull-right">
										<input type="submit" class="btn btn-success btn-lg" value="Save" />
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="panel">
					<div class="panel-heading">
						<h4>Practice</h4>
					</div>
					<div class="panel-body">
						<form role="form" method="POST" action="{{ URL::route('lawyer/practice', ['user_id'=>$user->id]) }}">
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

				<div class="panel">
					<div class="panel-heading">
						<h4>Change Password</h4>
					</div>
					<div class="panel-body">
						<form role="form" method="POST" action="{{ URL::route('lawyer/password', ['user_id'=>$user->id]) }}">
							<div class="row">
								<div class="col-xs-6  form-group">
									<label>New Password</label>
									{{ Form::password('password', ['class'=>'form-control', 'required'=>'']) }}
								</div>

								<div class="col-xs-6 form-group">
									<label>Confirm Password</label>
									{{ Form::password('password_confirmation', ['class'=>'form-control', 'required'=>'']) }}
								</div>
							</div>

							<div class="pull-right">
								<input type="submit" class="btn btn-success btn-lg" value="Save" />
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- IMAGE UPLOAD MODAL -->
	<div class="modal fade" id="image_upload_modal" role="dialog">
		<div class="modal-dialog">
			<form action="{{ URL::route('lawyer/personal/image', ['user_id'=>$user->id]) }}" method="POST" enctype="multipart/form-data">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<center>
							<label class="control-label">Select Photo</label>
							<input name="image" class="file-upload" type="file" />
						</center>
					</div>
				</div>
            </form>
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

    $(".file-upload").fileinput({
		showCaption: false,
        browseClass: "btn btn-primary btn-block",
        showUpload: true,
        uploadClass: "btn btn-success btn-block",
        showRemove: false,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
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