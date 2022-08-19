<h4 class='title-type-a near'>Account Settings</h4>

{!! Form::model($staff, ['route' => ['admin.user.info.update', $staff->id, 'account-settings'], 'class' => 'form-type-b plain-line']) !!}
	<div class='form-group {!! $input['role_class'] !!}'>
		<label for='role' class='col-xs-12 col-sm-3 col-md-2 col-lg-2'>Role <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-9 col-md-10 col-lg-10'>
			{!! Form::select('role[]', $roles_list, $staff->roles_list, ['class' => 'form-control select-type-multiple ' . $input['role_class'], 'disabled' => $input['disabled_role'], 'multiple' => 'multiple', 'style' => 'width: 100%']) !!}
			<span field='role' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='email' class='col-xs-12 col-sm-3 col-md-2 col-lg-2'>Email <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-9 col-md-10 col-lg-10'>
			{!! Form::text('email', $staff->email, ['class' => 'form-control ' . $input['email_class'], 'readonly' => $input['readonly_email']]) !!}
			<span field='email' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	@if($staff->edit_credential)
		<div class='form-group'>
			<label for='password' class='col-xs-12 col-sm-3 col-md-2 col-lg-2'>Password</label>

			<div class='col-xs-12 col-sm-9 col-md-10 col-lg-10'>
				{!! Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off']) !!}
				<span field='password' class='validation-error'></span>
			</div>
		</div> <!-- end form-group -->

		<div class='form-group'>
			<label for='password_confirmation' class='col-xs-12 col-sm-3 col-md-2 col-lg-2'>Confirm Password</label>

			<div class='col-xs-12 col-sm-9 col-md-10 col-lg-10'>
				{!! Form::password('password_confirmation', ['class' => 'form-control', 'autocomplete' => 'off']) !!}
				<span field='password_confirmation' class='validation-error'></span>
			</div>
		</div> <!-- end form-group -->

		{!! Form::hidden('id', $staff->id) !!}
		{!! Form::hidden('type', 'account-settings') !!}

	
		<div class='form-group'>
		    <div class='col-xs-12 col-sm-offset-4 col-sm-8 col-md-offset-3 col-md-9 col-lg-offset-2 col-lg-10'>
		        {!! Form::submit('Save', ['name' => 'save', 'class' => 'save btn btn-primary']) !!}
		    </div>
		</div> <!-- end form-group -->
	@endif
{!! Form::close() !!}