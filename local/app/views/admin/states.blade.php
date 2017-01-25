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
						<a href="{{ URL::route('states') }}" class="list-group-item active">
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
				<h3>States</h3>

				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="table">
					<thead>
						<tr>
							<th>State</th>
							<th>Abbreviation</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($states as $state)
						<tr>
							<td>{{ $state->text }}</td>
							<td>{{ $state->abbr }}</td>
							<td>
								<a href="{{ URL::route('cities', $state->id) }}" class="btn btn-default btn-xs"><span class="fa fa-home"></span> Cities</a>
								<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#edit" data-id="{{ $state->id }}"><span class="fa fa-edit"></span> Edit</a>
	                            <a href="#" class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#delete" data-id="{{ $state->id }}"><span class="fa fa-remove"></span> Delete</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<!-- Modal EDIT-->
    <div class="modal fade" id="edit" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit</h4>
                </div>
                <div class="modal-body">
                	<div class="form-group">
                        <label>State:</label>
                        <input class="form-control text" name="text" required="required"/>
                    </div>
                    <div class="form-group">
                        <label>Abbreviation:</label>
                        <input class="form-control abbr" name="abbr" required="required"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success submitEdit" data-id="">Submit</button>
                    <a href="#" class="btn btn-default" data-dismiss="modal">Cancel</a>
            	</div>
            </div>
        </div>
    </div>
    <!-- END of Modal EDIT-->

	<!-- DELETE Modal -->
	<div class="modal fade" id="delete" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Are you sure you want to delete this?</h4>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning">
						<strong>Reminder!</strong> This will also delete cities inside of it.
					</div>

					<div class="form-group">
                        <label>State:</label>
                        <input class="form-control text" name="text" disabled/>
                    </div>
                    <div class="form-group">
                        <label>Abbreviation:</label>
						<input class="form-control abbr" name="abbr" disabled/>
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


	$(".dataTables_length").prepend('<a href="#" data-toggle="modal" data-target="#edit" style="margin-right:2em" class="btn btn-success btn-m new"><span class="fa fa-plus"></span> New</a>');



	var clicked_tr_edit = null;
	$(document).on('click', '.new', function(e){
	    e.preventDefault();
	    
	    $("#edit .modal-title").html("New");
	    $("#edit .text").val(null);
	    $("#edit .abbr").val(null);
	    $("#edit .submitEdit").data('id', null);
	    clicked_tr_edit = null;
	});

	$(document).on('click', '.edit', function(e){
	    e.preventDefault();
	    var target = $("#edit .modal-body")[0];
	    var spinner = new Spinner(spinner_option).spin(target);

	    $("#edit .modal-title").html("Edit");

	    var id = $(this).data('id');
	    clicked_tr_edit = $(this).parent().parent();

	    $.get('{{ URL::route('getState') }}', {id: id}).done( function(data){
	    	$("#edit .text").val(data.text);
		    $("#edit .abbr").val(data.abbr);

	        $("#edit .submitEdit").data('id', id);
	        spinner.stop();
	    }).fail( function(obj){
    		displayNotifit( "Sorry! Failed to get details." , false );
    	});
	});

	$(document).on('click', '.submitEdit', function(e){
        e.preventDefault();
        var target = $("#edit .modal-body")[0];
	    var spinner = new Spinner(spinner_option).spin(target);

        var $this = $(this);
        var id 			= $("#edit .submitEdit").data('id'),
			text 		= $("#edit .text").val(),
			abbr 		= $("#edit .abbr").val();

        $.post('{{ URL::route('postState') }}', {id: id, state: text, abbreviation: abbr}).done( function(obj){
        	if(obj.flash.type == true) { // if validation success
	    		if(clicked_tr_edit) {
		        	clicked_tr_edit.find('td').eq(0).html(obj.data.text);
		        	clicked_tr_edit.find('td').eq(1).html(obj.data.abbr);
		        } else {
					table.row.add([
				        obj.data.text,
				        obj.data.abbr,
				        '<a href="{{ URL::route("cities") }}/'+obj.data.id+'" class="btn btn-default btn-xs"><span class="fa fa-home"></span> Cities</a> ' +
				        '<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#edit" data-id="'+obj.data.id+'"><span class="fa fa-edit"></span> Edit</a> ' +
	                    '<a href="#" class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#delete" data-id="'+obj.data.id+'"><span class="fa fa-remove"></span> Delete</a>'
				    ]).draw();
		        }
	            $this.closest('.modal').modal('hide');
		    }

    		spinner.stop();
            displayNotifit( obj.flash.message , obj.flash.type );
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

	    $.get('{{ URL::route('getState') }}', {id: id}).done( function(data){
	    	$("#delete .text").val(data.text);
		    $("#delete .abbr").val(data.abbr);

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

        $.post('{{ URL::route('deleteState') }}', {id: id}).done( function(obj){
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