@extends('layouts.master')

@section('content')
	
	<div class='row  div-panel'>
		<div class='full div-panel-header'>
		    <div class='col-xs-12 col-sm-4 col-md-5 col-lg-6'>	    	
		    	<h4 class='title-panel'>{!! $page['item_title'] !!}</h4>
		    </div>

		    <div class='col-xs-12 col-sm-8 col-md-7 col-lg-6 xs-left-sm-right'>
		    	<a class='btn btn-type-a first'><i class='fa fa-plus-circle'></i> Add...</a>
		    	<a class='btn btn-type-a'><i class='fa fa fa-clock-o'></i> Log Time</a>
		    	<a class='btn btn-type-a'><i class='fa fa fa-play'></i> Start Timer</a>
		    	<a class='btn thin btn-type-a'><i class='fa fa-ellipsis-v fa-sm'></i></a>
		    </div>
		</div> <!-- end full -->

		<div class='full'>
			<ul class='menu-h'>
				<li class='active'><a href='#'>Overview</a></li>
				<li><a href='#'>Tasks</a></li>
				<li><a href='#'>Issues</a></li>
				<li><a href='#'>Milestones</a></li>
				<li><a href='#'>Time</a></li>
				<li><a href='#'>Billing</a></li>
				<li><a href='#'>Risks</a></li>
				<li><a href='#'>Comments</a></li>
				<li><a href='#'>Notebook</a></li>
				<li><a href='#'>Files</a></li>
				<li><a href='#'>Reports</a></li>
				<li><a href='#'>Members</a></li>
				<li><a href='#'>Settings</a></li>
			</ul> <!-- end menu-h -->
		</div> <!-- end full -->	
		    
	</div> <!-- end row -->    

@endsection