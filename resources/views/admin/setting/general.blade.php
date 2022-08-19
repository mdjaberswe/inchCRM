@extends('layouts.master')

@section('content')

	<div class='row'>
		@include('partials.subnav.setting')
			
	    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-10 div-type-a'>
	        <h4 class='title-type-a'>General Settings</h4>

	        {!! Form::open(['route' => 'setting.general.post', 'files' => true, 'class' => 'form-type-b smooth-save']) !!}
	        	<div class='form-group'>
	        		<label for='app_name' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>App Name</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('app_name', config('setting.app_name'), ['class' => 'form-control']) !!}
	        			<span field='app_name' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='logo' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Logo</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			<img src='{!! asset(config('setting.logo')) !!}' alt='logo' realtime='logo'>
	        			<p class='para-hint'>Recommended Dimension : 500 x 200, Max Size : 3000KB, Allowed Format : png</p>
	        			{!! Form::file('logo', ['accept' => 'image/x-png', 'class' => 'plain']) !!}
	        			<span field='logo' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='favicon' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Favicon</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			<img src='{!! asset(config('setting.favicon')) !!}' alt='logo' realtime='favicon'>
	        			<p class='para-hint'>Recommended Dimension : 32 x 32, Max Size : 1000KB, Allowed Format : png</p>
	        			{!! Form::file('favicon', ['accept' => 'image/x-png', 'class' => 'plain']) !!}
	        			<span field='favicon' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='timezone' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Timezone</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::select('timezone', $time_zones_list, config('setting.timezone'), ['class' => 'form-control select-type-single']) !!}
	        			<span field='timezone' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='date_format' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Date Format</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::select('date_format', $date_format_lists, config('setting.date_format'), ['class' => 'form-control select-type-single']) !!}
	        			<span field='date_format' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='time_format' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Time Format</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::select('time_format', $time_format_list, config('setting.time_format'), ['class' => 'form-control select-type-single-b']) !!}
	        			<span field='time_format' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='pagination_limit' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Pagination Limit</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('pagination_limit', config('setting.pagination_limit'), ['class' => 'form-control']) !!}
	        			<span field='pagination_limit' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='allowed_files' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Allowed File Types</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('allowed_files', config('setting.allowed_files'), ['class' => 'form-control', 'placeholder' => 'Comma separated']) !!}
	        			<span field='allowed_files' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='purchase_code' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Purchase Code</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('purchase_code', config('setting.purchase_code') ? \Crypt::decrypt(config('setting.purchase_code')) : null, ['class' => 'form-control', 'placeholder' => 'Envato purchase code']) !!}
	        			<span field='purchase_code' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

        		<div class='form-group'>
        		    <div class='col-xs-12 col-sm-offset-4 col-sm-8 col-md-offset-3 col-md-9 col-lg-offset-2 col-lg-10'>
        		        {!! Form::submit('Save', ['name' => 'save', 'class' => 'save btn btn-primary']) !!}
        		    </div>
        		</div> <!-- end form-group -->
	        {!! Form::close() !!}
	    </div> <!-- end div-type-a -->
	</div> <!-- end row -->

@endsection

@push('scripts')
	@include('admin.setting.partials.script')
@endpush