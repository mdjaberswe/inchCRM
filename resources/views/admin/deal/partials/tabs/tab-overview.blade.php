<div class='full'>
	<div class='col-xs-12 col-md-8'>
		<h3 class='title-section'>
			<span data-realtime='name'>{!! $deal->name !!}</span> 
			<span class='minor if-not-empty' data-realtime='amount'>{!! $deal->amountHtml('amount') !!}</span>
		</h3> <!-- end title-section -->	

		<div class='full section-line'>
			<div class='field zero-border editable'>
				<label>Deal Owner</label>

				<div class='value' data-value='{!! $deal->deal_owner !!}' data-realtime='deal_owner'>
					{!! $deal->owner->name !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
					{!! Form::select('deal_owner', $admin_users_list, $deal->deal_owner, ['class' => 'form-control select-type-single']) !!}
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
				<label>Stage</label>

				<div class='value' data-value='{!! $deal->deal_stage_id !!}' data-realtime='deal_stage_id'>
					{!! non_property_checker($deal->stage, 'name') !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
					{!! Form::select('deal_stage_id', $deal_stages_list, $deal->deal_stage_id, ['class' => 'form-control select-type-single']) !!}
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
				<label>Probability</label>

				<div class='value' data-value='{!! $deal->probability !!}' data-realtime='probability'>
					{!! $deal->probability_amount !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
					<input type='text' name='probability' value='{!! $deal->probability !!}'>
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
				<label>Closing Date</label>

				<div class='value' data-value='{!! $deal->closing_date !!}' data-realtime='closing_date' data-datepicker='true'>
					{!! $deal->readableDate('closing_date') !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
					<input type='text' name='closing_date' value='{!! $deal->closing_date !!}' class='datepicker'>
					<div class='edit-single-btn'>
						<a class='save-single'>Save</a>
						<a class='cancel-single'>Cancel</a>
					</div>
				</div>

				<a class='edit'><i class='fa fa-pencil'></i></a>
			</div> <!-- end field -->
		</div> <!-- end section-line -->
	</div>

	<div id='next-action' class='col-xs-12 col-md-4 view-all-none'>
		{!! $deal->next_task_html !!}
	</div>
</div>

<div class='full'>
	<div class='col-xs-12'>
		{!! $deal->stageline_html !!}
	</div>
</div>

<div class='full show-hide-details'>
	<div class='col-xs-12'>
		<a class='link-caps' url='{!! route('admin.view.toggle', 'deal') !!}'>
			@if($deal->hide_info)
				SHOW DETAILS <i class='fa fa-angle-down'></i>
			@else
				HIDE DETAILS <i class='fa fa-angle-up'></i>
			@endif	
		</a>
	</div>	
</div>

<div class='full details-content @if($deal->hide_info) none @endif'>
	<div id='deal-info' class='full content-section'>
		<div class='col-xs-12'>
			<h4 class='title-sm-bold top-30'>Deal Information</h4>
		</div>
		
		<div class='full'>	
			<div class='col-xs-12 col-md-6'>
				<div class='field editable'>
					<label>Deal Name</label>

					<div class='value'>
						{!! $deal->name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						<input type='text' name='name' value='{!! $deal->name !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Amount</label>

					<div class='value' data-value='{!! number_format($deal->amount, 2, '.', '') . '|' . $deal->currency_id !!}' data-multiple='true'>
						{!! $deal->amountHtml('amount') !!}
					</div>

					<div class='edit-single left-icon ' icon='{!! currency_icon($deal->currency->code, $deal->currency->symbol) !!}' alter-icon='{!! $deal->currency->symbol !!}' base-id='{!! $base_currency->id !!}' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						<input type='text' class='numeric' name='amount' value='{!! $deal->amount !!}'>
						
			            <div class='icon clickable amount'>
			            	{!! Form::hidden('currency_id', $deal->currency_id, ['class' => 'reset-currency']) !!}
				            <i class='dropdown-toggle {!! currency_icon($deal->currency->code, $deal->currency->symbol) !!}' data-toggle='dropdown' animation='fadeIn|fadeOut'>{!! is_null(currency_icon($deal->currency->code, $deal->currency->symbol)) ? $base_currency->symbol : '' !!}</i>
				            <ul class='dropdown-menu dark up-caret select sm currency-list'>
				            	<div class='full perfectscroll max-h-100'>
					                {!! $currency_list !!}
					            </div> 
				            </ul>
				        </div>  
				        
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Closing Date</label>

					<div class='value' data-value='{!! $deal->closing_date !!}' data-realtime='closing_date' data-datepicker='true'>
						{!! $deal->readableDate('closing_date') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						<input type='text' name='closing_date' value='{!! $deal->closing_date !!}' class='datepicker'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Pipeline</label>

					<div class='value' data-value='{!! $deal->deal_pipeline_id !!}'>
						{!! $deal->pipeline->name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						{!! Form::select('deal_pipeline_id', $deal_pipelines_list, $deal->deal_pipeline_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Stage</label>

					<div class='value' data-value='{!! $deal->deal_stage_id !!}' data-realtime='deal_stage_id'>
						{!! $deal->stage->name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						{!! Form::select('deal_stage_id', $deal_stages_list, $deal->deal_stage_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Probability</label>

					<div class='value' data-value='{!! $deal->probability !!}' data-realtime='probability'>
						{!! $deal->probability_amount !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						<input type='text' class='numeric' name='probability' value='{!! $deal->probability !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Source</label>

					<div class='value' data-value='{!! $deal->source_id !!}'>
						{!! non_property_checker($deal->source, 'name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						{!! Form::select('source_id', $sources_list, $deal->source_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Campaign</label>

					<div class='value' data-value='{!! $deal->campaign_id !!}'>
						{!! non_property_checker($deal->campaign, 'name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						{!! Form::select('campaign_id', $campaigns_list, $deal->campaign_id, ['class' => 'form-control select-type-single']) !!}
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
					<label>Account</label>

					<div class='value' data-value='{!! $deal->account_id !!}'>
						{!! $deal->account->name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						{!! Form::select('account_id', $accounts_list, $deal->account_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Contact</label>

					<div class='value' data-value='{!! $deal->contact_id !!}'>
						{!! non_property_checker($deal->contact, 'name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						{!! Form::select('contact_id', $deal->account->contacts_list, $deal->contact_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Deal Type</label>

					<div class='value' data-value='{!! $deal->deal_type_id !!}'>
						{!! non_property_checker($deal->type, 'name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						{!! Form::select('deal_type_id', $deal_types_list, $deal->deal_type_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Deal Owner</label>

					<div class='value' data-value='{!! $deal->deal_owner !!}' data-realtime='deal_owner'>
						{!! $deal->owner->name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						{!! Form::select('deal_owner', $admin_users_list, $deal->deal_owner, ['class' => 'form-control select-type-single']) !!}
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
							{!! $deal->createdByName() !!}<br>
							<span class='c-shadow sm'>{!! $deal->created_ampm !!}</span>
						</p>
					</div>
				</div> <!-- end field -->

				<div class='field'>
					<label>Modified By</label>

					<div class='value' data-realtime='updated_by'>
						<p class='compact'>
							{!! $deal->updatedByName() !!}<br>
							<span class='c-shadow sm'>{!! $deal->updated_ampm !!}</span>
						</p>	
					</div>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Access</label>

					<div id='access' class='value' data-value='{!! $deal->access !!}'>
						{!! $deal->access_html !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						{!! Form::select('access', $access_list, $deal->access, ['class' => 'form-control select-type-single-b']) !!}
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
						{!! $deal->description !!}
					</div>

					<div class='edit-single textarea' data-action='{!! route('admin.deal.single.update', $deal->id) !!}'>
						{!! Form::textarea('description', $deal->description, ['rows' => 0]) !!}
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

<div class='full datatable-container'>
	<div class='col-xs-12 col-md-12'>
		<h4 class='title-sm-bold table-title'>Participant Contacts</h4>

	     <div class='right-top'>
			<button type='button' class='btn btn-type-a plain add-multiple' modal-title='Add Participants to Deal' modal-sub-title='{!! $deal->name !!}' modal-datatable='true' datatable-url='{!! 'participant-select/deal/' . $deal->id !!}' data-action='{!! route('admin.participant.contact.add', ['deal', $deal->id]) !!}' data-content='contact.partials.modal-participant' data-default='{!! 'module_id:' . $deal->id . '|module_name:deal' !!}' save-new='false'>
				<i class='mdi mdi-plus'></i> Add Participant
			</button>
	     </div>

	    <table id='deal-participant' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'participant-contact-data/deal/' . $deal->id !!}' datacolumn='{!! $contacts_table['json_columns'] !!}' databtn='{!! table_showhide_columns($contacts_table) !!}' perpage='10'>
			<thead>
				<tr>
					<th data-priority='1' data-class-name='all' style='max-width: 210px'>contact&nbsp;name</th>
					<th data-priority='3'>phone</th>	
					<th data-priority='4'>email</th>
					<th data-priority='6'>type</th>
					<th data-priority='5'>account</th>
					<th data-priority='2' data-orderable='false' data-class-name='align-r all' class='action-2'></th>       			      			        			
				</tr>
			</thead>
		</table>
	</div>
</div> <!-- end datatable-container -->	

<div class='full datatable-container'>
	<div class='col-xs-12 col-md-12'>
		<h4 class='title-sm-bold table-title'>Stage Records</h4>

	    <table id='deal-stage-history' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'deal-stage-history/' . $deal->id !!}' datacolumn='{!! $stage_history_table['json_columns'] !!}' databtn='{!! table_showhide_columns($stage_history_table) !!}' perpage='10'>
			<thead>
				<tr>
					<th data-priority='1' data-class-name='all'>stage</th>
					<th data-priority='6' data-class-name='align-r'>amount</th>
					<th data-priority='5' data-class-name='center' style='max-width: 40px;'>
						<span data-toggle='tooltip' data-placement='top' title='Probability'>{!! no_space('pr (%)') !!}</span>
					</th>
					<th data-priority='6' data-class-name='align-r' style='max-width: 110px;'>{!! no_space('expected revenue') !!}</th>
					<th data-priority='7' style='max-width: 90px;'>{!! no_space('closing date') !!}</th>
					<th data-priority='2' data-class-name='align-r' style='max-width: 50px;'>duration</th>							
					<th data-priority='3' style='min-width: 130px; max-width: 145px;'>{!! no_space('modified time') !!}</th>
					<th data-priority='4'>{!! no_space('modified by') !!}</th>  			      			        			
				</tr>
			</thead>
		</table>
	</div>
</div> <!-- end datatable-container -->	

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

		@include('admin.deal.partials.timeline-shortinfo')
	</div>	
</div>