<div class='modal-body perfectscroll'>
	<div class='form-group'>
		<label for='name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Project Name <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::text('name', null, ['class' => 'form-control']) !!}
			<span field='name' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='account_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Account Name<span class='c-danger'>*</span></label>
		
		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('account_id', $accounts_list, null, ['class' => 'form-control account white-select-type-single']) !!}
			<span field='account_id' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	{!! Form::hidden('contact_id[]', null) !!}
	{!! Form::hidden('deal_id', null) !!}

	<div class='form-group'>
		<label for='project_owner' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Project Owner</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('project_owner', $project_owners_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
			<span field='project_owner' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='status' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Status</label>
		
		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('status', $status_list, 'in_progress', ['class' => 'form-control white-select-type-single-b']) !!}
			<span field='status' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='start_date' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Date</label>
		
		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			<div class='full'>
				<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
					<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='Start Date'>
						<i class='fa fa-calendar-check-o'></i>
						{!! Form::text('start_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Start Date']) !!}
						<span field='start_date' class='validation-error'></span>
					</div> <!-- end form-group -->
				</div>

				<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
					<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='End Date'>
						<i class='fa fa-calendar-times-o'></i>
						{!! Form::text('end_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'End Date']) !!}
						<span field='end_date' class='validation-error'></span>
					</div> <!-- end form-group -->
				</div>
			</div>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='access' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Project Access</label>
		
		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>			
			<div class='inline-input'>
			    <span><input name='access' value='private' type='radio' checked> Private <i class='shadow-on-white'>(Only project members can access)</i></span>
			    <span><input name='access' value='public' type='radio'> Public <i class='shadow-on-white'>(Users can view)</i></span>
			</div>			
			<span field='access' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='description' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Description</label>

	    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
	        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
	        <span field='description' class='validation-error'></span>
	    </div>
	</div> <!-- end form-group -->
</div> <!-- end modal-body -->	

@if(isset($form) && $form == 'edit')
    {!! Form::hidden('id', null) !!}
@endif