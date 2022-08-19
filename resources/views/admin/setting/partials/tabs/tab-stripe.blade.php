<h4 class='title-type-a near'>Stripe Settings</h4>

{!! Form::open(['route' => ['admin.setting.payment.update', 'stripe'], 'class' => 'form-type-b']) !!}
	<div class='form-group'>
		<label for='stripe_status' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Active</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			<div class='inline-input'>
			    <span><input type='radio' name='stripe_status' value='1' {!! tag_attr('1', config('setting.stripe_status'), 'checked') !!}> Yes</span>
			    <span><input type='radio' name='stripe_status' value='0' {!! tag_attr('0', config('setting.stripe_status'), 'checked') !!}> No</span>
			</div>
			<span field='stripe_status' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='stripe_mode' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Stripe Mode <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::select('stripe_mode', ['test' => 'Test', 'live' => 'Live'], config('setting.stripe_mode'), ['class' => 'form-control select-type-single-b']) !!}
			<span field='stripe_mode' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='stripe_test_secret_key' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Test Secret Key <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('stripe_test_secret_key', check_before_decrypt(config('setting.stripe_test_secret_key')), ['class' => 'form-control']) !!}
			<span field='stripe_test_secret_key' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='stripe_test_public_key' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Test Public Key <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('stripe_test_public_key', check_before_decrypt(config('setting.stripe_test_public_key')), ['class' => 'form-control']) !!}
			<span field='stripe_test_public_key' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='stripe_live_secret_key' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Live Secret Key <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('stripe_live_secret_key', check_before_decrypt(config('setting.stripe_live_secret_key')), ['class' => 'form-control']) !!}
			<span field='stripe_live_secret_key' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='stripe_live_public_key' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Live Public Key <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('stripe_live_public_key', check_before_decrypt(config('setting.stripe_live_public_key')), ['class' => 'form-control']) !!}
			<span field='stripe_live_public_key' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	{!! Form::hidden('type', 'stripe') !!}

	<div class='form-group'>
	    <div class='col-xs-12 col-sm-offset-4 col-sm-8 col-md-offset-3 col-md-9 col-lg-offset-2 col-lg-10'>
	        {!! Form::submit('Save', ['name' => 'save', 'class' => 'save btn btn-primary']) !!}
	    </div>
	</div> <!-- end form-group -->
{!! Form::close() !!}