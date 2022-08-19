<div class='full'>
	<div class='col-xs-12 col-md-8'>
		<h3 class='title-section with-image'>
			<a class='modal-image add-multiple' data-avt='{!! 'lead' . $lead->id !!}' data-item='image' data-action='{!! route('admin.avatar.upload') !!}' data-content='partials.modals.upload-avatar' data-default='linked_type:lead|linked_id:{!! $lead->id !!}' save-new='false' data-modalsize='' modal-footer='hide' modal-files='true' save-txt='Crop and Save' modal-title='Lead Image'>
				<img src='{!! $lead->avatar !!}' alt='{!! $lead->name !!}'/>
				<span class='icon'><i class='fa fa-camera'></i></span>
			</a>
			<span data-realtime='first_name'>{!! $lead->name !!}</span>
			<span class='minor if-not-empty' data-realtime='company'>{!! non_property_checker($lead, 'company') !!}</span>
		</h3> <!-- end title-section -->	

		<div class='full section-line'>
			<div class='field zero-border editable'>
				<label>Lead Owner</label>

				<div class='value' data-value='{!! $lead->lead_owner !!}' data-realtime='lead_owner'>
					{!! $lead->leadowner->name !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
					{!! Form::select('lead_owner', $admin_users_list, $lead->lead_owner, ['class' => 'form-control select-type-single']) !!}
					<div class='edit-single-btn'>
						<a class='save-single'>Save</a>
						<a class='cancel-single'>Cancel</a>
					</div>
				</div>

				<a class='edit'><i class='fa fa-pencil'></i></a>
			</div> <!-- end field -->
		</div>

		<div class='full section-line'>
			<div class='field zero-border editable'>
				<label>Email</label>

				<div class='value' data-realtime='email'>
					{!! $lead->email !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
					<input type='text' name='email' value='{!! $lead->email !!}'>
					<div class='edit-single-btn'>
						<a class='save-single'>Save</a>
						<a class='cancel-single'>Cancel</a>
					</div>
				</div>

				<a class='edit'><i class='fa fa-pencil'></i></a>
			</div> <!-- end field -->
		</div>	

		<div class='full section-line'>
			<div class='field zero-border editable'>
				<label>Phone</label>

				<div class='value' data-realtime='phone'>
					{!! $lead->phone !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
					<input type='text' name='phone' value='{!! $lead->phone !!}'>
					<div class='edit-single-btn'>
						<a class='save-single'>Save</a>
						<a class='cancel-single'>Cancel</a>
					</div>
				</div>

				<a class='edit'><i class='fa fa-pencil'></i></a>
			</div> <!-- end field -->
		</div>	

		<div class='full section-line'>
			<div class='field last zero-border editable'>
				<label>Lead Stage</label>

				<div class='value' data-value='{!! $lead->lead_stage_id !!}' data-realtime='lead_stage_id'>
					{!! str_limit($lead->leadstage->name, 50) !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
					{!! Form::select('lead_stage_id', $lead_stages_list, $lead->lead_stage_id, ['class' => 'form-control select-type-single']) !!}
					<div class='edit-single-btn'>
						<a class='save-single'>Save</a>
						<a class='cancel-single'>Cancel</a>
					</div>
				</div>

				<a class='edit'><i class='fa fa-pencil'></i></a>
			</div> <!-- end field -->
		</div>	
	</div>

	<div id='next-action' class='col-xs-12 col-md-4'>
		{!! $lead->next_task_html !!}
	</div>
</div>

<div class='full show-hide-details'>
	<div class='col-xs-12'>
		<a class='link-caps' url='{!! route('admin.view.toggle', 'lead') !!}'>
			@if($lead_hide_details)
				SHOW DETAILS <i class='fa fa-angle-down'></i>
			@else
				HIDE DETAILS <i class='fa fa-angle-up'></i>
			@endif	
		</a>
	</div>	
</div>

<div class='full details-content @if($lead_hide_details) none @endif'>
	<div id='lead-info' class='full content-section'>
		<div class='col-xs-12'>
			<h4 class='title-sm-bold top-30'>Lead Information</h4>
		</div>
		
		<div class='full'>	
			<div class='col-xs-12 col-md-6'>
				<div class='field editable'>
					<label>Lead Name</label>

					<div class='value' data-value='{!! $lead->first_name . '|'. $lead->last_name !!}' data-multiple='true'>
						{!! $lead->name !!}
					</div>

					<div class='edit-single double' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='first_name' value='{!! $lead->first_name !!}' placeholder='First name'>
						<input type='text' name='last_name' value='{!! $lead->last_name !!}' placeholder='Last name'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Job Title</label>

					<div class='value'>
						{!! $lead->title !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='title' value='{!! $lead->title !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Email</label>

					<div class='value' data-realtime='email'>
						{!! $lead->email !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='email' value='{!! $lead->email !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Phone</label>

					<div class='value' data-realtime='phone'>
						{!! $lead->phone !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='phone' value='{!! $lead->phone !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Facebook</label>

					<div class='value' data-value='{!! non_property_checker($lead->getSocialDataAttribute('facebook'), 'link') !!}'>
						<a href='{!! $lead->getSocialLinkAttribute('facebook') !!}' target='_blank'>
							{!! non_property_checker($lead->getSocialDataAttribute('facebook'), 'link') !!}
						</a>
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='facebook' value='{!! non_property_checker($lead->getSocialDataAttribute('facebook'), 'link') !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Twitter</label>

					<div class='value'  data-value='{!! non_property_checker($lead->getSocialDataAttribute('twitter'), 'link') !!}'>
						<a href='{!! $lead->getSocialLinkAttribute('twitter') !!}' target='_blank'>
							{!! non_property_checker($lead->getSocialDataAttribute('twitter'), 'link') !!}
						</a>
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='twitter' value='{!! non_property_checker($lead->getSocialDataAttribute('twitter'), 'link') !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Skype</label>

					<div class='value'  data-value='{!! non_property_checker($lead->getSocialDataAttribute('skype'), 'link') !!}'>
						{!! non_property_checker($lead->getSocialDataAttribute('skype'), 'link') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='skype' value='{!! non_property_checker($lead->getSocialDataAttribute('skype'), 'link') !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->	

				<div class='field editable'>
					<label>Date of Birth</label>

					<div class='value' data-value='{!! $lead->date_of_birth !!}'>
						{!! $lead->readableDate('date_of_birth') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='date_of_birth' value='{!! $lead->date_of_birth !!}' class='datepicker'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field'>
					<label>Lead Score</label>

					<div class='value' data-realtime='lead_score_html'>
						{!! $lead->lead_score !!}
						&nbsp;
						{!! $lead->lead_score_status !!}						
					</div>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Lead Stage</label>

					<div class='value' data-value='{!! $lead->lead_stage_id !!}' data-realtime='lead_stage_id'>
						{!! str_limit($lead->leadstage->name, 50) !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						{!! Form::select('lead_stage_id', $lead_stages_list, $lead->lead_stage_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Lead Source</label>

					<div class='value' data-value='{!! $lead->source_id !!}'>
						{!! non_property_checker($lead->source, 'name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						{!! Form::select('source_id', $sources_list, $lead->source_id, ['class' => 'form-control select-type-single']) !!}
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
					<label>Company</label>

					<div class='value'>
						{!! $lead->company !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='company' value='{!! $lead->company !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>No. of Employees</label>

					<div class='value'>
						{!! $lead->no_of_employees !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' class='numeric' name='no_of_employees' value='{!! $lead->no_of_employees !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Annual Revenue</label>

					<div class='value' data-value='{!! number_format($lead->annual_revenue, 2, '.', '') . '|' . $lead->currency_id !!}' data-multiple='true'>
						<span class='symbol'>{!! $lead->currency->symbol !!}</span>{!! $lead->amountFormat('annual_revenue') !!}
					</div>

					<div class='edit-single left-icon ' icon='{!! currency_icon($lead->currency->code, $lead->currency->symbol) !!}' alter-icon='{!! $lead->currency->symbol !!}' base-id='{!! $base_currency->id !!}' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' class='numeric' name='annual_revenue' value='{!! $lead->annual_revenue !!}'>
						
			            <div class='icon clickable amount'>
			            	{!! Form::hidden('currency_id', $lead->currency_id, ['class' => 'reset-currency']) !!}
				            <i class='dropdown-toggle {!! currency_icon($lead->currency->code, $lead->currency->symbol) !!}' data-toggle='dropdown' animation='fadeIn|fadeOut'>{!! is_null(currency_icon($lead->currency->code, $lead->currency->symbol)) ? $base_currency->symbol : '' !!}</i>
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
					<label>Fax</label>

					<div class='value'>
						{!! $lead->fax !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='fax' value='{!! $lead->fax !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Website</label>

					<div class='value' data-value='{!! $lead->website !!}'>
						<a href='{!! quick_url($lead->website) !!}' target='_blank'>
							{!! $lead->website !!}
						</a>
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='website' value='{!! $lead->website !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Lead Owner</label>

					<div class='value' data-value='{!! $lead->lead_owner !!}' data-realtime='lead_owner'>
						{!! $lead->leadowner->name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						{!! Form::select('lead_owner', $admin_users_list, $lead->lead_owner, ['class' => 'form-control select-type-single']) !!}
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
							{!! $lead->createdByName() !!}<br>
							<span class='c-shadow sm'>{!! $lead->created_ampm !!}</span>
						</p>
					</div>
				</div> <!-- end field -->

				<div class='field'>
					<label>Modified By</label>

					<div class='value' data-realtime='updated_by'>
						<p class='compact'>
							{!! $lead->updatedByName() !!}<br>
							<span class='c-shadow sm'>{!! $lead->updated_ampm !!}</span>
						</p>	
					</div>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Access</label>

					<div id='access' class='value' data-value='{!! $lead->access !!}'>
						{!! $lead->access_html !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						{!! Form::select('access', $access_list, $lead->access, ['class' => 'form-control select-type-single-b']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->
			</div>	
		</div>	
	</div> <!-- end lead-info -->

	<div id='address-info' class='full content-section'>
		<div class='col-xs-12'>
			<h4 class='title-sm-bold'>Address Information</h4>
		</div>
		
		<div class='full'>	
			<div class='col-xs-12 col-md-6'>
				<div class='field editable'>
					<label>Street</label>

					<div class='value'>
						{!! $lead->street !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='street' value='{!! $lead->street !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>State</label>

					<div class='value'>
						{!! $lead->state !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='state' value='{!! $lead->state !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Country</label>

					<div class='value' data-value='{!! $lead->country_code !!}'>
						{!! non_property_checker($lead->country, 'ascii_name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						{!! Form::select('country_code', $countries_list, $lead->country_code, ['class' => 'form-control select-type-single']) !!}
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
					<label>City</label>

					<div class='value'>
						{!! $lead->city !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='city' value='{!! $lead->city !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Zip Code</label>

					<div class='value'>
						{!! $lead->zip !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						<input type='text' name='zip' value='{!! $lead->zip !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->
			</div>	
		</div>	
	</div> <!-- end address-info -->

	<div id='description' class='full content-section'>
		<div class='col-xs-12 col-md-8'>
			<h4 class='title-sm-bold'>Description Information</h4>
		</div>

		<div class='full'>	
			<div class='col-xs-12 col-md-8'>
				<div class='field auto editable'>
					<div class='value'>
						{!! $lead->description !!}
					</div>

					<div class='edit-single textarea' data-action='{!! route('admin.lead.single.update', $lead->id) !!}'>
						{!! Form::textarea('description', $lead->description, ['rows' => 0]) !!}
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
							<p>Lead Source was updated from Web <span class='bold'>Download</span> to <span class='bold'>Cold Call</span></p>
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
							<p>Lead Owner was changed from <span class='bold'>Shaikh Jaber</span> to <span class='bold'>Hasina</span></p>
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

		@include('admin.lead.partials.timeline-shortinfo')
	</div>	
</div>	