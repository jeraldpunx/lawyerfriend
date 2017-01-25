@extends('layout')


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<div class="row">
				<div class="sidebar-offcanvas">
					<div class="list-group">
						<a href="{{ URL::route('profile/personal') }}" class="list-group-item">
							<i class="fa fa-user"></i> Profile
						</a>
						<a href="{{ URL::route('contacts') }}" class="list-group-item active">
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
				<h3>Contacts</h3>
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Home Number</th>
							<th>Email</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($contacts as $contact)
						<tr>
							<td>{{ $contact->contact_detail->first_name . " " . $contact->contact_detail->last_name }}</td>
							<td>{{ $contact->contact_detail->home_number }}</td>
							<td>{{ $contact->contact_detail->email }}</td>
							<td>
	                            <a href="#" class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#delete" data-id="{{ $contact->id }}"><span class="fa fa-remove"></span> Remove</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<!-- DELETE Modal -->
	<div class="modal fade" id="delete" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Are you sure you want to remove this contact?</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
                        <label>Name:</label>
                        <input class="form-control name" name="name" disabled/>
                    </div>

                    <div class="form-group">
                        <label>Home Number:</label>
                        <input class="form-control home_number" name="home_number" disabled/>
                    </div>

                    <div class="form-group">
                        <label>Email:</label>
                        <input class="form-control email" name="email" disabled/>
                    </div>
				</div>
				<div class="modal-footer">
                    <a href="#" class="btn btn-danger submitDelete">Delete</a>
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
		"order": [[ 0, "asc" ]],
		"columnDefs": [
		  { "searchable": false, "orderable": false, "targets": 3 },
		]
	});

	var clicked_tr_delete = null;
	$(document).on('click', '.delete', function(e){
	    e.preventDefault();
	    var target = $("#delete .modal-body")[0];
	    var spinner = new Spinner(spinner_option).spin(target);

	    var id = $(this).data('id');
	    clicked_tr_delete = $(this).parents('tr');

	    $.get('{{ URL::route('getContact') }}', {id: id}).done( function(data){
	    	console.log(data);
	    	$("#delete .name").val(data.contact_detail.first_name + " " + data.contact_detail.last_name);
			$("#delete .home_number").val(data.contact_detail.home_number);
			$("#delete .email").val(data.contact_detail.email);

	        $("#delete .submitDelete").data('id', id);
	        spinner.stop();
	     }).fail( function(obj){
    		displayNotifit( "Sorry! Failed to get details." , false );
    	});
	});


	$(document).on('click', '.submitDelete', function(e){
		e.preventDefault();
        var target = $("#edit .modal-body")[0];
	    var spinner = new Spinner(spinner_option).spin(target);

        var $this = $(this);
        var id 			= $("#delete .submitDelete").data('id');

        $.post('{{ URL::route('deleteContact') }}', {id: id}).done( function(obj){
            table.row( clicked_tr_delete ).remove().draw();

            spinner.stop();
            $this.closest('.modal').modal('hide');
            displayNotifit( obj.flash.message , obj.flash.type );
        }).fail( function(obj){
    		displayNotifit( "Sorry! Failed to delete." , false );
    	});
	});
</script>
@endsection