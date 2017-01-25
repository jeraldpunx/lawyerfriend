@extends('layout')

@section('content')
	<div class="container">
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
		<div class="col-md-5">
    		<form action="{{ URL::route('mail', $user->activation_code) }}" method="POST">
		    	<h4>Send mail to {{ $user->first_name }} {{ $user->last_name }}</h4>
	    		<textarea name="body" class="form-control" required></textarea>
	    		<br>
	    		<input type="submit" class="pull-right btn btn-success" value="Send!">
	    	</form>
	   	</div>
	</div>
@endsection