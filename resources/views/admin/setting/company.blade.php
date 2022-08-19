@extends('layouts.master')

@section('content')

	<div class='row'>
		@include('partials.subnav.setting')
			
	    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-10 div-type-a'>
	        <h4 class='title-type-a'>Company Settings</h4>

	        {!! Form::open(['route' => 'setting.company.post', 'files' => true, 'class' => 'form-type-b smooth-save']) !!}
	        	<div class='form-group'>
	        		<label for='company_name' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Company Name</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('company_name', config('setting.company_name'), ['class' => 'form-control']) !!}
	        			<span field='company_name' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_logo' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Company Logo <span class='c-shadow sm'>(&nbsp;PDF&nbsp;and&nbsp;HTML&nbsp;)</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			<img src='{!! asset(config('setting.company_logo')) !!}' alt='logo' realtime='company_logo' @if(is_null(config('setting.company_logo'))) style='display: none' @endif>	        			
	        			<p class='para-hint'>Recommended Dimension : 500 x 200, Max Size : 3000KB, Allowed Format : png</p>
	        			{!! Form::file('company_logo', ['accept' => 'image/x-png', 'class' => 'plain']) !!}
	        			<span field='company_logo' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_phone' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Phone</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('company_phone', config('setting.company_phone'), ['class' => 'form-control']) !!}
	        			<span field='company_phone' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_fax' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Fax</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('company_fax', config('setting.company_fax'), ['class' => 'form-control']) !!}
	        			<span field='company_fax' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_website' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Website</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('company_website', config('setting.company_website'), ['class' => 'form-control']) !!}
	        			<span field='company_website' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_vat_no' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>VAT Number</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('company_vat_no', config('setting.company_vat_no'), ['class' => 'form-control']) !!}
	        			<span field='company_vat_no' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_description' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Description</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::textarea('company_description', config('setting.company_description'), ['class' => 'form-control']) !!}
	        			<span field='company_description' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_street' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Street</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('company_street', config('setting.company_street'), ['class' => 'form-control']) !!}
	        			<span field='company_street' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_city' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>City</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('company_city', config('setting.company_city'), ['class' => 'form-control']) !!}
	        			<span field='company_city' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_state' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>State</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('company_state', config('setting.company_state'), ['class' => 'form-control']) !!}
	        			<span field='company_state' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_zip' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Zip Code</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('company_zip', config('setting.company_zip'), ['class' => 'form-control']) !!}
	        			<span field='company_zip' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_country' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Country</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::select('company_country', $countries_list, config('setting.company_country'), ['class' => 'form-control select-type-single']) !!}
	        			<span field='company_country' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='form-group'>
	        		<label for='company_info_format' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Company Information&nbsp;Format <span class='c-shadow sm'>(&nbsp;PDF&nbsp;and&nbsp;HTML&nbsp;)</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10 editor-type-a toolbar-free'>
	        			{!! Form::textarea('company_info_format', config('setting.company_info_format'), ['class' => 'form-control plain-editor']) !!}
	        			<div class='shortcode'>
	        				<a shortcode='[company_name]'>[company_name]</a> 
	        				<a shortcode='[street]'>[street]</a> 
	        				<a shortcode='[city]'>[city]</a> 
	        				<a shortcode='[state]'>[state]</a> 
	        				<a shortcode='[zip_code]'>[zip_code]</a> 
	        				<a shortcode='[country]'>[country]</a> 
	        				<a shortcode='[phone]'>[phone]</a> 
	        				<a shortcode='[fax]'>[fax]</a> 
	        				<a shortcode='[website]'>[website]</a> 
	        				<a shortcode='[vat_number]'>[vat_number]</a>
	        			</div>	
	        			<span field='company_info_format' class='validation-error'></span>
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