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
				<h3>Users({{ $sectionText }})</h3>
				<ul class="btn-bar">
				<?php
					$section = (!empty(Route::current()->parameters()['section'])) ? strtolower(Route::current()->parameters()['section']) : 'all';
				?>
					<li class="{{ ( $section == 'all'  ) ? 'active' : '' }}">
						<a href="{{ URL::route('users', ['section'=>'all']) }}">All</a>
					</li>
					<li class="{{ ( $section == 'customers' ) ? 'active' : '' }}">
						<a href="{{ URL::route('users', ['section'=>'customers']) }}">Customers</a>
					</li>
					<li class="{{ ( $section == 'lawyers' ) ? 'active' : '' }}">
						<a href="{{ URL::route('users', ['section'=>'lawyers']) }}">Lawyers</a>
					</li>
					<li class="{{ ( $section == 'pending' ) ? 'active' : '' }}">
						<a href="{{ URL::route('users', ['section'=>'pending']) }}">Pending</a>
					</li>
				</ul>
			</div>
			<br class="top30"  />
			<div class="col-md-12">
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Role</th>
							<th>Email</th>
							<th>Phone</th>
							{{ ($section == 'customers' || $section == 'all') ? "<th>Rate</th>" : "" }}
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
						<tr>
							<td>{{ $user->first_name . " " . $user->last_name }}</td>
							<td>{{ $user->role }}</td>
							<td>{{ $user->email }}</td>
							<td>{{ $user->home_number }}</td>
							@if($section == 'customers' || $section == 'all')
								<td>{{ ($user->role == 'customer') ? "$".CustomerProfile::getCurrentRateByCity(CustomerProfile::getFriendLevel($user->id)->rate, $user->id) : "" }}</td>
							@endif
							@if( $section == 'pending' )
								<td>
									<a href="#" class="btn btn-success btn-xs approve" data-toggle="modal" data-target="#approve" data-id="{{ $user->id }}"><span class="fa fa-check"></span> Approve</a>
									<a href="#" class="btn btn-danger btn-xs decline" data-toggle="modal" data-target="#decline" data-id="{{ $user->id }}"><span class="fa fa-remove"></span> Decline</a>
								</td>
							@else
								<td>
									@if($user->role == 'admin')
										<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#edit" data-id="{{ $user->id }}"><span class="fa fa-edit"></span> Edit</a>
									@else
										<a href="{{ URL::route('user', ['user_id'=>$user->id]) }}" class="btn btn-primary btn-xs"><span class="fa fa-edit"></span> Edit</a>
									@endif
									<a href="#" class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#delete" data-id="{{ $user->id }}"><span class="fa fa-remove"></span> Delete</a>
								</td>
							@endif
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>





	<!-- Modal NEW-->
	<div class="modal fade" id="new" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">New User</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 form-group">
							<label>Role:</label>
							<select class="selectize role" name="role" required="required">
								<option value="admin">Admin</option>
								<option value="customer">Customer</option>
								<option value="lawyer">Lawyer</option>
							</select>
						</div>

						<div class="col-md-6 form-group">
							<label>First Name:</label>
							<input class="form-control first_name" name="first_name" required="required"/>
						</div>
						<div class="col-md-6 form-group">
							<label>Last Name:</label>
							<input class="form-control last_name" name="last_name" required="required"/>
						</div>

						<div class="col-md-6 form-group">
							<label>Password:</label>
							<input type="password" class="form-control password" name="password" required="required"/>
						</div>

						<div class="col-md-6 form-group">
							<label>Confirm Password:</label>
							<input type="password" class="form-control password_confirmation" name="password_confirmation" required="required"/>
						</div>

						 <div class="col-md-6 form-group">
							<label>Email:</label>
							<input class="form-control email" name="email" required="required"/>
						</div>

						<div class="col-md-6 form-group">
							<label>Birthdate:</label>
							<div class='input-group date'>
								<input type="text" class="form-control birthdate" name="birthdate" required="required"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar">
									</span>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success submitNew" data-id="">Submit</button>
					<a href="#" class="btn btn-default" data-dismiss="modal">Cancel</a>
				</div>
			</div>
		</div>
	</div>


	<!-- Modal Edit-->
	<div class="modal fade" id="edit" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Edit Admin</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<h3>Basic Information</h3>
						</div>

						<div class="col-md-6 form-group">
							<label>First Name:</label>
							<input class="form-control first_name" name="first_name" required="required"/>
						</div>
						<div class="col-md-6 form-group">
							<label>Last Name:</label>
							<input class="form-control last_name" name="last_name" required="required"/>
						</div>

						 <div class="col-md-6 form-group">
							<label>Email:</label>
							<input class="form-control email" name="email" required="required"/>
						</div>

						<div class="col-md-6 form-group">
							<label>Birthdate:</label>
							<div class='input-group date'>
								<input type="text" class="form-control birthdate" name="birthdate" required="required"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar">
									</span>
								</span>
							</div>
						</div>
					</div>


					<hr>
					<div class="row">
						<div class="col-md-12">
							<h3>Change Password</h3>
						</div>

						<div class="col-md-6 form-group">
							<label>New Password:</label>
							<input type="password" class="form-control password" name="password" required="required"/>
						</div>

						<div class="col-md-6 form-group">
							<label>Confirm Password:</label>
							<input type="password" class="form-control password_confirmation" name="password_confirmation" required="required"/>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success submitEdit" data-id="">Submit</button>
					<a href="#" class="btn btn-default" data-dismiss="modal">Cancel</a>
				</div>
			</div>
		</div>
	</div>

	<!-- DELETE Modal -->
	<div class="modal fade" id="delete" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Are you sure you want to delete this?</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 form-group">
							<label>Role:</label>
							<select class="form-control role" name="role" disabled>
								<option value="admin">Admin</option>
								<option value="customer">Customer</option>
								<option value="lawyer">Lawyer</option>
							</select>
						</div>

						<div class="col-md-6 form-group">
							<label>First Name:</label>
							<input class="form-control first_name" name="first_name" disabled/>
						</div>
						<div class="col-md-6 form-group">
							<label>Last Name:</label>
							<input class="form-control last_name" name="last_name" disabled/>
						</div>

						 <div class="col-md-6 form-group">
							<label>Email:</label>
							<input class="form-control email" name="email" disabled/>
						</div>

						<div class="col-md-6 form-group">
							<label>Birthdate:</label>
							<div class='input-group date'>
								<input type="text" class="form-control birthdate" name="birthdate" disabled/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar">
									</span>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger submitDelete" data-id="">Delete</button>
					<a href="#" class="btn btn-default" data-dismiss="modal">Cancel</a>
				</div>
			</div>
		</div>
	</div>


	<!-- Approve Lawyer-->
	<div class="modal fade" id="approve" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Approve this lawyer?</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<h3>Basic Information: </h3>
							<div class="col-md-6 form-group">
								<label>First Name:</label>
								<input class="form-control first_name" name="first_name" disabled/>
							</div>
							<div class="col-md-6 form-group">
								<label>Last Name:</label>
								<input class="form-control last_name" name="last_name" disabled/>
							</div>

							 <div class="col-md-6 form-group">
								<label>Email:</label>
								<input class="form-control email" name="email" disabled/>
							</div>

							<div class="col-md-6 form-group">
								<label>Birthdate:</label>
								<div class='input-group date'>
									<input type="text" class="form-control birthdate" name="birthdate" disabled/>
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar">
										</span>
									</span>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<h3>Practice Information: </h3>
							<div class="col-md-12 form-group">
								<label>Firm Name:</label>
								<input class="form-control firm_name" name="firm_name" disabled/>
							</div>
							<div class="col-md-12 form-group">
								<label>Description:</label>
								<textarea class="form-control firm_desc" name="description" disabled></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success submitApprove" data-id="">Approve</button>
					<a href="#" class="btn btn-default" data-dismiss="modal">Cancel</a>
				</div>
			</div>
		</div>
	</div>


	<!-- Decline Lawyer-->
	<div class="modal fade" id="decline" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Decline this lawyer?</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<h3>Basic Information: </h3>
							<div class="col-md-6 form-group">
								<label>First Name:</label>
								<input class="form-control first_name" name="first_name" disabled/>
							</div>
							<div class="col-md-6 form-group">
								<label>Last Name:</label>
								<input class="form-control last_name" name="last_name" disabled/>
							</div>

							 <div class="col-md-6 form-group">
								<label>Email:</label>
								<input class="form-control email" name="email" disabled/>
							</div>

							<div class="col-md-6 form-group">
								<label>Birthdate:</label>
								<div class='input-group date'>
									<input type="text" class="form-control birthdate" name="birthdate" disabled/>
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar">
										</span>
									</span>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<h3>Practice Information: </h3>
							<div class="col-md-12 form-group">
								<label>Firm Name:</label>
								<input class="form-control firm_name" name="firm_name" disabled/>
							</div>
							<div class="col-md-12 form-group">
								<label>Description:</label>
								<textarea class="form-control firm_desc" name="description" disabled></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger submitDecline" data-id="">Decline</button>
					<a href="#" class="btn btn-default" data-dismiss="modal">Cancel</a>
				</div>
			</div>
		</div>
	</div>
