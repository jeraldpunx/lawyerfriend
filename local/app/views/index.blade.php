@extends('layout')

@section('content')
    <div id="masthead">
    	<div class="container">
    		<div class="col-md-4">
    			<h3>The VERY BEST PLACE place for fixed prices on all of your legal needs</h3>
    			<ul class="checked">
					<li>Earn points via social sharing</li>
					<li>Increase friendship level</li>
					<li>Lower your legal rates!</li>
					<li>Browse for lawyers at fixed prices</li>
				</ul>
    		</div>
    		<div class="col-md-8">
    			<div class="embed-responsive embed-responsive-16by9">
				  <iframe class="embed-responsive-item" allowfullscreen="" src="https://www.youtube.com/embed/8tPnX7OPo0Q"></iframe>
				</div>
    		</div>
    	</div>
    </div>

    <div class="container">
    	<section id="touts" class="clearfix">
	    	<div class="col-md-4">
	    		<center>
	    			<i class="text-success fa fa-share-alt"></i>
		    		<p>Earn points by social sharing</p>
		    	</center>
	    	</div>
	    	<div class="col-md-4">
	    		<center>
	    			<i class="text-success fa fa-user-plus"></i>
	    			<p>Increase friendship level</p>
	    		</center>
	    	</div>
	    	<div class="col-md-4">
	    		<center>
	    			<i class="text-success fa fa-arrow-down"></i>
	    			<p>Lower your legal rates!</p>
	    		</center>
			</div>
	    </section>
	    <section class="home clearfix">
	    	<div class="col-md-6">
	    		<h4>Find a lawyer tailored to your legal needs!</h4>
	    		<p>No matter what legal area you need assistance with, Lawyer Friend will help you find the best possible attorneys at the lowest possible rates.</p>
	    		<ul class="checked">
					<li>Earn points via social sharing</li>
					<li>Increase friendship level</li>
					<li>Lower your legal rates!</li>
					<li>Browse for lawyers at fixed prices</li>
				</ul>
				<center><a class="btn btn-success" href="{{ URL::route('register') }}">Provide Legal Services</a></center>
	    	</div>
	    	<div class="col-md-6">
	    		<div class="embed-responsive embed-responsive-16by9">
				  <iframe class="embed-responsive-item" allowfullscreen="" src="https://www.youtube.com/embed/8tPnX7OPo0Q"></iframe>
				</div>
	    	</div>
	    </section>
	    <section class="home">
	    	<center><h2>The more you <b class="text-success">share</b>, <br> the more you <b class="text-success">save</b>.</h2></center>
	    </section>
	    <section class="home">
	    	<div class="col-md-6">
	    		<div class="embed-responsive embed-responsive-16by9">
				  <iframe class="embed-responsive-item" allowfullscreen="" src="https://www.youtube.com/embed/8tPnX7OPo0Q"></iframe>
				</div>
	    	</div>
	    	<div class="col-md-6">
	    		<h4>Get in front of the eyes that matter</h4>
	    		<p>Discover potential clients that are actively seeking representation & looking to build relationships, and who are eager and willing to share valuable content of yours!</p>
	    		<ul class="checked">
					<li>Select practice areas and locations served</li>
					<li>Get in front of highly targeted customers</li>
					<li>Collect helpful information about each inquiry to better serve your customers</li>
					<li>Real time bidding marketplace / free market</li>
					<!-- <li>Relgic customers earn points by sharing you create!</li> -->
				</ul>
				<center><a class="btn btn-success" href="{{ URL::route('register') }}">Provide Legal Services</a></center>
	    	</div>
	    </section>
    </div>
@endsection