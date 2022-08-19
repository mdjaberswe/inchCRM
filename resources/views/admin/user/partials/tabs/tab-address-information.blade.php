<h4 class='title-type-a near'>Address Information</h4>

{!! Form::model($staff, ['route' => ['admin.user.info.update', $staff->id, 'address-information'], 'class' => 'form-type-b plain-line']) !!}
	<div class='form-group'>
		<label for='street' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Street</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('street', null, ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly']]) !!}
			<span field='street' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='city' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>City</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('city', null, ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly']]) !!}
			<span field='city' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='state' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>State</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('state', null, ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly']]) !!}
			<span field='state' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='zip' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Zip Code</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('zip', null, ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly']]) !!}
			<span field='zip' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group {!! $input['class'] !!}'>
		<label for='country_code' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Country <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::select('country_code', $countries_list, null, ['class' => 'form-control select-type-single ' . $input['class'], 'disabled' => $input['readonly']]) !!}
			<span field='country_code' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	{!! Form::hidden('id', $staff->id) !!}
	{!! Form::hidden('type', 'address-information') !!}

	@if($staff->follow_command_rule)
		<div class='form-group'>
		    <div class='col-xs-12 col-sm-offset-4 col-sm-8 col-md-offset-3 col-md-9 col-lg-offset-2 col-lg-10'>
		        {!! Form::submit('Save', ['name' => 'save', 'class' => 'save btn btn-primary']) !!}
		    </div>
		</div> <!-- end form-group -->
	@endif	
{!! Form::close() !!}