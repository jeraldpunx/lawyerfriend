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
					<li class="current">
						<a href="{{ URL::route('find-a-lawyer', ['page'=>3, 'practice_area'=>Input::get('practice_area'), 'state'=>Input::get('state'), 'city'=>Input::get('city'), 'contact'=>Input::get('contact')]) }}">3. View Rates</a>
					</li>
					<li class="disabled last">
						<a href="#">4. Matched Lawyers</a>
					</li>
				</ul>
			</div>
			<div class="content col-md-10">
			<form method="POST" action="{{ URL::route('find-a-lawyer-third', ['practice_area'=>Input::get('practice_area'), 'state'=>Input::get('state'), 'city'=>Input::get('city'), 'contact'=>Input::get('contact')]) }}">
				<h4>Rates</h4>

				<ul class="col-md-4 divided">
					@foreach($friendlevels as $friendlevel)
						<li {{ (CustomerProfile::getFriendLevel(Auth::user()->id)->name == $friendlevel->name) ? 'class="bg--canvas-shade"' : '' }}> 
							{{ (CustomerProfile::getFriendLevel(Auth::user()->id)->name == $friendlevel->name) ? '<i class="fa fa-check text-success"></i>' : '' }} 
							{{ $friendlevel->name }} 
							<!-- <b class="pull-right">${{ CustomerProfile::getCurrentRateByCity($friendlevel->rate, Auth::user()->id) }}</b> -->
							<b class="pull-right">${{ CustomerProfile::getCurrentRateByCityAndPracticeArea(Input::get('practice_area'), Input::get('city'), $friendlevel->rate_percent) }}</b>
						</li>
					@endforeach
				</ul>

				<div class="col-md-8 text-center">
					<div class="panel panel-default rate-panel">
						<div class="panel-body">
							Your Rate
							<p class="bot0"><b>{{ CustomerProfile::getFriendLevel(Auth::user()->id)->name }}</b> starts at:</p>
							<!-- <h1 class="text-success top0"><b>${{ CustomerProfile::getCurrentRateByCity(CustomerProfile::getFriendLevel(Auth::user()->id)->rate, Auth::user()->id) }}</b></h1> -->
							<h1 class="text-success top0">
								<b>${{ CustomerProfile::getCurrentRateByCityAndPracticeArea(Input::get('practice_area'), Input::get('city'), CustomerProfile::getFriendLevel(Auth::user()->id)->rate_percent) }}</b>
							</h1>
						</div>
					</div>
				</div>
			</div>

			<div class="actions col-md-12">
				<div class="pull-right">
					<a class="btn" href="{{ URL::route('find-a-lawyer', ['page'=>2]) }}">Previous</a>
					<input type="submit" class="btn" value="Next" />
				</div>
			</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="http://fgnass.github.io/spin.js/spin.min.js"></script>
<script type="text/javascript">
	
</script>
@endsection