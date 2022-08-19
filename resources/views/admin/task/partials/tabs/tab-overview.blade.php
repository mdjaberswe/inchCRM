<div class='full'>
	<div class='col-xs-12 col-md-8'>
		<h3 class='title-section'>
			<span data-realtime='name'>{!! str_limit($task->name, 50) !!}</span> 
		</h3> <!-- end title-section -->	

		<div class='full section-line'>
			<div class='field zero-border editable'>
				<label>Task Owner</label>

				<div class='value' data-value='{!! $task->task_owner !!}' data-realtime='task_owner'>
					{!! $task->owner->name !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
					{!! Form::select('task_owner', $task_owner_list, $task->task_owner, ['class' => 'form-control select-type-single']) !!}
					<div class='edit-single-btn'>
						<a class='save-single'>Save</a>
						<a class='cancel-single'>Cancel</a>
					</div>
				</div>

				<a class='edit'><i class='fa fa-pencil'></i></a>
			</div> <!-- end field -->
		</div> <!-- end section-line -->

		<div class='full section-line'>
			<div class='field zero-border editable'>
				<label>Due Date</label>

				<div class='value' data-value='{!! $task->due_date !!}' data-realtime='due_date' data-datepicker='true'>
					{!! $task->readableDate('due_date') !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
					<input type='text' name='due_date' value='{!! $task->due_date !!}' class='datepicker'>
					<div class='edit-single-btn'>
						<a class='save-single'>Save</a>
						<a class='cancel-single'>Cancel</a>
					</div>
				</div>

				<a class='edit'><i class='fa fa-pencil'></i></a>
			</div> <!-- end field -->
		</div> <!-- end section-line -->

		<div class='full section-line'>
			<div class='field zero-border editable'>
				<label>Priority</label>

				<div class='value' data-value='{!! $task->priority !!}' data-realtime='priority'>
					{!! ucfirst($task->priority) !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
					{!! Form::select('priority', $priority_list, $task->priority, ['class' => 'form-control select-type-single']) !!}
					<div class='edit-single-btn'>
						<a class='save-single'>Save</a>
						<a class='cancel-single'>Cancel</a>
					</div>
				</div>

				<a class='edit'><i class='fa fa-pencil'></i></a>
			</div> <!-- end field -->
		</div> <!-- end section-line -->

		<div class='full section-line'>
			<div class='field zero-border editable'>
				<label>Status</label>

				<div class='value' data-value='{!! $task->task_status_id !!}' data-realtime='task_status_id'>
					{!! non_property_checker($task->status, 'name') !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
					{!! Form::select('task_status_id', $status_plain_list, $task->task_status_id, ['class' => 'form-control select-type-single']) !!}
					<div class='edit-single-btn'>
						<a class='save-single'>Save</a>
						<a class='cancel-single'>Cancel</a>
					</div>
				</div>

				<a class='edit'><i class='fa fa-pencil'></i></a>
			</div> <!-- end field -->
		</div> <!-- end section-line -->
	</div>
</div>

<div class='full show-hide-details'>
	<div class='col-xs-12'>
		<a class='link-caps' url='{!! route('admin.view.toggle', 'task') !!}'>
			@if($task->hide_info)
				SHOW DETAILS <i class='fa fa-angle-down'></i>
			@else
				HIDE DETAILS <i class='fa fa-angle-up'></i>
			@endif
		</a>
	</div>	
</div>

<div class='full details-content @if($task->hide_info) none @endif'>
	<div id='deal-info' class='full content-section'>
		<div class='col-xs-12'>
			<h4 class='title-sm-bold top-30'>Task Information</h4>
		</div>
		
		<div class='full'>	
			<div class='col-xs-12 col-md-6'>
				<div class='field editable'>
					<label>Task Name</label>

					<div class='value' data-value="{!! $task->name !!}" data-realtime='name'>
						{!! str_limit($task->name, 50) !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
						<input type='text' name='name' value='{!! $task->name !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Start Date</label>

					<div class='value' data-value='{!! $task->start_date !!}' data-realtime='start_date' data-datepicker='true'>
						{!! $task->readableDate('start_date') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
						<input type='text' name='start_date' value='{!! $task->start_date !!}' class='datepicker'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Priority</label>

					<div class='value' data-value='{!! $task->priority !!}' data-realtime='priority'>
						{!! ucfirst($task->priority) !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
						{!! Form::select('priority', $priority_list, $task->priority, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Status</label>

					<div class='value' data-value='{!! $task->task_status_id !!}' data-realtime='task_status_id'>
						{!! $task->status->name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
						{!! Form::select('task_status_id', $status_plain_list, $task->task_status_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable @if($task->status->category == 'closed') edit-false @endif'>
					<label>Completion</label>

					<div class='value percent' data-value='{!! $task->completion_percentage !!}' data-realtime='completion_percentage'>{!! $task->completion_percentage !!}</div>

					<div class='edit-single percentage-options' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
						<select name='completion_percentage' class='form-control select-type-single'>
							{!! number_options_html(0, 100, 10) !!}
						</select>

						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Reminder</label>

					<div class='value' data-value='' data-realtime='' data-datepicker='true'>
						{!! '08:28 AM, May 3, 2019' !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
						<input type='text' name='reminder' value='' class='datetimepicker'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Recurrence</label>

					<div class='value' data-value='{!! null !!}' data-realtime='recurrence' data-datepicker='true'>
						Every 10 days, Until 2019-05-22
					</div>

					<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
						<input type='text' name='recurrence' value='{!! $task->due_date !!}' class='datepicker'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->
			</div>

			<div class='col-xs-12 col-md-6'>
				<div class='field editable'>
					<label>Related To</label>

					<div class='value' data-value='{!! $task->linked_type . '|'. $task->linked_id !!}' data-multiple='true' data-realtime='linked_type'>
						{!! non_property_checker($task->linked, 'name_link_icon') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}' data-appear='false'>
						<div class='full'>
							<div class='full related-field show-select-arrow'>
								<div class='parent-field select-full'>
									{!! Form::select('linked_type', $related_type_list, null, ['class' => 'form-control choose-select select-type-single-b']) !!}
								</div>

								<div class='child-field'>
									{!! Form::hidden('linked_id', $task->linked_id, ['data-child' => 'true']) !!}

									<div class='full' data-field='none' data-default='true'>
										{!! Form::text('linked', null, ['class' => 'form-control', 'disabled' => true]) !!}
									</div>

									<div class='full none' data-field='lead'>
										{!! Form::select('lead_id', $related_to_list['lead'], null, ['class' => 'form-control select-type-single']) !!}
									</div>

									<div class='full none' data-field='contact'>
										{!! Form::select('contact_id', $related_to_list['contact'], null, ['class' => 'form-control select-type-single']) !!}
									</div>

									<div class='full none' data-field='account'>
										{!! Form::select('account_id', $related_to_list['account'], null, ['class' => 'form-control select-type-single']) !!}
									</div>

									<div class='full none' data-field='campaign'>
										{!! Form::select('campaign_id', $related_to_list['campaign'], null, ['class' => 'form-control select-type-single']) !!}
									</div>

									<div class='full none' data-field='deal'>
										{!! Form::select('deal_id', $related_to_list['deal'], null, ['class' => 'form-control select-type-single']) !!}
									</div>

									<div class='full none' data-field='project'>
										{!! Form::select('project_id', $related_to_list['project'], null, ['class' => 'form-control select-type-single']) !!}
									</div>

									<div class='full none' data-field='estimate'>
										{!! Form::select('estimate_id', $related_to_list['estimate'], null, ['class' => 'form-control select-type-single']) !!}
									</div>

									<div class='full none' data-field='invoice'>
										{!! Form::select('invoice_id', $related_to_list['invoice'], null, ['class' => 'form-control select-type-single']) !!}
									</div>
								</div>	
							</div>
						</div>

						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Due Date</label>

					<div class='value' data-value='{!! $task->due_date !!}' data-realtime='due_date' data-datepicker='true'>
						{!! $task->readableDate('due_date') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
						<input type='text' name='due_date' value='{!! $task->due_date !!}' class='datepicker'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Task Owner</label>

					<div class='value' data-value='{!! $task->task_owner !!}' data-realtime='task_owner'>
						{!! $task->owner->name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
						{!! Form::select('task_owner', $task_owner_list, $task->task_owner, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field'>
					<label>Created By</label>

					<div class='value'>
						<p class='compact'>
							{!! $task->createdByName() !!}<br>
							<span class='c-shadow sm'>{!! $task->created_ampm !!}</span>
						</p>
					</div>
				</div> <!-- end field -->

				<div class='field'>
					<label>Modified By</label>

					<div class='value' data-realtime='updated_by'>
						<p class='compact'>
							{!! $task->updatedByName() !!}<br>
							<span class='c-shadow sm'>{!! $task->updated_ampm !!}</span>
						</p>	
					</div>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Access</label>

					<div id='access' class='value' data-value='{!! $task->access !!}'>
						{!! $task->access_html !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
						{!! Form::select('access', $access_list, $task->access, ['class' => 'form-control select-type-single-b']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->
			</div>	
		</div>	
	</div> <!-- end deal-info -->

	<div id='description' class='full content-section'>
		<div class='col-xs-12 col-md-8'>
			<h4 class='title-sm-bold'>Description Information</h4>
		</div>

		<div class='full'>	
			<div class='col-xs-12 col-md-8'>
				<div class='field auto editable'>
					<div class='value'>
						{!! $task->description !!}
					</div>

					<div class='edit-single textarea' data-action='{!! route('admin.task.single.update', $task->id) !!}'>
						{!! Form::textarea('description', $task->description, ['rows' => 0]) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->
			</div>
		</div>		
	</div> <!--end description -->
</div> <!-- end details-content -->	

<div id='recent-activity' class='full'>
	<div class='col-xs-12'>
		<h4 class='title-sm-bold bottom-20'>History</h4>
	</div>

	<div class='full'>
		<div class='col-xs-12 col-md-8'>
			<div class='full timeline section'>
				<div class='timeline-info start'>
					<div class='timeline-icon'>Today</div>
				</div> <!-- end timeline-info -->

				<div class='timeline-info'>
					<div class='timeline-icon'>
					    <i class='fa fa-calendar' data-toggle='tooltip' data-placement='bottom' title='Event'></i>
					</div>

					<div class='timeline-details'>
						<div class='timeline-title'>
							<p>Event added - <a>Jan 2018 Summit</a></p>
						</div>

						<div class='timeline-record'>
							<span>by</span>
							<span class='plain'>Shaikh Jaber</span>
							<span data-toggle='tooltip' data-placement='bottom' title='01:38 PM, Aug 15, 2018'>Aug 15, 2018</span>
						</div>
					</div> <!-- end timeline-details -->
				</div> <!-- end timeline-info -->

				<div class='timeline-info'>
					<div class='timeline-icon'>
					    <i class='fa fa-pencil' data-toggle='tooltip' data-placement='bottom' title='Updated'></i>
					</div>

					<div class='timeline-details'>
						<div class='timeline-title'>
							<p>Deal Source was updated from Web <span class='bold'>Download</span> to <span class='bold'>Cold Call</span></p>
						</div>

						<div class='timeline-record'>
							<span>by</span>
							<span class='plain'>Shaikh Jaber</span>
							<span data-toggle='tooltip' data-placement='bottom' title='01:38 PM, Aug 15, 2018'>Aug 15, 2018</span>
						</div>
					</div> <!-- end timeline-details -->
				</div> <!-- end timeline-info -->

				<div class='timeline-info'>
					<div class='timeline-icon'>
					    <i class='fa fa-pencil' data-toggle='tooltip' data-placement='bottom' title='Updated'></i>
					</div>

					<div class='timeline-details'>
						<div class='timeline-title'>
							<p>Deal Owner was changed from <span class='bold'>Shaikh Jaber</span> to <span class='bold'>Hasina</span></p>
						</div>

						<div class='timeline-record'>
							<span>by</span>
							<span class='plain'>Shaikh Jaber</span>
							<span data-toggle='tooltip' data-placement='bottom' title='01:38 PM, Aug 15, 2018'>Aug 15, 2018</span>
						</div>
					</div> <!-- end timeline-details -->
				</div> <!-- end timeline-info -->

				<div class='timeline-info'>
					<div class='timeline-icon'>
					    <i class='fa fa-tasks' data-toggle='tooltip' data-placement='bottom' title='Task'></i>
					</div>

					<div class='timeline-details'>
						<div class='timeline-title'>
							<p>Task added - <a>My first task</a></p>
						</div>

						<div class='timeline-record'>
							<span>by</span>
							<span class='plain'>Shaikh Jaber</span>
							<span data-toggle='tooltip' data-placement='bottom' title='01:38 PM, Aug 15, 2018'>Aug 15, 2018</span>
						</div>
					</div> <!-- end timeline-details -->
				</div> <!-- end timeline-info -->

				<div class='timeline-info prev-end'>
					<div class='timeline-icon'>
					    <i class='fa fa-paperclip' data-toggle='tooltip' data-placement='bottom' title='Attachment'></i>
					</div>

					<div class='timeline-details'>
						<div class='timeline-title'>
							<p>Attachment added - <span class='bold'>index.html</span></p>
						</div>

						<div class='timeline-record'>
							<span>by</span>
							<span class='plain'>Shaikh Jaber</span>
							<span data-toggle='tooltip' data-placement='bottom' title='01:38 PM, Aug 15, 2018'>Aug 15, 2018</span>
						</div>
					</div> <!-- end timeline-details -->
				</div> <!-- end timeline-info -->

				<div class='timeline-info end'>
					<div class='timeline-icon'><a class='tab-link' tabkey='timeline'>View all</a></div>
				</div> <!-- end timeline-info -->
			</div> <!-- end timeline -->
		</div>

		@include('admin.task.partials.timeline-shortinfo')
	</div>	
</div>