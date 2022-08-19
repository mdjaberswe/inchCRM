@extends('layouts.master')

@section('content')

	<div class='row'>
		@include('partials.subnav.setting')
			
	    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-10 div-type-a'>
	        <h4 class='title-type-a'>SMS Settings</h4>

	        {!! Form::open(['route' => 'setting.sms.post', 'class' => 'form-type-b smooth-save']) !!}
	        	<div class='form-group'>
	        		<label for='sms_service' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>SMS&nbsp;Service</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			<select name='sms_service' class='form-control select-type-single parentfield'>
	        				<option value='disabled' childfield='none'>Disabled</option>
	        				<option value='clickatell' childfield='clickatell' {!! tag_attr('clickatell', $sms_service, 'selected') !!}>Clickatell</option>
	        				<option value='twilio' childfield='twilio' {!! tag_attr('twilio', $sms_service, 'selected') !!}>Twilio</option>
	        			</select>
	        			<span field='sms_service' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, [$sms_service], 'clickatell') !!}'>
	        		<label for='clickatell_username' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Clickatell&nbsp;Username&nbsp;<span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('clickatell_username', check_before_decrypt(config('setting.clickatell_username')), ['class' => 'form-control', 'parent' => 'clickatell']) !!}
	        			<span field='clickatell_username' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, [$sms_service], 'clickatell') !!}'>
	        		<label for='clickatell_password' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Clickatell&nbsp;Password&nbsp;<span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('clickatell_password', check_before_decrypt(config('setting.clickatell_password')), ['class' => 'form-control', 'parent' => 'clickatell']) !!}
	        			<span field='clickatell_password' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, [$sms_service], 'clickatell') !!}'>
	        		<label for='clickatell_api_id' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Clickatell&nbsp;Api&nbsp;Id&nbsp;<span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('clickatell_api_id', check_before_decrypt(config('setting.clickatell_api_id')), ['class' => 'form-control', 'parent' => 'clickatell']) !!}
	        			<span field='clickatell_api_id' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, [$sms_service], 'twilio') !!}'>
	        		<label for='twilio_account_sid' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Twilio&nbsp;Account&nbsp;SID&nbsp;<span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('twilio_account_sid', check_before_decrypt(config('setting.twilio_account_sid')), ['class' => 'form-control', 'parent' => 'twilio']) !!}
	        			<span field='twilio_account_sid' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, [$sms_service], 'twilio') !!}'>
	        		<label for='twilio_auth_token' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Auth&nbsp;Token&nbsp;<span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('twilio_auth_token', check_before_decrypt(config('setting.twilio_auth_token')), ['class' => 'form-control', 'parent' => 'twilio']) !!}
	        			<span field='twilio_auth_token' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, [$sms_service], 'twilio') !!}'>
	        		<label for='twilio_phone_no' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Twilio&nbsp;Phone&nbsp;No&nbsp;<span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('twilio_phone_no', check_before_decrypt(config('setting.twilio_phone_no')), ['class' => 'form-control', 'parent' => 'twilio']) !!}
	        			<span field='twilio_phone_no' class='validation-error'></span>
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