@extends('layout')


@section('content')
<div class="container">
	<h1>Find A Lawyer</h1>
	<div class="panel wizard">
		<div class="panel-body">
			<div class="steps col-md-2">
				<ul>
					<li class="first">
						<a href="{{ URL::route('find-a-lawyer', ['page'=>1, 'practice_area'=>Input::get('practice_area'), 'state'=>Input::get('state'), 'city'=>Input::get('city'), 'contact'=>Input::get('contact')]) }}">1. Basic Information</a>
					</li>
					<li class="">
						<a href="{{ URL::route('find-a-lawyer', ['page'=>2, 'practice_area'=>Input::get('practice_area'), 'state'=>Input::get('state'), 'city'=>Input::get('city'), 'contact'=>Input::get('contact')]) }}">2. Details</a>
					</li>
					<li class="">
						<a href="{{ URL::route('find-a-lawyer', ['page'=>3, 'practice_area'=>Input::get('practice_area'), 'state'=>Input::get('state'), 'city'=>Input::get('city'), 'contact'=>Input::get('contact')]) }}">3. View Rates</a>
					</li>
					<li class="current last">
						<a href="#">4. Matched Lawyers</a>
					</li>
				</ul>
			</div>
			<div class="content col-md-10">
				<h4>Matched Lawyers</h4>
				@if($lawyers)
					@foreach($lawyers as $lawyer)
						@if($lawyer['lawyer']['lawyer_active'] == 1)
						<section class="lawyer" onclick="window.location='{{ URL::route('lawyerView', $lawyer['lawyer']['user_id']) }}';">
							<div class="row">
								<div class="col-md-3">
									<div class="row">
										<div class="col-md-10">
											<a href="">
												<img src="{{ asset('uploads/'.$lawyer['lawyer']['user']['image']) }}">
											</a>
										</div>
									</div>
								</div>

								<div class="col-md-9">
									<div>
										<h3 class="text-primary">{{ $lawyer['lawyer']['user']['first_name'] }} <em>{{ $lawyer['lawyer']['user']['last_name'] }}</em></h3>

										<p><b>Firm Name: </b></p>
										<p>{{ $lawyer['lawyer']['firm_name'] }}</p>

										<ul class="list-inline">
							            	<?php 
							            		$practice_areas 	= LawyerProfile::practice_areas($lawyer['lawyer_id'])->toArray();
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
		                            	
		                            	<p><b>Description: </b></p>
		                            	<p>{{ $lawyer['lawyer']['firm_desc'] }}</p>
		                            </div>
								</div>
		                    </div>
						</section>
						<hr>
						@endif
					@endforeach
				@else
					<div class="text-center">
						<h4>No lawyers found. Please try another field.</h4>
					</div>
				@endif
			</div>

			<div class="actions col-md-12">
				<div class="pull-right">
					<a class="btn" href="{{ URL::route('find-a-lawyer', ['page'=>1]) }}">Search again</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
@endsection