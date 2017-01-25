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
						<a href="{{ URL::route('bloglists') }}" class="list-group-item active">
							<i class="fa fa-group"></i> Blogs
						</a>
						<a href="{{ URL::route('practiceareas') }}" class="list-group-item">
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

				@if($blog)
					<form action="{{ URL::route('postBlog', $blog->id) }}" method="POST">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<a href="{{ URL::route('bloglists') }}" class="pull-left btn btn-danger"><i class="fa fa-arrow-left"></i> cancel</a>
								<button class="pull-right btn btn-success"><i class="fa fa-save"></i> Publish</button>
							</div>
							<div class="panel-body">
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="title" placeholder="Title" value="{{ $blog['title'] }}" required />
								</div>
								<div class="form-group">
									<textarea class="form-control" id="content" name="content" placeholder="Content">{{ $blog['content'] }}</textarea>
								</div>
							</div>
							<div class="panel-footer clearfix">
								<a href="{{ URL::route('bloglists') }}" class="pull-left btn btn-danger"><i class="fa fa-arrow-left"></i> cancel</a>
								<button class="pull-right btn btn-success"><i class="fa fa-save"></i> Publish</button>
							</div>
						</div>
					</form>
				@else
					<form action="{{ URL::route('postBlog') }}" method="POST">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<a href="{{ URL::route('bloglists') }}" class="pull-left btn btn-danger"><i class="fa fa-arrow-left"></i> cancel</a>
								<button class="pull-right btn btn-success"><i class="fa fa-save"></i> Publish</button>
							</div>
							<div class="panel-body">
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="title" placeholder="Title" value="{{ $blog['title'] }}" required />
								</div>
								<div class="form-group">
									<textarea class="form-control" id="content" name="content" placeholder="Content">{{ $blog['content'] }}</textarea>
								</div>
							</div>
							<div class="panel-footer clearfix">
								<a href="{{ URL::route('bloglists') }}" class="pull-left btn btn-danger"><i class="fa fa-arrow-left"></i> cancel</a>
								<button class="pull-right btn btn-success"><i class="fa fa-save"></i> Publish</button>
							</div>
						</div>
					</form>
				@endif
			</div>
		</div>
	</div>

</div> <!-- /container -->
@endsection

@section('script')
<script type="text/javascript">
	tinymce.init({
	  selector: '#content',
	  height: 500,
	  menubar:false,
	  statusbar: false,
	  plugins: "table pagebreak image link code",
	  toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent pagebreak | image link | code",
	  content_css: [
	    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
	    '//www.tinymce.com/css/codepen.min.css'
	  ]
	});
</script>
@endsection