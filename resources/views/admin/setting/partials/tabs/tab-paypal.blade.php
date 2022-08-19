<h4 class='title-type-a near'>Paypal Settings</h4>

{!! Form::open(['route' => ['admin.setting.payment.update', 'paypal'], 'class' => 'form-type-b']) !!}
	<div class='form-group'>
		<label for='paypal_status' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Active</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			<div class='inline-input'>
			    <span><input type='radio' name='paypal_status' value='1' {!! tag_attr('1', config('setting.paypal_status'), 'checked') !!}> Yes</span>
			    <span><input type='radio' name='paypal_status' value='0' {!! tag_attr('0', config('setting.paypal_status'), 'checked') !!}> No</span>
			</div>
			<span field='paypal_status' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='paypal_email' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Paypal Email <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('paypal_email', check_before_decrypt(config('setting.paypal_email')), ['class' => 'form-control']) !!}
			<span field='paypal_email' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='paypal_mode' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Paypal Mode <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::select('paypal_mode', ['sandbox' => 'Sandbox', 'live' => 'Live'], config('setting.paypal_mode'), ['class' => 'form-control select-type-single-b']) !!}
			<span field='paypal_mode' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='paypal_sandbox_client_id' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Sandbox Client Id <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('paypal_sandbox_client_id', check_before_decrypt(config('setting.paypal_sandbox_client_id')), ['class' => 'form-control']) !!}
			<span field='paypal_sandbox_client_id' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='paypal_sandbox_secret' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Sandbox Secret <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('paypal_sandbox_secret', check_before_decrypt(config('setting.paypal_sandbox_secret')), ['class' => 'form-control']) !!}
			<span field='paypal_sandbox_secret' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='paypal_live_client_id' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Live Client Id <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('paypal_live_client_id', check_before_decrypt(config('setting.paypal_live_client_id')), ['class' => 'form-control']) !!}
			<span field='paypal_live_client_id' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='paypal_live_secret' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Live Secret <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('paypal_live_secret', check_before_decrypt(config('setting.paypal_live_secret')), ['class' => 'form-control']) !!}
			<span field='paypal_live_secret' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='paypal_ipn_url' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Paypal IPN URL</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			<div class='inline-input'>
				{!! url('/paypal-ipn') !!}
			</div>	
		</div>
	</div> <!-- end form-group -->

	{!! Form::hidden('type', 'paypal') !!}

	<div class='form-group'>
	    <div class='col-xs-12 col-sm-offset-4 col-sm-8 col-md-offset-3 col-md-9 col-lg-offset-2 col-lg-10'>
	        {!! Form::submit('Save', ['name' => 'save', 'class' => 'save btn btn-primary']) !!}
	    </div>
	</div> <!-- end form-group -->
{!! Form::close() !!}