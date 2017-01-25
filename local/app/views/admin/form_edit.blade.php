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

				@if($form)
					<form action="{{ URL::route('postForm', $form->id) }}" method="POST">
						<div class="panel panel-default">
							<div class="panel-heading">
								<label>Form Name: </label>
								<div class="input-group">
				                    <input type="text" id="special" class="form-control" name="form_name" placeholder="Name" value="{{ $form['name'] }}" />
				                    <div class="input-group-btn">
				                    	<button type="submit" name="submit" value="save" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
				                        <button type="submit" name="submit" value="delete" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
				                    </div>
				                </div>
							</div>
							<div class="panel-body">
								<div class="form-group col-md-6">
									<label>Title</label>
									<input type="text" class="form-control" name="title" placeholder="Title" value="{{ $form['title'] }}" />
								</div>
								<div class="form-group col-md-off-6 col-md-12">
									<label>Description</label>
									<textarea class="form-control" name="description" placeholder="Description">{{ $form['description'] }}</textarea>
								</div>

								
								<div class="col-md-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">Fields</h4>
										</div>
										<div class="panel-body">
											<div class="fields">
												@foreach($form->questions as $question)
													<!-- {{ $question }} -->
													<div class="field col-md-6">
														<div class="panel panel-default">
															<div class="panel-heading clearfix">
																<a class="remove pull-right" href="#">
														          <i class="fa fa-times"></i>
														        </a>
															</div>
															<div class="panel-body">
																<select class="form-control field-type" name="customform[type][]" >
																	<option value="">Select a Type</option>
																	<option value="text" {{ ($question->type == "text") ? 'selected="selected"' : ''; }}>Input</option>
																	<option value="textarea" {{ ($question->type == "textarea") ? 'selected="selected"' : ''; }}>Textarea</option>
																	<option value="radio" {{ ($question->type == "radio") ? 'selected="selected"' : ''; }}>Yes/No</option>
																	<option value="select" {{ ($question->type == "select") ? 'selected="selected"' : ''; }}>Multiple Choice</option>
																</select>
																<input class="form-control" type="text" name="customform[label][]" placeholder="Label" value="{{ $question->label }}" />
																<input class="form-control {{ ($question->type == "select" || $question->type == "radio") ? '' : 'hide' }}" type="text" name="customform[option][]" placeholder="Ex. Male,Female" value="{{ $question->options }}" />
															</div>
														</div>
													</div>
												@endforeach
											</div>
											<a href="#" id="add_attribute" class="btn btn-default">+ Add attribute</a>

										</div>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<div class="input-group">
				                    <div class="input-group-btn">
				                        <button type="submit" name="submit" value="delete" class="btn btn-danger pull-right"><i class="fa fa-trash"></i> Delete</button>
				                    	<button type="submit" name="submit" value="save" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save</button>
				                    </div>
				                </div>
							</div>
						</div>
					</form>
				@else
					<form action="{{ URL::route('postForm') }}" method="POST">
						<div class="panel panel-default">
							<div class="panel-heading">
								<label>Form Name: </label>
								<div class="input-group">
				                    <input type="text" id="special" class="form-control" name="form_name" placeholder="Name" value="" required />
				                    <div class="input-group-btn">
				                    	<button type="submit" name="submit" value="save" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
				                    </div>
				                </div>
							</div>
							<div class="panel-body">
								<div class="form-group col-md-6">
									<label>Title</label>
									<input type="text" class="form-control" name="title" placeholder="Title" value="" required />
								</div>
								<div class="form-group col-md-off-6 col-md-12">
									<label>Description</label>
									<textarea class="form-control" name="description" placeholder="Description"></textarea>
								</div>

								
								<div class="col-md-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">Fields</h4>
										</div>
										<div class="panel-body">
											<div class="fields">
												<div class="field col-md-6">
													<div class="panel panel-default">
														<div class="panel-heading clearfix">
															<a class="remove pull-right" href="#">
													          <i class="fa fa-times"></i>
													        </a>
														</div>
														<div class="panel-body">
															<select class="form-control field-type" name="customform[type][]" required>
																<option value="">Select a Type</option>
																<option value="text">Input</option>
																<option value="textarea">Textarea</option>
																<option value="radio">Yes/No</option>
																<option value="select">Multiple choice</option>
															</select>
															<input class="form-control" type="text" name="customform[label][]" placeholder="Label" />
															<input class="form-control hide" type="text" name="customform[option][]" placeholder="Ex. Male,Female" />
														</div>
													</div>
												</div>
											</div>
											<a href="#" id="add_attribute" class="btn btn-default">+ Add attribute</a>

										</div>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<div class="input-group">
				                    <div class="input-group-btn">
				                    	<button type="submit" name="submit" value="save" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save</button>
				                    </div>
				                </div>
							</div>
						</div>
					</form>
				@endif
				<!-- CLONE -->
				<div class="clone-field hide">
					<div class="field col-md-6">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<a class="remove pull-right" href="#">
						          <i class="fa fa-times"></i>
						        </a>
							</div>
							<div class="panel-body">
								<select class="form-control field-type" name="customform[type][]" required>
									<option value="">Select a Type</option>
									<option value="text">Input</option>
									<option value="textarea">Textarea</option>
									<option value="radio">Yes/No</option>
									<option value="select">Multiple choice</option>
								</select>
								<input class="form-control" type="text" name="customform[label][]" placeholder="Label" />
								<input class="form-control hide" type="text" name="customform[option][]" placeholder="Ex. Male,Female" />
							</div>
						</div>
					</div>
				</div>
				<!--END OF CLONE -->
			</div>
		</div>
	</div>
</div> <!-- /container -->
@endsection

@section('script')
<script type="text/javascript">
	$(".container").on("click", "#add_attribute", function(e){
		e.stopPropagation();
		e.preventDefault();

		var append_att = $(".clone-field").html();

		$(".fields").append(append_att);
	}).on('click', '.remove', function(e) {
		e.stopPropagation();
		e.preventDefault();

	    $(this).closest('.field').remove();
	}).on('change', '.field-type', function(){
		var inputType = this.value;
		var panel = $(this).closest('.panel-body');

	    var inputs = panel.find('input');
	    inputs.last().val("");

	    if(inputType=='radio' || inputType=='select') {
	    	inputs.last().removeClass('hide');
	    } else {
	    	inputs.last().addClass('hide');
	    }
	});
</script>
@endsection