</div> <!-- /container -->
@endsection

@section('script')
<script type="text/javascript">
	var table = $('#table').DataTable({
		"order": [[ 1, "asc" ], [ 0, "asc" ]],
		"columnDefs": [
		@if($section == 'customers' || $section == 'all')
			{ "searchable": false, "orderable": false, "targets": 5 },
		@else
			{ "searchable": false, "orderable": false, "targets": 4 },
		@endif
		]
	});

	$(".dataTables_length").prepend('<a href="#" style="margin-right:2em" class="btn btn-success btn-m new" data-toggle="modal" data-target="#new"><span class="fa fa-plus"></span> New</a>');

	$(document).on('click', '.new', function(e){
		e.preventDefault();

		$("#new .role").val($("#new .role option:first").val());
		$("#new .first_name").val(null);
		$("#new .last_name").val(null);
		$("#new .password").val(null);
		$("#new .password_confirmation").val(null);
		$("#new .email").val(null);
		$("#new .birthdate").val(null);
	});

	$(document).on('click', '.submitNew', function(e){
		e.preventDefault();
		var target = $("#new .modal-body")[0];
		var spinner = new Spinner(spinner_option).spin(target);

		var $this = $(this);
		var role 					= $("#new .role").val(),
			first_name 				= $("#new .first_name").val(),
			last_name 				= $("#new .last_name").val(),
			password 				= $("#new .password").val(),
			password_confirmation 	= $("#new .password_confirmation").val(),
			email 					= $("#new .email").val(),
			birthdate 				= $("#new .birthdate").val();

		$.post('{{ URL::route('postNewUser') }}', {
			role : role,
			first_name : first_name,
			last_name : last_name,
			password : password,
			password_confirmation : password_confirmation,
			email : email,
			birthdate : birthdate,
		}).done( function(obj) {
			console.log(obj);

			spinner.stop();
			if(obj.flash.type == false) {
				errorsHtml = "";
				$.each( obj.flash.message , function( key, value ) {
					errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
				});

				displayNotifit( errorsHtml , obj.flash.type );
			} else {
				table.row.add([
					obj.data.first_name + ' ' + obj.data.last_name,
					obj.data.role,
					obj.data.email,
					obj.data.home_number ? obj.data.home_number : '',
					'',
					'<a href="{{ URL::route('user') }}/'+obj.data.id+'" class="btn btn-primary btn-xs"><span class="fa fa-edit"></span> Edit</a> ' +
					'<a href="#" class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#delete" data-id="'+obj.data.id+'"><span class="fa fa-remove"></span> Delete</a>'
				]).draw();


				$this.closest('.modal').modal('hide');
				displayNotifit( obj.flash.message , obj.flash.type );

				if(obj.data.role != 'admin') {
					window.setTimeout(function() {
						window.location.href = '{{ URL::route('user') }}/'+obj.data.id;
					}, 2000);
				}
			}
		}).fail( function(obj){
			displayNotifit( "Sorry! Failed to save." , false );
		});
	});

	var clicked_tr_edit = null;
	$(document).on('click', '.edit', function(e){
		e.preventDefault();
		var target = $("#edit .modal-body")[0];
		var spinner = new Spinner(spinner_option).spin(target);

		var id = $(this).data('id');
		clicked_tr_edit = $(this).parents('tr');

		$.get('{{ URL::route('getUser') }}', {id: id}).done( function(data){
			$("#edit .first_name").val(data.first_name);
			$("#edit .last_name").val(data.last_name);
			$("#edit .email").val(data.email);
			var birthdate = new Date(data.birthdate);
				birthdate = (birthdate.getMonth() + 1) + '/' + birthdate.getDate() + '/' +  birthdate.getFullYear();
			$("#edit .birthdate").val(birthdate);

			$("#edit .submitEdit").data('id', id);
			spinner.stop();
		});
	});

	$(document).on('click', '.submitEdit', function(e){
		e.preventDefault();
		var target = $("#edit .modal-body")[0];
		var spinner = new Spinner(spinner_option).spin(target);

		var $this = $(this);
		var id 						= $("#edit .submitEdit").data('id'),
			first_name 				= $("#edit .first_name").val(),
			last_name 				= $("#edit .last_name").val(),
			email 					= $("#edit .email").val(),
			birthdate 				= $("#edit .birthdate").val(),
			password 				= $("#edit .password").val(),
			password_confirmation 	= $("#edit .password_confirmation").val();

		$.post('{{ URL::route('postEditUser') }}', {
			id 						: id,
			first_name 				: first_name,
			last_name 				: last_name,
			password 				: password,
			password_confirmation 	: password_confirmation,
			email 					: email,
			birthdate 				: birthdate,
		}).done( function(obj) {
			console.log(obj);

			spinner.stop();
			if(obj.flash.type == false) {
				errorsHtml = "";
				$.each( obj.flash.message , function( key, value ) {
					errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
				});

				displayNotifit( errorsHtml , obj.flash.type );
			} else {
				clicked_tr_edit.find('td').eq(0).html(obj.data.first_name + ' ' + obj.data.last_name);
	        	clicked_tr_edit.find('td').eq(2).html(obj.data.email);

				$this.closest('.modal').modal('hide');
				displayNotifit( obj.flash.message , obj.flash.type );

				if(obj.data.role != 'admin') {
					window.setTimeout(function() {
						window.location.href = '{{ URL::route('user') }}/'+obj.data.id;
					}, 2000);
				}
			}
		}).fail( function(obj){
			displayNotifit( "Sorry! Failed to save." , false );
		});
	});


	var clicked_tr_delete = null;
	$(document).on('click', '.delete', function(e){
		e.preventDefault();
		var target = $("#delete .modal-body")[0];
		var spinner = new Spinner(spinner_option).spin(target);

		var id = $(this).data('id');
		clicked_tr_delete = $(this).parents('tr');

		$.get('{{ URL::route('getUser') }}', {id: id}).done( function(data){
			$("#delete .role").val(data.role);
			$("#delete .first_name").val(data.first_name);
			$("#delete .last_name").val(data.last_name);
			$("#delete .email").val(data.email);
			var birthdate = new Date(data.birthdate);
				birthdate = (birthdate.getMonth() + 1) + '/' + birthdate.getDate() + '/' +  birthdate.getFullYear();
			$("#delete .birthdate").val(birthdate);

			$("#delete .submitDelete").data('id', id);
			spinner.stop();
		});
	});


	$(document).on('click', '.submitDelete', function(e){
		e.preventDefault();
		var target = $("#edit .modal-body")[0];
		var spinner = new Spinner(spinner_option).spin(target);

		var $this = $(this);
		var id 			= $("#delete .submitDelete").data('id');

		$.post('{{ URL::route('deleteUser') }}', {id: id}).done( function(obj){
			table.row( clicked_tr_delete ).remove().draw();

			spinner.stop();
			$this.closest('.modal').modal('hide');
			displayNotifit( obj.flash.message , obj.flash.type );
		});
	});


	// APPROVE LAWYER
	var clicked_tr_approve = null;
	$(document).on('click', '.approve', function(e){
		e.preventDefault();
		var target = $("#approve .modal-body")[0];
		var spinner = new Spinner(spinner_option).spin(target);

		var id = $(this).data('id');
		clicked_tr_approve = $(this).parents('tr');

		$.get('{{ URL::route('user') }}', {id: id}).done( function(data){
			$("#approve .first_name").val(data.user.first_name);
			$("#approve .last_name").val(data.user.last_name);
			$("#approve .email").val(data.user.email);
			$("#approve .birthdate").val(data.user.birthdate);
			$("#approve .firm_name").val(data.profile.firm_name);
			$("#approve .firm_desc").val(data.profile.firm_desc);

			$("#approve .submitApprove").data('id', id);
			spinner.stop();
		});
	});


	$(document).on('click', '.submitApprove', function(e){
		e.preventDefault();
		var target = $("#approve .modal-body")[0];
		var spinner = new Spinner(spinner_option).spin(target);

		var $this = $(this);
		var id 			= $("#approve .submitApprove").data('id');

		$.post('{{ URL::route('postLawyerApprove') }}', {id: id}).done( function(obj){
			table.row( clicked_tr_approve ).remove().draw();

			spinner.stop();
			$this.closest('.modal').modal('hide');
			displayNotifit( obj.flash.message , obj.flash.type );
		});
	});


	// DECLINE LAWYER
	var clicked_tr_decline = null;
	$(document).on('click', '.decline', function(e){
		e.preventDefault();
		var target = $("#decline .modal-body")[0];
		var spinner = new Spinner(spinner_option).spin(target);

		var id = $(this).data('id');
		clicked_tr_decline = $(this).parents('tr');

		$.get('{{ URL::route('user') }}', {id: id}).done( function(data){
			$("#decline .first_name").val(data.user.first_name);
			$("#decline .last_name").val(data.user.last_name);
			$("#decline .email").val(data.user.email);
			$("#decline .birthdate").val(data.user.birthdate);
			$("#decline .firm_name").val(data.profile.firm_name);
			$("#decline .firm_desc").val(data.profile.firm_desc);

			$("#decline .submitDecline").data('id', id);
			spinner.stop();
		});
	});


	$(document).on('click', '.submitDecline', function(e){
		e.preventDefault();
		var target = $("#decline .modal-body")[0];
		var spinner = new Spinner(spinner_option).spin(target);

		var $this = $(this);
		var id 			= $("#decline .submitDecline").data('id');

		$.post('{{ URL::route('postLawyerDecline') }}', {id: id}).done( function(obj){
			table.row( clicked_tr_decline ).remove().draw();

			spinner.stop();
			$this.closest('.modal').modal('hide');
			displayNotifit( obj.flash.message , obj.flash.type );
		});
	});
</script>
@endsection