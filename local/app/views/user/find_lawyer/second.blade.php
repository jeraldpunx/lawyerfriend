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
					<li class="current">
						<a href="{{ URL::route('find-a-lawyer', ['page'=>2, 'practice_area'=>Input::get('practice_area'), 'state'=>Input::get('state'), 'city'=>Input::get('city'), 'contact'=>Input::get('contact')]) }}">2. Details</a>
					</li>
					<li class="disabled">
						<a href="{{ URL::route('find-a-lawyer', ['page'=>3]) }}">3. View Rates</a>
					</li>
					<li class="disabled last">
						<a href="#">4. Matched Lawyers</a>
					</li>
				</ul>
			</div>
			<div class="content col-md-10">
			<form method="POST" action="{{ URL::route('find-a-lawyer-second', ['page'=>3, 'practice_area'=>Input::get('practice_area'), 'state'=>Input::get('state'), 'city'=>Input::get('city'), 'contact'=>Input::get('contact')]) }}">
				<?php 
					$custom_form = unserialize(Session::get('find_lawyer-custom_form')); 
					$custom_form = $custom_form['answer'];
				?>


				@foreach($forms as $form)
				<h4>{{ $form['title'] }}</h4>
				<p>{{ $form['description'] }}</p>

				<div class="col-md-6">
					@foreach($form['questions'] as $question)
						<?php 
							$options = $question['options'];
							$options = array_filter( explode(',',$options) );
						?>

					<div class="form-group">
						<input type="hidden" value="{{ $question['label'] }}" name="custom_form[label][{{ $question['id'] }}]" />
						<label>{{ $question['label'] }}</label>
						@if($question['type'] == 'text')
							<input type="text" placeholder="{{ $question['label'] }}" name="custom_form[answer][{{ $question['id'] }}]" class="form-control" required value="{{ $custom_form[$question['id']] }}" />
						@elseif($question['type'] == 'textarea')
							<textarea name="custom_form[answer][]" placeholder="{{ $question['label'] }}" class="form-control" required>{{ $custom_form[$question['id']] }}</textarea>
						@elseif($question['type'] == 'radio')
							<div class="input-group btn-group" data-toggle="buttons">
								@foreach($options as $option)
									<label class="btn btn-default {{ ($custom_form[$question['id']] == $option ? 'active' : '') }}">
										<input type="radio" name="custom_form[answer][{{ $question['id'] }}]" value="{{ $option }}" {{ ($custom_form[$question['id']] == $option ? 'checked="checked"' : '') }} required>{{ $option }}
									</label>
								@endforeach
							</div>
						@elseif($question['type'] == 'select')
							<select class="form-control" name="custom_form[answer][{{ $question['id'] }}]" required>
								<option value=""></option>
								@foreach($options as $option)
									<option {{ ($custom_form[$question['id']] == $option) ? 'selected="selected"' : '' }}>{{ $option }}</option>
								@endforeach
							</select>
						@endif
						
					</div>
					@endforeach
				</div>
				@endforeach
				<div class="col-md-6"></div>
			</div>

			<div class="actions col-md-12">
				<div class="pull-right">
					<a class="btn" href="{{ URL::route('find-a-lawyer', ['page'=>1]) }}">Previous</a>
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