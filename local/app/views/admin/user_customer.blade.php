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
						<h4>
							Personal
							<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progress['personal']['points'],$progress['personal']['numberOfTextfield']) }})</small></span>
						</h4>
						<div class="progress">
							<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['personal']['points'],$progress['personal']['numberOfTextfield']) }}"></div>
						</div>
					</div>
					<div class="panel-body">
						<form role="form" method="POST" action="{{ URL::route('customer/personal', ['user_id'=>$user->id]) }}">
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
									<label>Ethnicity</label>
									<select class="selectize" name="ethnicity">
										<option value="" selected="selected"></option>
									@foreach($ethnicities as $ethnicity)
										<option value="{{ $ethnicity['id'] }}" {{ ($user->ethnicity == $ethnicity['id']) ? 'selected="selected"' : ''; }}>{{ $ethnicity['name'] }}</option>
									@endforeach
									</select>
								</div>

								<div class="col-xs-6 form-group">
									<label>Gender</label>
									<div class="input-group btn-group" data-toggle="buttons">
										<label class="btn btn-default {{ ($user->gender == "male") ? 'active' : ''; }}"><input type="radio" name="gender" value="male" {{ ($user->gender == "male") ? 'checked="checked"' : ''; }} >Male</label>
										<label class="btn btn-default {{ ($user->gender == "female") ? 'active' : ''; }}"><input type="radio" name="gender" value="female" {{ ($user->gender == "female") ? 'checked="checked"' : ''; }} >Female</label>
									</div>
								</div>
							</div>

							<div class="pull-right">
								<input type="submit" class="btn btn-success btn-lg" value="Save" />
							</div>
						</form>
					</div>
				</div>

				<div class="panel">
					<div class="panel-heading">
						<h4>Contact
							<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progress['contact']['points'],$progress['contact']['numberOfTextfield']) }})</small></span>
						</h4>
						<div class="progress">
							<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['contact']['points'],$progress['contact']['numberOfTextfield']) }}"></div>
						</div>
					</div>
					<div class="panel-body">
						<form role="form" method="POST" action="{{ URL::route('customer/contact', ['user_id'=>$user->id]) }}">
							<div class="row">
								<div class="col-xs-6 form-group">
									<label>Home Phone</label>
									{{ Form::text('home_number', $user->home_number, ['class'=>'form-control']) }}
								</div>

								<div class="col-xs-6 form-group">
									<label>Mobile Phone</label>
									{{ Form::text('mobile_number', $user->mobile_number, ['class'=>'form-control']) }}
								</div>

								<div class="col-xs-6 form-group">
									<label>Email</label>
									{{ Form::text('email', $user->email, ['class'=>'form-control', 'required'=>'']) }}
								</div>


								<div class="col-xs-9 form-group">
									<label>Street Addres</label>
									{{ Form::text('street', $user->street, ['class'=>'form-control']) }}
								</div>

								<div class="col-xs-3 form-group">
									<label>Zip Code</label>
									{{ Form::text('zip', $user->zip, ['class'=>'form-control']) }}
								</div>

								<div class="col-xs-6 form-group">
									<label>State</label>
									<select class="form-control" name="state" id="state">
										<option value="" selected="selected"></option>
									@foreach($states as $state)
										<option value="{{ $state['id'] }}" {{ ($user->state_id == $state['id']) ? 'selected="selected"' : ''; }}>{{ $state['text'] }}</option>
									@endforeach
									</select>
								</div>

								<div class="col-xs-6 form-group">
									<label>City</label>
									<select class="form-control" name="city" id="city">
										<option value="" selected="selected"></option>
									@foreach($cities as $city)
										<option value="{{ $city['id'] }}" {{ ($user->city_id == $city['id']) ? 'selected="selected"' : ''; }}>{{ $city['name'] }}</option>
									@endforeach
									</select>
								</div>
							</div>

							<div class="pull-right">
								<input type="submit" class="btn btn-success btn-lg" value="Save" />
							</div>
						</form>
					</div>
				</div>

				<div class="panel">
					<div class="panel-heading">
						<h4>Work
							<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progress['work']['points'],$progress['work']['numberOfTextfield']) }})</small></span>
						</h4>
						<div class="progress">
							<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['work']['points'],$progress['work']['numberOfTextfield']) }}"></div>
						</div>
					</div>
					<div class="panel-body">
						<form role="form" method="POST" action="{{ URL::route('customer/work', ['user_id'=>$user->id]) }}">
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
					</div>
				</div>

				<div class="panel">
					<div class="panel-heading">
						<h4>Education
							<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progress['edu']['points'],$progress['edu']['numberOfTextfield']) }})</small></span>
						</h4>
						<div class="progress">
							<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['edu']['points'],$progress['edu']['numberOfTextfield']) }}"></div>
						</div>
					</div>
					<div class="panel-body">
						<form role="form" method="POST" action="{{ URL::route('customer/education', ['user_id'=>$user->id]) }}">
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
					</div>
				</div>

				<div class="panel">
					<div class="panel-heading">
						<h4>Transportation
							<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progress['trans']['points'],$progress['trans']['numberOfTextfield']) }})</small></span>
						</h4>
						<div class="progress">
							<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['trans']['points'],$progress['trans']['numberOfTextfield']) }}"></div>
						</div>
					</div>
					<div class="panel-body">
						<form role="form" method="POST" action="{{ URL::route('customer/transportation', ['user_id'=>$user->id]) }}">
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
									<label>Insurance Infomation</label>
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
					</div>
				</div>

				<div class="panel">
					<div class="panel-heading">
						<h4>Criminal
							<span class="pull-right"><small class="">+2 points ({{ helper::fraction($progress['crim']['points'],$progress['crim']['numberOfTextfield']) }})</small></span>
						</h4>
						<div class="progress">
							<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['crim']['points'],$progress['crim']['numberOfTextfield']) }}"></div>
						</div>
					</div>
					<div class="panel-body">
						<form role="form" method="POST" action="{{ URL::route('customer/criminal', ['user_id'=>$user->id]) }}">
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
					</div>
				</div>

				<div class="panel">
					<div class="panel-heading">
						<h4>Change Password</h4>
					</div>
					<div class="panel-body">
						<form role="form" method="POST" action="{{ URL::route('customer/password', ['user_id'=>$user->id]) }}">
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
</div> <!-- /container -->
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