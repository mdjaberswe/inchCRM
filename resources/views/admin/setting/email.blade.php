@extends('layouts.master')

@section('content')

	<div class='row'>
		@include('partials.subnav.setting')
			
	    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-10 div-type-a'>
	        <h4 class='title-type-a'>Email Settings</h4>

	        {!! Form::open(['route' => 'setting.email.post', 'class' => 'form-type-b smooth-save']) !!}
	        	<div class='form-group'>
	        		<label for='mail_driver' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Default Mailer</label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			<select name='mail_driver' class='form-control select-type-single parentfield'>
	        				<option value='mail' childfield='mail' {!! tag_attr('mail', $mail_driver, 'selected') !!}>PHP mail()</option>
	        				<option value='smtp' childfield='smtp' {!! tag_attr('smtp', $mail_driver, 'selected') !!}>SMTP</option>
	        				<option value='mailgun' childfield='smtp.mailgun' {!! tag_attr('mailgun', $mail_driver, 'selected') !!}>Mailgun</option>
	        				<option value='mandrill' childfield='smtp.mandrill' {!! tag_attr('mandrill', $mail_driver, 'selected') !!}>Mandrill</option>
	        				<option value='ses' childfield='smtp.ses' {!! tag_attr('ses', $mail_driver, 'selected') !!}>Amazon SES</option>
	        			</select>
	        			<span field='mail_driver' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, $mail_driver_type, 'smtp') !!}'>
	        		<label for='mail_host' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Hostname <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('mail_host', config('setting.mail_host'), ['class' => 'form-control', 'parent' => 'smtp']) !!}
	        			<span field='mail_host' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, $mail_driver_type, 'smtp') !!}'>
	        		<label for='mail_username' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Username <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('mail_username', check_before_decrypt(config('setting.mail_username')), ['class' => 'form-control', 'parent' => 'smtp']) !!}
	        			<span field='mail_username' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, $mail_driver_type, 'smtp') !!}'>
	        		<label for='mail_password' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Password <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('mail_password', check_before_decrypt(config('setting.mail_password')), ['class' => 'form-control', 'parent' => 'smtp']) !!}
	        			<span field='mail_password' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, $mail_driver_type, 'smtp') !!}'>
	        		<label for='mail_port' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Port <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('mail_port', config('setting.mail_port'), ['class' => 'form-control', 'parent' => 'smtp']) !!}
	        			<span field='mail_port' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, $mail_driver_type, 'smtp') !!}'>
	        		<label for='mail_encryption' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Encryption <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::select('mail_encryption', ['tls' => 'TLS', 'ssl' => 'SSL'], config('setting.mail_encryption'), ['class' => 'form-control select-type-single-b', 'parent' => 'smtp']) !!}
	        			<span field='mail_encryption' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, $mail_driver_type, 'mailgun') !!}'>
	        		<label for='mailgun_domain' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Mailgun Domain <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('mailgun_domain', check_before_decrypt(config('setting.mailgun_domain')), ['class' => 'form-control', 'parent' => 'mailgun']) !!}
	        			<span field='mailgun_domain' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, $mail_driver_type, 'mailgun') !!}'>
	        		<label for='mailgun_secret' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Mailgun Secret <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('mailgun_secret', check_before_decrypt(config('setting.mailgun_secret')), ['class' => 'form-control', 'parent' => 'mailgun']) !!}
	        			<span field='mailgun_secret' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, $mail_driver_type, 'mandrill') !!}'>
	        		<label for='mandrill_secret' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Mandrill Secret <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('mandrill_secret', check_before_decrypt(config('setting.mandrill_secret')), ['class' => 'form-control', 'parent' => 'mandrill']) !!}
	        			<span field='mandrill_secret' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, $mail_driver_type, 'ses') !!}'>
	        		<label for='ses_key' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>SES Key <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('ses_key', check_before_decrypt(config('setting.ses_key')), ['class' => 'form-control', 'parent' => 'ses']) !!}
	        			<span field='ses_key' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, $mail_driver_type, 'ses') !!}'>
	        		<label for='ses_secret' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>SES Secret <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('ses_secret', check_before_decrypt(config('setting.ses_secret')), ['class' => 'form-control', 'parent' => 'ses']) !!}
	        			<span field='ses_secret' class='validation-error'></span>
	        		</div>
	        	</div> <!-- end form-group -->

	        	<div class='{!! append_css_class('form-group', 'none', false, $mail_driver_type, 'ses') !!}'>
	        		<label for='ses_region' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>SES Region <span class='c-danger'>*</span></label>

	        		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
	        			{!! Form::text('ses_region', check_before_decrypt(config('setting.ses_region')), ['class' => 'form-control', 'parent' => 'ses']) !!}
	        			<span field='ses_region' class='validation-error'></span>
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