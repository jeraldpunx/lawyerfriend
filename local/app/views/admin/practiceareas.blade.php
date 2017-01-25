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
						<a href="{{ URL::route('practiceareas') }}" class="list-group-item active">
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
				<h3>Practice Areas</h3>

				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Form</th>
							<th>Rate</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($practiceareas as $practicearea)
						<tr>
							<td>{{ $practicearea->name }}</td>
							<td>{{ $practicearea->form->name }}</td>
							<td>{{ $practicearea->rate }}</td>
							<td>
								<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#edit" data-id="{{ $practicearea->id }}"><span class="fa fa-edit"></span> Edit</a>
	                            <a href="#" class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#delete" data-id="{{ $practicearea->id }}"><span class="fa fa-remove"></span> Delete</a>
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
                        <label>Name:</label>
                        <input class="form-control name" name="name" required="required"/>
                    </div>
                    <div class="form-group">
                        <label>Form:</label>
                        <select class="selectize form_id" name="form_id" required>
						@foreach($forms as $form)
							<option value="{{ $form['id'] }}">{{ $form['name'] }}</option>
						@endforeach
						</select>
                    </div>
                    <div class="form-group">
                        <label>Rate (Please input only numbers. No symbols.):</label>
                        <input class="form-control rate" name="rate" required="required"/>
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
					<div class="form-group">
                        <label>Name:</label>
                        <input class="form-control name" name="name" disabled/>
                    </div>
                    <div class="form-group">
                        <label>Form:</label>
                        <select class="form-control form_id" name="form_id" disabled>
						@foreach($forms as $form)
							<option value="{{ $form['id'] }}">{{ $form['name'] }}</option>
						@endforeach
						</select>
                    </div>
				</div>
				<div class="form-group">
                    <label>Rate:</label>
					<input class="form-control rate" name="rate" disabled/>
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

	$(".dataTables_length").prepend('<a href="#" style="margin-right:2em" class="btn btn-success btn-m new" data-toggle="modal" data-target="#edit"><span class="fa fa-plus"></span> New</a>');

	$(document).on('click', '.new', function(e){
	    e.preventDefault();
	    
	    $("#edit .modal-title").html("New");
	    $("#edit .name").val(null);
		$("#edit .form_id").val($("#edit .form_id option:first").val());
		$("#edit .rate").val(null);

	    $("#edit .submitEdit").data('id', null);
	    clicked_tr_edit = null;
	});

	var clicked_tr_edit = null;
	$(document).on('click', '.edit', function(e){
	    e.preventDefault();
	    var target = $("#edit .modal-body")[0];
	    var spinner = new Spinner(spinner_option).spin(target);

	    $("#edit .modal-title").html("Edit");

	    var id = $(this).data('id');
	    clicked_tr_edit = $(this).parent().parent();

	    $.get('{{ URL::route('getPracticeArea') }}', {id: id}).done( function(data){
	    	$("#edit .name").val(data.name);
	    	$("#edit .form_id").val(data.form_id);
	    	$("#edit .rate").val(data.rate);

	        $("#edit .submitEdit").data('id', id);
	        spinner.stop();
	    }).fail( function(obj){
    		displayNotifit( "Sorry! Failed to get details." , false );
    		spinner.stop();
    	});
	});

	$(document).on('click', '.submitEdit', function(e){
        e.preventDefault();
        var target = $("#edit .modal-body")[0];
	    var spinner = new Spinner(spinner_option).spin(target);

        var $this = $(this);
        var id 			= $("#edit .submitEdit").data('id'),
			name 		= $("#edit .name").val(),
			form_id 	= $("#edit .form_id").val(),
			rate 		= $("#edit .rate").val();

        $.post('{{ URL::route('postPracticeArea') }}', {id: id, name: name, form_id: form_id, rate: rate}).done( function(obj){
        	if(obj.flash.type == true) { // if validation success
	    		if(clicked_tr_edit) {
		        	clicked_tr_edit.find('td').eq(0).html(obj.data.practicearea.name);
		        	clicked_tr_edit.find('td').eq(1).html(obj.data.form.name);
		        	clicked_tr_edit.find('td').eq(2).html(obj.data.rate);
		        } else {
					table.row.add([
				        obj.data.practicearea.name,
				        obj.data.form.name,
				        obj.data.rate,
				        '<a href="#" class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#edit" data-id="'+obj.data.practicearea.id+'"><span class="fa fa-edit"></span> Edit</a> ' +
	                    '<a href="#" class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#delete" data-id="'+obj.data.practicearea.id+'"><span class="fa fa-remove"></span> Delete</a>'
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

	    $.get('{{ URL::route('getPracticeArea') }}', {id: id}).done( function(data){
	    	$("#delete .name").val(data.name);
	    	$("#delete .form_id").val(data.form_id);
	    	$("#delete .rate").val(data.rate);

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

        $.post('{{ URL::route('deletePracticeArea') }}', {id: id}).done( function(obj){
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