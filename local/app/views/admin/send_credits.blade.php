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
							<a href="{{ URL::route('users') }}" class="list-group-item">
								<i class="fa fa-list"></i> Manage
							</a>
							<a href="{{ URL::route('lawyers') }}" class="list-group-item active">
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
				<h3>Send Credits</h3>
			</div>
			<br class="top30"  />
			<div class="col-md-12">
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Credits</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
						<tr>
							<td>{{ $user->first_name . " " . $user->last_name }}</td>
							<td>{{ $user->email }}</td>
							<td>{{ $user->home_number }}</td>
							<td>${{ $user->credits }}</td>
							<td>
								<a href="#" class="btn btn-primary btn-xs sendCredit" data-toggle="modal" data-target="#sendCredit" data-id="{{ $user->id }}"><span class="fa fa-send"></span> Send Credit</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<!-- Modal sendCredit-->
	<div class="modal fade" id="sendCredit" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Send Credit</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 form-group">
							<label>Credit (USD):</label>
							<div class="input-group">
								<input class="form-control credit" name="credit" required="required"/>
								<span class="input-group-btn">
									<button type="submit" class="btn btn-success submitCredit" data-id="">Send</button>
								</span>
							</div>
						</div>
						<div class="col-md-6">
							<label>Current Credit:</label>
							<p>$200</p>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<h4>Recent Transaction</h4>
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="historytable">
								<thead>
									<tr>
										<th>Amount</th>
										<th>Date</th>
										<th>Type</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>50$</td>
										<td>June 10</td>
										<td>Receive</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
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
			{ "searchable": false, "orderable": false, "targets": 4 },
		]
	});

	var historytable = $('#historytable').DataTable();

	var clicked_tr_credit = null;
	$(document).on('click', '.sendCredit', function(e){
		e.preventDefault();
		var target = $("#sendCredit .modal-body")[0];
	    var spinner = new Spinner(spinner_option).spin(target);

	    var id = $(this).data('id');
		$("#sendCredit .submitCredit").data('id', id);
		clicked_tr_credit = $(this).parents('tr');

		historytable.clear().draw();

	    $.get('{{ URL::route('getLawyerTransHistory') }}', {id: id}).done( function(data){
	    	$.each( data.histories, function( key, value ) {
			  historytable.row.add([
			        value.credit,
			        value.date,
			        value.type
			    ]).draw();
			});
	    	
	        spinner.stop();
	    }).fail( function(obj){
    		displayNotifit( "Sorry! Failed to get details." , false );
    		spinner.stop();
    	});

		
	});

	$(document).on('click', '.submitCredit', function(e){
		e.preventDefault();
		var target = $("#edit .modal-body")[0];
		var spinner = new Spinner(spinner_option).spin(target);

		var $this = $(this);
		var id 					= $("#sendCredit .submitCredit").data('id'),
			credit 				= $("#sendCredit .credit").val();

		$.post('{{ URL::route('postSendCredits') }}', {
			id 					: id,
			credit 				: credit,
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
				$this.closest('.modal').modal('hide');
				displayNotifit( obj.flash.message , obj.flash.type );
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
</script>
@endsection