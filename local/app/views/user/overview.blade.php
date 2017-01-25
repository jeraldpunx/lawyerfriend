@extends('layout')


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-2">
			<div class="">
				<div class="sidebar-offcanvas">
					<div class="list-group">
						<a href="{{ URL::route('overview') }}" class="list-group-item {{ Helper::setActive('overview') }}">
							<i class="fa fa-tasks"></i> Overview
						</a>
						<a href="{{ URL::route('profile/personal') }}" class="list-group-item {{ Helper::setActive('profile/personal') }}">
							<i class="fa fa-user"></i> Profile
						</a>
						<a href="{{ URL::route('setting') }}" class="list-group-item">
							<i class="fa fa-cog"></i> Settings
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-10">
			<div class="">
				<div class="panel panel-default">
					<div class="panel-body">
						<h3>Overview</h3>

						<div class="col-md-12">
							<?php
								$friend_level 	= customerProfile::getFriendLevelPoints(Auth::user()->id);
								$next_level 	= $friend_level->next_level ? $friend_level->next_level - $friend_level->prev_level : 0;
								$current_point 	= $profile->friend_points - $friend_level->prev_level;
							?>

							<div id="progress">
								<?php $first_friendlevel = true; ?>
								@foreach($friendlevels as $friendlevel)
									@if($first_friendlevel)
									<div class="node {{ (customerProfile::getFriendLevel(Auth::user()->id)->name == $friendlevel->name) ? 'state--active' : ''}}" style="width: 0%;">
									<?php $first_friendlevel = false; ?>
									@else
						        	<div class="node {{ (customerProfile::getFriendLevel(Auth::user()->id)->name == $friendlevel->name) ? 'state--active' : ''}}" style="width: {{ helper::percentage(1,$friendlevels->count() - 1) }};">
							        @endif
							            <a href="#" class="node-handle">
							                <div class="node-title">{{ $friendlevel->name }}</div>
							            </a>
							        </div>
						        @endforeach
							</div>

							<div class="col-md-12">
								<p class="pull-right">
									<span class="text-success">({{ $profile->friend_points }} points)</span> -
									{{ ( $next_level == 0) ? "You're on the max level" : ($friend_level->next_level - $profile->friend_points) . ' points until next level' }}
								</p>
							</div>

							<div class="col-md-6">
								<label><a href="{{ URL::route('profile/personal') }}">Personal Information</a> ({{ helper::fraction($progress['personal']['points'],$progress['personal']['numberOfTextfield']) }})</label>
								<div class="progress">
									<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['personal']['points'],$progress['personal']['numberOfTextfield']) }}"></div>
								</div>

								<label><a href="{{ URL::route('profile/contact') }}">Contact Information</a> ({{ helper::fraction($progress['contact']['points'],$progress['contact']['numberOfTextfield']) }})</label>
								<div class="progress">
									<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['contact']['points'],$progress['contact']['numberOfTextfield']) }}"></div>
								</div>

								<label><a href="{{ URL::route('profile/work') }}">Work Information</a> ({{ helper::fraction($progress['work']['points'],$progress['work']['numberOfTextfield']) }})</label>
								<div class="progress">
									<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['work']['points'],$progress['work']['numberOfTextfield']) }}"></div>
								</div>
							</div>

							<div class="col-md-6">
								<label><a href="{{ URL::route('profile/transportation') }}">Transportation Information</a> ({{ helper::fraction($progress['trans']['points'],$progress['trans']['numberOfTextfield']) }})</label>
								<div class="progress">
									<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['trans']['points'],$progress['trans']['numberOfTextfield']) }}"></div>
								</div>

								<label><a href="{{ URL::route('profile/criminal') }}">Criminal Information</a> ({{ helper::fraction($progress['crim']['points'],$progress['crim']['numberOfTextfield']) }})</label>
								<div class="progress">
									<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['crim']['points'],$progress['crim']['numberOfTextfield']) }}"></div>
								</div>

								<label><a href="{{ URL::route('profile/education') }}">Education Information</a> ({{ helper::fraction($progress['edu']['points'],$progress['edu']['numberOfTextfield']) }})</label>
								<div class="progress">
									<div class="progress-bar progress-bar-striped active" style="width:{{ helper::percentage($progress['edu']['points'],$progress['edu']['numberOfTextfield']) }}"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!-- /container -->
@endsection

@section('script')
@endsection