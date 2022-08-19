<h4 class='title-type-a near'>Basic Information</h4>

{!! Form::model($staff, ['route' => ['admin.user.info.update', $staff->id, 'basic-information'], 'class' => 'form-type-b plain-line']) !!}
	<div class='form-group'>
		<label for='first_name' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>First Name</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('first_name', null, ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly']]) !!}
			<span field='first_name' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='last_name' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Last Name <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('last_name', null, ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly']]) !!}
			<span field='last_name' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='birthdate' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Date of Birth</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('birthdate', null, ['class' => 'form-control datepicker ' . $input['class'], 'placeholder' => 'yyyy-mm-dd', 'readonly' => $input['readonly']]) !!}
			<span field='birthdate' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='phone' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Phone</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('phone', null, ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly']]) !!}
			<span field='phone' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='fax' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Fax</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('fax', null, ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly']]) !!}
			<span field='fax' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='website' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Website</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('website', null, ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly']]) !!}
			<span field='website' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	{!! Form::hidden('id', $staff->id) !!}
	{!! Form::hidden('type', 'basic-information') !!}

	@if($staff->follow_command_rule)
		<div class='form-group'>
		    <div class='col-xs-12 col-sm-offset-4 col-sm-8 col-md-offset-3 col-md-9 col-lg-offset-2 col-lg-10'>
		        {!! Form::submit('Save', ['name' => 'save', 'class' => 'save btn btn-primary']) !!}
		    </div>
		</div> <!-- end form-group -->
	@endif	
{!! Form::close() !!}