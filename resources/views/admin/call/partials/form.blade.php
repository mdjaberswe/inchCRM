<div class='modal-body perfectscroll'>
	<div class='form-group'>
		<label for='subject' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Subject <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::text('subject', null, ['class' => 'form-control']) !!}
			<span field='subject' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='client' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conversation With <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			<div class='full related-field'>
				<div class='parent-field'>
					{!! Form::select('client_type', $client_types, null, ['class' => 'form-control white-select-type-single-b']) !!}
				</div>

				<div class='child-field'>
					{!! Form::hidden('client_id', null, ['data-child' => 'true']) !!}

					<div class='full' data-field='none' data-default='true'>
						{!! Form::text('client', null, ['class' => 'form-control', 'disabled' => true]) !!}
					</div>

					<div class='full none' data-field='lead'>
						{!! Form::select('lead_id', $client_list['lead'], null, ['class' => 'form-control white-select-type-single']) !!}
					</div>

					<div class='full none' data-field='contact'>
						{!! Form::select('contact_id', $client_list['contact'], null, ['class' => 'form-control white-select-type-single']) !!}
					</div>
				</div>	
			</div>
			<span field='client_type' class='validation-error'></span>
			<span field='client_id' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group show-if multiple'>
		<label for='related' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Related To</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			<div class='full related-field'>
				<div class='parent-field'>
					{!! Form::select('related_type', $related_type_list, null, ['class' => 'form-control white-select-type-single-b']) !!}
				</div>

				<div class='child-field'>
					{!! Form::hidden('related_id', null, ['data-child' => 'true']) !!}

					<div class='full' data-field='none' data-default='true'>
						{!! Form::text('related', null, ['class' => 'form-control', 'disabled' => true]) !!}
					</div>

					<div class='full none' data-field='account'>
						{!! Form::select('account_id', $related_to_list['account'], null, ['class' => 'form-control white-select-type-single']) !!}
					</div>

					<div class='full none' data-field='campaign'>
						{!! Form::select('campaign_id', $related_to_list['campaign'], null, ['class' => 'form-control white-select-type-single']) !!}
					</div>

					<div class='full none' data-field='event'>
						{!! Form::select('event_id', $related_to_list['event'], null, ['class' => 'form-control white-select-type-single']) !!}
					</div>

					<div class='full none' data-field='task'>
						{!! Form::select('task_id', $related_to_list['task'], null, ['class' => 'form-control white-select-type-single']) !!}
					</div>

					<div class='full none' data-field='deal'>
						{!! Form::select('deal_id', $related_to_list['deal'], null, ['class' => 'form-control white-select-type-single']) !!}
					</div>

					<div class='full none' data-field='project'>
						{!! Form::select('project_id', $related_to_list['project'], null, ['class' => 'form-control white-select-type-single']) !!}
					</div>

					<div class='full none' data-field='estimate'>
						{!! Form::select('estimate_id', $related_to_list['estimate'], null, ['class' => 'form-control white-select-type-single']) !!}
					</div>

					<div class='full none' data-field='invoice'>
						{!! Form::select('invoice_id', $related_to_list['invoice'], null, ['class' => 'form-control white-select-type-single']) !!}
					</div>
				</div>	
			</div>
			<span field='related_type' class='validation-error'></span>
			<span field='related_id' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='type' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Call Type</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('type', ['incoming' => 'Incoming', 'outgoing' => 'Outgoing'], 'outgoing', ['class' => 'form-control white-select-type-single-b']) !!}
			<span field='type' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='call_time' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Call Time <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::text('call_time', null, ['class' => 'form-control datetimepicker']) !!}
			<span field='call_time' class='validation-error'></span>
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