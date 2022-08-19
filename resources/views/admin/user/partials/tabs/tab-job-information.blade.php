<h4 class='title-type-a near'>Job Information</h4>

{!! Form::model($staff, ['route' => ['admin.user.info.update', $staff->id, 'job-information'], 'class' => 'form-type-b plain-line']) !!}
	<div class='form-group'>
		<label for='title' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Job Title <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('title', null, ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly']]) !!}
			<span field='title' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group {!! $input['class'] !!}'>
		<label for='employee_type' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Employee Type</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::select('employee_type', $employee_type_list, null, ['class' => 'form-control select-type-single ' . $input['class'], 'disabled' => $input['readonly']]) !!}
			<span field='employee_type' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='date_of_hire' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Date of Hire</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('date_of_hire', null, ['class' => 'form-control datepicker ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'yyyy-mm-dd']) !!}
			<span field='date_of_hire' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	{!! Form::hidden('id', $staff->id) !!}
	{!! Form::hidden('type', 'job-information') !!}

	@if($staff->follow_command_rule)
		<div class='form-group'>
		    <div class='col-xs-12 col-sm-offset-4 col-sm-8 col-md-offset-3 col-md-9 col-lg-offset-2 col-lg-10'>
		        {!! Form::submit('Save', ['name' => 'save', 'class' => 'save btn btn-primary']) !!}
		    </div>
		</div> <!-- end form-group -->
	@endif	
{!! Form::close() !!}