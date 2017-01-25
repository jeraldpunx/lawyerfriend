@extends('layout')


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<div class="row">
				<div class="sidebar-offcanvas">
					<div class="list-group">
						<a href="{{ URL::route('users') }}" class="list-group-item">
							<i class="fa fa-users"></i> Users
						</a>
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
						<a href="{{ URL::route('forms') }}" class="list-group-item active">
							<i class="fa fa-file-o"></i> Forms
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-9">
			<div class="col-md-12">
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

				<h3>Forms</h3>

				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Form Title</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($forms as $form)
						<tr>
							<td>{{ $form->name }}</td>
							<td>{{ $form->title }}</td>
							<td>
								<a href="{{ URL::route('getForm', $form->id) }}" class="btn btn-primary btn-xs edit"><span class="fa fa-edit"></span> Edit</a>
	                            <a href="#" class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#delete" data-id="{{ $form->id }}"><span class="fa fa-remove"></span> Delete</a>
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
					<h4 class="modal-title">Are you sure you want to delete this?</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
                        <label>Form Name:</label>
                        <input class="form-control name" name="name" disabled/>
                    </div>
                    <div class="form-group">
                        <label>Title:</label>
						<input class="form-control title" name="title" disabled/>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
						<input class="form-control description" name="description" disabled/>
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
		  { "searchable": false, "orderable": false, "targets": 2 },
		]
	});

	$(".dataTables_length").prepend('<a href="{{ URL::route("getForm") }}" style="margin-right:2em" class="btn btn-success btn-m"><span class="fa fa-plus"></span> New</a>');

	var clicked_tr_delete = null;
	$(document).on('click', '.delete', function(e){
	    e.preventDefault();
	    var target = $("#delete .modal-body")[0];
	    var spinner = new Spinner(spinner_option).spin(target);

	    var id = $(this).data('id');
	    clicked_tr_delete = $(this).parents('tr');

	    $.get('{{ URL::route('getForm') }}', {id: id}).done( function(data){
	    	console.log(data);
	    	$("#delete .name").val(data.name);
		    $("#delete .title").val(data.title);
			$("#delete .description").val(data.description);

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

        $.post('{{ URL::route('deleteForm') }}', {id: id}).done( function(obj){
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