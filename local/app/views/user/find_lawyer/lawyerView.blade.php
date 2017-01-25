@extends('layout')


@section('content')
<style type="text/css">
	.panel-body {
		padding: 30px;
	}
</style>

<div class="container">
	@if (Session::has('flash.message'))
		<div class="alert alert-{{Session::get('flash.type')}}">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('flash.message') }}
		</div>
	@endif
	<div class="col-md-9">
		<div class="panel">
			<div class="panel-body">
				<h3>Overview</h3>
				<div class="col-md-4">
					<img src="{{ asset('uploads/'.$lawyer['user']['image']) }}" style="float: left">
				</div>
				<div class="col-md-8">
					<h2>{{ $lawyer['user']['first_name'] }} {{ $lawyer['user']['last_name'] }}</h2>

					<p><b>Firm Name: </b></p>
					<p>{{ $lawyer['firm_name'] }}</p>

					<ul class="list-inline">
		            	<?php 
		            		$practice_areas 	= LawyerProfile::practice_areas($lawyer['id'])->toArray();
		            		$last_practice_id 	= 0;
		            	?>

		            	<p><b>Practice Area(s): </b></p>
		            	@foreach($practice_areas as $practice_area)
		            		@if($last_practice_id != $practice_area['practice_area_id'])
		            			<li>
		            				<p><i>{{ $practice_area['practice_area']['name'] }}</i></p>
		            			</li>
		            		@endif
		            		<ul>
	        					<li><p><span class="fa fa-location-arrow"></span> {{ $practice_area['street'] }}, {{ $practice_area['city']['name'] }}, {{ $practice_area['state']['text'] }}, {{ $practice_area['zip'] }}</p></li>
	        				</ul>
		            		<?php $last_practice_id = $practice_area['practice_area_id']; ?>
		            	@endforeach
					</ul>	
				</div>


	        	<div class="col-md-12" style="margin-top: 30px;">
					<div class="col-md-12">
						<p><strong>Firm Description:</strong></p>
						<p>{{ $lawyer['firm_desc'] }}</p>
					</div>

					<div class="col-md-6">
						<p><strong>Email:</strong> {{ $lawyer['user']['email'] }}</p>
					</div>
					<div class="col-md-6">
						<p><strong>Birthdate: </strong>{{ $lawyer['user']['birthdate'] }}</p>
					</div>
					<div class="col-md-6">
						<p><strong>Gender: </strong>{{ $lawyer['user']['gender'] }}</p>
					</div>
					<div class="col-md-6">
						<p><strong>Contact: (home)</strong> {{ $lawyer['user']['home_number'] }} - <strong>(mobile)</strong> {{ $lawyer['user']['mobile_number'] }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<form role="form" method="POST" action="{{ URL::route('lawyerView', $lawyer['user_id']) }}">
			<div class="panel">
				<div class="panel-body">
					<textarea class="form-control" placeholder="Briefly describe your case: " required="required" name="info"></textarea>
				</div>
			</div>
			<input type="submit" class="btn btn-success btn-block btn-raised" value="Send Request!" />
		</form>
	</div>
	
</div>
@endsection

@section('script')
@endsection