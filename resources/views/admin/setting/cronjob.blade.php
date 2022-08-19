@extends('layouts.master')

@section('content')

	<div class='row'>
		@include('partials.subnav.setting')
			
	    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-10 div-type-a'>
	        <h4 class='title-type-a'>Cron Job</h4>

	        {!! Form::open(['class' => 'form-type-b']) !!}
		        <div class='form-group'>
			        <label for='cron_token' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Cron Token</label>
		        	
		        	<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>	
		        		<div class='inline-input'>952xn5vatvrs30j7xa2nvcx9y75ak32xu</div>
		        		<a class='btn btn-primary status xs pointer'>Update Cron Token</a>
		        	</div>	
		        </div>	

	        	<div class='form-group'>
	        		<label for='cron_job_link' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Cron Job Link</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			<div class='inline-input'>{!! url('/cronjob?token=952xn5vatvrs30j7xa2nvcx9y75ak32xu') !!}</div>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='last_cron_job' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Last Cron Job Run</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			<div class='inline-input'>2018-07-03 00:00:00 am</div>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='execution_interval' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Execution Interval</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			<div class='inline-input'>Every 1 hour</div>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='cron_job_command' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>cPanel Command <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			<div class='inline-input'>
	        				wget {!! url('/cronjob?token=952xn5vatvrs30j7xa2nvcx9y75ak32xu') !!}
	        			</div>

	        			<div class='m-xs-vertical'>Or</div>	

	        			<div class='inline-input'>
	        				wget -q -O- {!! url('/cronjob?token=952xn5vatvrs30j7xa2nvcx9y75ak32xu') !!}
	        			</div>
	        		</div>
	        	</div> <!-- end form-group -->
        	{!! Form::close() !!}
	    </div> <!-- end div-type-a -->
	</div> <!-- end row -->

@endsection