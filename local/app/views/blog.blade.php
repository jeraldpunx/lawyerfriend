@extends('layout')


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			@foreach($blogs as $blog)
			<article>
				<div class="panel panel-default">
					<div class="panel-body">
						<h3><a href="{{ URL::route('blogs', [$blog->id]) }}">{{ $blog->title }}</a></h3>
						<hr>
						{{ $blog->content }}

						<div class="share-menu">
							<a href="#" class="share-link" title="Share this to your friends">Share</a>
							@if(Auth::check() && Auth::user()->role == 'admin') | 
							<a href="{{ URL::route('getBlog', [$blog->id]) }}" title="Edit this blog">Edit</a>
							@endif
							<div class="share-tip" style="display: none;">
								share a link to gain points
								@if(Auth::check())
									<input type="text" onfocus="this.select();" onmouseup="return false;" value="{{ URL::route('blogs', [$blog->id, 'token'=>Auth::user()->remember_token]) }}" style="display:block; width:325px;">
								@else
									<input type="text" onfocus="this.select();" onmouseup="return false;" value="{{ URL::route('blogs', [$blog->id]) }}" style="display:block; width:325px;">
								@endif
								<a href="#" style="float:right" class="close-share-tip">close</a>
							</div>
						</div>
					</div>
				</div>
			</article>
			@endforeach

			@if(method_exists($blogs, 'links'))
			{{ $blogs->links() }}
			@endif
		</div>
	</div>
</div> <!-- /container -->
@endsection

@section('script')
<script type="text/javascript">
	$(document).on('click', '.share-link', function(e) {
		e.preventDefault();
		$(this).siblings('div.share-tip').show();
	}).on('click', '.close-share-tip', function(e){
		e.preventDefault();
		$(this).parents('div.share-tip').hide();
	});
</script>
@endsection
