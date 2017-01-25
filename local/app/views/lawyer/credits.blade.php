@extends('layout')


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<div class="row">
				<div class="sidebar-offcanvas">
					<div class="list-group list-group-root well">
						<a href="{{ URL::route('profile/personal') }}" class="list-group-item">
							<i class="fa fa-user"></i> Profile
						</a>
						<a href="{{ URL::route('contacts') }}" class="list-group-item">
							<i class="fa fa-group"></i> Contacts
						</a>
						<a href="{{ URL::route('setting') }}" class="list-group-item">
							<i class="fa fa-cog"></i> Settings
						</a>
						<div class="list-group">
							<a href="{{ URL::route('getAddCredits') }}" class="list-group-item active">
								<i class="fa fa-money"></i> Add Credits
							</a>
							<a href="{{ URL::route('getChangePassword') }}" class="list-group-item">
								<i class="fa fa-key"></i> Change Password
							</a>
					    </div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="panel-body">
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
						<h3>Add Credits</h3>
						<form role="form" method="POST" action="{{ URL::route('setting') }}">
							<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-8">
									<div>
										<h2>
											Balance: 
											<span>${{ $profile->credits }}</span> 
											<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add"><span class="fa fa-plus"></span> Add Credit Now</a>
										</h2>
									</div>
								</div>
							</div>
						</form>
					</div>

					<br class="top30"  />


					<div class="row">
						<div class="col-md-12">
							<h3>Add Credits</h3>
							<div class="col-md-12">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="table">
									<thead>
										<tr>
											<th>Name</th>
											<th>Role</th>
											<th>Email</th>
											<th>Phone</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<!-- Modal Edit-->
	<div class="modal fade" id="add" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" method="POST" action="{{ URL::route('postAddCredits') }}">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add Credits</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<ol>
									<li>is minimum of 5$ per transaction</li>
									<li>is minimum of 100$ per transaction</li>
								</ol>
							</div>
							<div class="col-md-2"></div>
							<div class="col-md-6 form-group">
								<label>Credit (USD):</label>
								<input class="form-control" name="credit" required="required"/>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">Submit</button>
						<a href="#" class="btn btn-default" data-dismiss="modal">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div> <!-- /container -->
@endsection	