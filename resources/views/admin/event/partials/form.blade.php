<div class='modal-body perfectscroll'>
	<div class='full form-group-container'>
		<div class='form-group'>
			<label for='name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Event Name <span class='c-danger'>*</span></label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('name', null, ['class' => 'form-control']) !!}
				<span field='name' class='validation-error'></span>
			</div>
		</div> <!-- end form-group -->

		<div class='form-group'>
			<label for='location' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Location</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('location', null, ['class' => 'form-control']) !!}
				<span field='location' class='validation-error'></span>
			</div>
		</div> <!-- end form-group -->

		<div class='form-group'>
			<label for='event_owner' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Event Owner</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('event_owner', $event_owners_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
				<span field='event_owner' class='validation-error'></span>
			</div>
		</div> <!-- end form-group -->

		<div class='form-group'>
			<label for='attendees' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Attendees</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('attendees[]', $attendees_list, null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple']) !!}
				<span field='attendees' class='validation-error'></span>
			</div>
		</div> <!-- end form-group -->

		<div class='form-group'>
			<label for='start_date' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Date <span class='c-danger'>*</span></label>
			
			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				<div class='full'>
					<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
						<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='Start Date'>
							<i class='fa fa-calendar-check-o'></i>
							{!! Form::text('start_date', isset($start_date) ? $start_date : null, ['class' => 'form-control datetimepicker', 'placeholder' => 'Start Date']) !!}
							<span field='start_date' class='validation-error'></span>
						</div> <!-- end form-group -->
					</div>

					<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
						<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='End Date'>
							<i class='fa fa-calendar-times-o'></i>
							{!! Form::text('end_date', isset($end_date) ? $end_date : null, ['class' => 'form-control datetimepicker', 'placeholder' => 'End Date']) !!}
							<span field='end_date' class='validation-error'></span>
						</div> <!-- end form-group -->
					</div>
				</div>
			</div>
		</div> <!-- end form-group -->

		<div class='form-group'>
			<label for='reminder' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Reminder <span class='c-danger'>*</span></label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				<div class='full'>
					<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
						<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='Notification Time Before'>
							<i class='fa fa-clock-o'></i>
							{!! Form::text('reminder_time', 15, ['class' => 'form-control', 'placeholder' => 'Notification Time Before']) !!}
							<span field='reminder_time' class='validation-error'></span>
						</div> <!-- end form-group -->
					</div>

					<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
						<div class='full'>
							{!! Form::select('reminder_time_unit', $time_unit_list, null, ['class' => 'form-control white-select-type-single-b']) !!}
							<span field='reminder_time_unit' class='validation-error'></span>
						</div> <!-- end form-group -->
					</div>
				</div>
			</div>
		</div> <!-- end form-group -->

		<div class='form-group show-if multiple'>
			<label for='related' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Related To</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('related', $related_type_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='related' class='validation-error'></span>
			</div>
		</div> <!-- end form-group -->

		<div class='form-group related-input none'>
			<div class='full none lead-list'>
				<label for='lead_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Lead</label>

				<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
					{!! Form::select('lead_id', $related_to_list['lead'], null, ['class' => 'form-control white-select-type-single']) !!}
					<span field='lead_id' class='validation-error'></span>
				</div>
			</div> <!-- end lead dropdown -->	

			<div class='full none contact-list'>
				<label for='contact_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Contact</label>

				<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
					{!! Form::select('contact_id', $related_to_list['contact'], null, ['class' => 'form-control white-select-type-single']) !!}
					<span field='contact_id' class='validation-error'></span>
				</div>
			</div> <!-- end contact dropdown -->	

			<div class='full none account-list'>
				<label for='account_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Account</label>

				<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
					{!! Form::select('account_id', $related_to_list['account'], null, ['class' => 'form-control white-select-type-single']) !!}
					<span field='account_id' class='validation-error'></span>
				</div>
			</div> <!-- end account dropdown -->	

			<div class='full none project-list'>
				<label for='project_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Project</label>

				<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
					{!! Form::select('project_id', $related_to_list['project'], null, ['class' => 'form-control white-select-type-single']) !!}
					<span field='project_id' class='validation-error'></span>
				</div>
			</div> <!-- end project dropdown -->	

			<div class='full none campaign-list'>
				<label for='campaign_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Campaign</label>

				<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
					{!! Form::select('campaign_id', $related_to_list['campaign'], null, ['class' => 'form-control white-select-type-single']) !!}
					<span field='campaign_id' class='validation-error'></span>
				</div>
			</div> <!-- end campaign dropdown -->	

			<div class='full none deal-list'>
				<label for='deal_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Deal</label>

				<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
					{!! Form::select('deal_id', $related_to_list['deal'], null, ['class' => 'form-control white-select-type-single']) !!}
					<span field='deal_id' class='validation-error'></span>
				</div>
			</div> <!-- end deal dropdown -->	

			<div class='full none estimate-list'>
				<label for='estimate_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Estimate</label>

				<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
					{!! Form::select('estimate_id', $related_to_list['estimate'], null, ['class' => 'form-control white-select-type-single']) !!}
					<span field='estimate_id' class='validation-error'></span>
				</div>
			</div> <!-- end estimate dropdown -->	

			<div class='full none invoice-list'>
				<label for='invoice_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Invoice</label>

				<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
					{!! Form::select('invoice_id', $related_to_list['invoice'], null, ['class' => 'form-control white-select-type-single']) !!}
					<span field='invoice_id' class='validation-error'></span>
				</div>
			</div> <!-- end invoice dropdown -->	
		</div> <!-- end form-group -->

		<div class='form-group'>
			<label for='priority' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Priority</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('priority', $priority_list, null, ['class' => 'form-control white-select-type-single-b']) !!}
				<span field='priority' class='validation-error'></span>
			</div>
		</div> <!-- end form-group -->

		<div class='form-group'>
			<label for='description' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Description</label>

		    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
		        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
		        <span field='description' class='validation-error'></span>
		    </div>
		</div> <!-- end form-group -->

		<div class='form-group show-if'>
			<label for='repeat' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Repeat</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				<p class='pretty top-space info smooth'>
				    <input type='checkbox' name='repeat' value='1' class='indicator'>
				    <label><i class='mdi mdi-check'></i></label>			    
				</p>
				<span field='repeat' class='validation-error'></span>
			</div>
		</div> <!-- end form-group -->

		<div class='form-group repeatevery-input none'>
			<label for='repeat_every' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Repeat Every</label>
			
			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				<div class='full'>
					<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 div-double-input'>
						<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='Repeat Interval'>
							<i class='fa fa-repeat'></i>
							{!! Form::text('repeat_interval', 10, ['class' => 'form-control', 'placeholder' => 'Repeat Interval']) !!}
							<span field='repeat_interval' class='validation-error'></span>
						</div> <!-- end form-group -->
					</div>

					<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 div-double-input triple'>
						<div class='full'>
							{!! Form::select('repeat_type', $repeat_type_list, null, ['class' => 'form-control white-select-type-single-b']) !!}
							<span field='repeat_type' class='validation-error'></span>
						</div> <!-- end form-group -->
					</div>

					<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 div-double-input'>
						<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='Repeat Closing Date'>
							<i class='fa fa-calendar-times-o'></i>
							{!! Form::text('repeat_closing_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Repeat Closing Date']) !!}
							<span field='repeat_closing_date' class='validation-error'></span>
						</div> <!-- end form-group -->
					</div>
				</div>
			</div>
		</div> <!-- end form-group -->

		<div class='form-group'>
			<label for='access' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Access</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				<div class='inline-input show-if' @if(isset($form) && $form == 'create') scroll='true' flush='true' @endif>
					<p class='pretty top-space info smooth'>
					    <input type='radio' name='access' value='private' class='indicator'>
					    <label><i class='mdi mdi-check'></i></label> Private	    
					</p> 

					<p class='pretty top-space info smooth'>
					    <input type='radio' name='access' value='public' checked>
					    <label><i class='mdi mdi-check'></i></label> Public Read Only		    
					</p> 

					<p class='pretty top-space info smooth'>
					    <input type='radio' name='access' value='public_rwd'>
					    <label><i class='mdi mdi-check'></i></label> Public Read/Write/Delete	    
					</p> 
				</div>
				
				@if(isset($form) && $form == 'create')
					<div class='full m-Top-10 none'>
						{!! Form::select('staffs[]', $admin_users_list, null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Allow some users only']) !!}

						<p class='para-checkbox'>Allowed users can</p>

						<p class='pretty top-space info smooth'>
						    <input type='checkbox' name='can_read' value='1' checked disabled>
						    <label><i class='mdi mdi-check'></i></label> Read	    
						</p> 

						<p class='pretty top-space info smooth'>
						    <input type='checkbox' name='can_write' value='1'>
						    <label><i class='mdi mdi-check'></i></label> Write	    
						</p> 

						<p class='pretty top-space info smooth'>
						    <input type='checkbox' name='can_delete' value='1'>
						    <label><i class='mdi mdi-check'></i></label> Delete    
						</p> 
					</div>	
				@endif

				<span field='access' class='validation-error'></span>
			</div>	
		</div> <!-- end form-group -->
	</div> <!-- end from-group-container -->	
</div> <!-- end modal-body -->	

@if(isset($form) && $form == 'edit')
    {!! Form::hidden('id', null) !!}
@endif