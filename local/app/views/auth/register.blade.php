@extends('layout')

@section('content')
<div class="container">
	<div class="row">
		<center>
			<h3>Registration</h3>
			<div class="register_button top30">
				<a href="{{ URL::route('register_user') }}" class="btn btn-primary">I <b>need</b> a lawyer</a>    
				<a href="{{ URL::route('register_lawyer') }}" class="btn btn-primary">I <b>am</b> a lawyer</a>
			</div>
		</center>
	</div>
</div>
@endsection