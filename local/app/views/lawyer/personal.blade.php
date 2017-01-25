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
					<li class="active"><a href="{{ URL::route('profile/personal') }}">Basic Information</a></li>
					<li class=""><a href="{{ URL::route('profile/practice') }}">Practice Information</a></li>
				</ul>
			</div>
			<div class="col-md-2">
				<div class="row">
					<div class="img-container-background">
						<div class="img-container">
							<img src="{{ asset('uploads/'.Auth::user()->image) }}" class="img-responsive"/>
						</div>
					</div>
					<center>
						<a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#image_upload_modal"><span class="fa fa-picture-o"></span> upload</a>
					</center>			
				</div>
			</div>
			<div class="col-md-10">
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
							<label>E-Mail Address</label>
							{{ Form::text('email', Auth::user()->email, ['class'=>'form-control', 'required'=>'']) }}
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
							<label>Gender</label>
							<div class="input-group btn-group" data-toggle="buttons">
								<label class="btn btn-default {{ (Auth::user()->gender == "male") ? 'active' : ''; }}"><input type="radio" name="gender" value="male" {{ (Auth::user()->gender == "male") ? 'checked="checked"' : ''; }} >Male</label>
								<label class="btn btn-default {{ (Auth::user()->gender == "female") ? 'active' : ''; }}"><input type="radio" name="gender" value="female" {{ (Auth::user()->gender == "female") ? 'checked="checked"' : ''; }} >Female</label>
							</div>
						</div>

						<div class="col-xs-6 form-group">
							<label>Ethnicity</label>
							<select class="selectize" name="ethnicity">
								<option value="" selected="selected"></option>
							@foreach($ethnicities as $ethnicity)
								<option value="{{ $ethnicity['id'] }}" {{ (Auth::user()->ethnicity == $ethnicity['id']) ? 'selected="selected"' : ''; }}>{{ $ethnicity['name'] }}</option>
							@endforeach
							</select>
						</div>

						<div class="col-xs-9 form-group">
							<label>Street</label>
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

						<div class="col-xs-6 form-group">
							<label>Home Number</label>
							{{ Form::text('home_number', Auth::user()->home_number, ['class'=>'form-control']) }}
						</div>

						<div class="col-xs-6 form-group">
							<label>Mobile Number</label>
							{{ Form::text('mobile_number', Auth::user()->mobile_number, ['class'=>'form-control']) }}
						</div>

						<div class="pull-right">
							<input type="submit" class="btn btn-success btn-lg" value="Save" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- IMAGE UPLOAD MODAL -->
	<div class="modal fade" id="image_upload_modal" role="dialog">
		<div class="modal-dialog">
			<form action="{{ URL::route('profile/personal/image') }}" method="POST" enctype="multipart/form-data">
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
<script src="http://fgnass.github.io/spin.js/spin.min.js"></script>
<script type="text/javascript">
    $(".file-upload").fileinput({
		showCaption: false,
        browseClass: "btn btn-primary btn-block",
        showUpload: true,
        uploadClass: "btn btn-success btn-block",
        showRemove: false,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
	});

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