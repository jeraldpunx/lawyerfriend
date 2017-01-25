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
						<a href="{{ URL::route('friendlevels') }}" class="list-group-item active">
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
				<h3>Friend Levels</h3>

				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="table">
					<thead>
						<tr>
							<th>Name</th>
							<!-- <th>Rate</th> -->
							<th>Rate Percent</th>
							<th>Ponts Needed</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($friendlevels as $friendlevel)
						<tr>
							<td>{{ $friendlevel->name }}</td>
							<!-- <td>{{ $friendlevel->rate }}</td> -->
							<td>{{ number_format($friendlevel->rate_percent,2) }}</td>
							<td>{{ $friendlevel->points_needed }}</td>
							<td>
								<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#edit" data-id="{{ $friendlevel->id }}"><span class="fa fa-edit"></span> Edit</a>
	                            <a href="#" class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#delete" data-id="{{ $friendlevel->id }}"><span class="fa fa-remove"></span> Delete</a>
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
                    <h4 class="modal-title">Edit Friend Level</h4>
                </div>
                <div class="modal-body">
                	<div class="form-group">
                        <label>Name:</label>
                        <input class="form-control name" name="name" required="required"/>
                    </div>
                   <!--  <div class="form-group">
                        <label>Rate (Please input only numbers. No symbols.):</label>
                        <input class="form-control rate" name="rate" required="required"/>
                    </div> -->
                    <div class="form-group">
                        <label>Rate Percent (Please input only numbers. No symbols.):</label>
                        <input class="form-control rate_percent" name="rate_percent" required="required"/>
                    </div>
                    <div class="form-group">
                        <label>Points Needed (Please input only numbers. No symbols.):</label>
                        <input class="form-control points_needed" name="points_needed" required="required"/>
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
					<div class="form-group">
                        <label>Name:</label>
                        <input class="form-control name" name="name" disabled/>
                    </div>
                   <!--  <div class="form-group">
                        <label>Rate:</label>
						<input class="form-control rate" name="rate" disabled/>
                    </div> -->
                    <div class="form-group">
                        <label>Rate Percent:</label>
						<input class="form-control rate_percent" name="rate_percent" disabled/>
                    </div>
                    <div class="form-group">
                        <label>Points Needed:</label>
						<input class="form-control points_needed" name="points_needed" disabled/>
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
		"order": [[ 2, "asc" ]],
		"columnDefs": [
		  { "searchable": false, "orderable": false, "targets": 3 },
		]
	});


	$(".dataTables_length").prepend('<a href="#" data-toggle="modal" data-target="#edit" style="margin-right:2em" class="btn btn-success btn-m new"><span class="fa fa-plus"></span> New</a>');



	var clicked_tr_edit = null;
	$(document).on('click', '.new', function(e){
	    e.preventDefault();
	    
	    $("#edit .modal-title").html("New Friend Level");
	    $("#edit .name").val(null);
	    // $("#edit .rate").val(null);
	    $("#edit .rate_percent").val(null);
		$("#edit .points_needed").val(null);
	    $("#edit .submitEdit").data('id', null);
	    clicked_tr_edit = null;
	});

	$(document).on('click', '.edit', function(e){
	    e.preventDefault();
	    var target = $("#edit .modal-body")[0];
	    var spinner = new Spinner(spinner_option).spin(target);

	    $("#edit .modal-title").html("Edit Friend Level");

	    var id = $(this).data('id');
	    clicked_tr_edit = $(this).parent().parent();

	    $.get('{{ URL::route('getFriendLevel') }}', {id: id}).done( function(data){
	    	$("#edit .name").val(data.name);
		    // $("#edit .rate").val(data.rate);
		    $("#edit .rate_percent").val(data.rate_percent.toFixed(2));
			$("#edit .points_needed").val(data.points_needed);

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
			name 		= $("#edit .name").val(),
			// rate 		= $("#edit .rate").val(),
			rate_percent  = $("#edit .rate_percent").val(),
			points_needed = $("#edit .points_needed").val();

        $.post('{{ URL::route('postFriendLevel') }}', {id: id, name: name, /*rate: rate, */rate_percent: rate_percent, points_needed: points_needed}).done( function(obj){
        	if(obj.flash.type == true) { // if validation success
	    		if(clicked_tr_edit) {
		        	clicked_tr_edit.find('td').eq(0).html(obj.data.name);
		        	// clicked_tr_edit.find('td').eq(1).html(obj.data.rate);
		        	clicked_tr_edit.find('td').eq(1).html(obj.data.rate_percent);
		        	clicked_tr_edit.find('td').eq(2).html(obj.data.points_needed);
		        } else {
					table.row.add([
				        obj.data.name,
				        // obj.data.rate,
				        obj.data.rate_percent.toFixed(2),
				        obj.data.points_needed,
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

	    $.get('{{ URL::route('getFriendLevel') }}', {id: id}).done( function(data){
	    	$("#delete .name").val(data.name);
		    // $("#delete .rate").val(data.rate);
		    $("#delete .rate_percent").val(data.rate_percent.toFixed(2));
			$("#delete .points_needed").val(data.points_needed);

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

        $.post('{{ URL::route('deleteFriendLevel') }}', {id: id}).done( function(obj){
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