<div class='full'>
	<div class='col-xs-12 col-md-8'>
		<h3 class='title-section with-image-status'>
			<a class='modal-image add-multiple' data-avt='{!! 'contact' . $contact->id !!}' data-item='image' data-action='{!! route('admin.avatar.upload') !!}' data-content='partials.modals.upload-avatar' data-default='linked_type:contact|linked_id:{!! $contact->id !!}' save-new='false' data-modalsize='' modal-footer='hide' modal-files='true' save-txt='Crop and Save' modal-title='Contact Image'>
				<img src='{!! $contact->avatar !!}' alt='{!! $contact->name !!}'/>
				<span class='icon'><i class='fa fa-camera'></i></span>
			</a>
			<span data-realtime='first_name'>{!! $contact->name !!}</span>
			<span class='minor if-not-empty' data-realtime='account_name'>{!! $contact->account_name !!}</span>
			{!! $contact->getStatusHtmlAttribute('right') !!}
		</h3> <!-- end title-section -->	

		<div class='full section-line'>
			<div class='field zero-border editable'>
				<label>Contact Owner</label>

				<div class='value' data-value='{!! $contact->contact_owner !!}' data-realtime='contact_owner'>
					{!! $contact->contactowner->name !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
					{!! Form::select('contact_owner', $admin_users_list, $contact->contact_owner, ['class' => 'form-control select-type-single']) !!}
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
					{!! $contact->email !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
					<input type='text' name='email' value='{!! $contact->email !!}'>
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
					{!! $contact->phone !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
					<input type='text' name='phone' value='{!! $contact->phone !!}'>
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
				<label>Contact Type</label>

				<div class='value' data-value='{!! $contact->contact_type_id !!}' data-realtime='contact_type_id'>
					{!! str_limit(non_property_checker($contact->type, 'name'), 50) !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
					{!! Form::select('contact_type_id', $contact_types_list, $contact->contact_type_id, ['class' => 'form-control select-type-single']) !!}
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
		{!! $contact->next_task_html !!}
	</div>
</div>

<div class='full show-hide-details'>
	<div class='col-xs-12'>
		<a class='link-caps' url='{!! route('admin.view.toggle', 'contact') !!}'>
			@if($contact_hide_details)
				SHOW DETAILS <i class='fa fa-angle-down'></i>
			@else
				HIDE DETAILS <i class='fa fa-angle-up'></i>
			@endif	
		</a>
	</div>	
</div>

<div class='full details-content @if($contact_hide_details) none @endif'>
	<div id='lead-info' class='full content-section'>
		<div class='col-xs-12'>
			<h4 class='title-sm-bold top-30'>Contact Information</h4>
		</div>
		
		<div class='full'>	
			<div class='col-xs-12 col-md-6'>
				<div class='field editable'>
					<label>Contact Name</label>

					<div class='value' data-value='{!! $contact->first_name . '|'. $contact->last_name !!}' data-multiple='true'>
						{!! $contact->name !!}
					</div>

					<div class='edit-single double' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='first_name' value='{!! $contact->first_name !!}' placeholder='First name'>
						<input type='text' name='last_name' value='{!! $contact->last_name !!}' placeholder='Last name'>
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
						{!! $contact->title !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='title' value='{!! $contact->title !!}'>
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
						{!! $contact->email !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='email' value='{!! $contact->email !!}'>
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
						{!! $contact->phone !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='phone' value='{!! $contact->phone !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Facebook</label>

					<div class='value' data-value='{!! non_property_checker($contact->getSocialDataAttribute('facebook'), 'link') !!}'>
						<a href='{!! $contact->getSocialLinkAttribute('facebook') !!}' target='_blank'>
							{!! non_property_checker($contact->getSocialDataAttribute('facebook'), 'link') !!}
						</a>
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='facebook' value='{!! non_property_checker($contact->getSocialDataAttribute('facebook'), 'link') !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Twitter</label>

					<div class='value'  data-value='{!! non_property_checker($contact->getSocialDataAttribute('twitter'), 'link') !!}'>
						<a href='{!! $contact->getSocialLinkAttribute('twitter') !!}' target='_blank'>
							{!! non_property_checker($contact->getSocialDataAttribute('twitter'), 'link') !!}
						</a>
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='twitter' value='{!! non_property_checker($contact->getSocialDataAttribute('twitter'), 'link') !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Skype</label>

					<div class='value'  data-value='{!! non_property_checker($contact->getSocialDataAttribute('skype'), 'link') !!}'>
						{!! non_property_checker($contact->getSocialDataAttribute('skype'), 'link') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='skype' value='{!! non_property_checker($contact->getSocialDataAttribute('skype'), 'link') !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->	

				<div class='field editable'>
					<label>Date of Birth</label>

					<div class='value' data-value='{!! $contact->date_of_birth !!}'>
						{!! $contact->readableDate('date_of_birth') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='date_of_birth' value='{!! $contact->date_of_birth !!}' class='datepicker'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Contact Type</label>

					<div class='value' data-value='{!! $contact->contact_type_id !!}' data-realtime='contact_type_id'>
						{!! str_limit(non_property_checker($contact->type, 'name'), 50) !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						{!! Form::select('contact_type_id', $contact_types_list, $contact->contact_type_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Source</label>

					<div class='value' data-value='{!! $contact->source_id !!}'>
						{!! non_property_checker($contact->source, 'name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						{!! Form::select('source_id', $sources_list, $contact->source_id, ['class' => 'form-control select-type-single']) !!}
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

					<div class='value' data-value='{!! $contact->account_id !!}'>
						{!! $contact->account_name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}' data-confirm-account='true'>
						{!! Form::select('account_id', $accounts_list, $contact->account_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Reporting To</label>

					<div class='value' data-value='{!! $contact->parent_id !!}'>
						{!! non_property_checker($contact->parentContact, 'name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						{!! Form::select('parent_id', $contact->parent_contacts_list, $contact->parent_id, ['class' => 'form-control parent_id select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Annual Revenue</label>

					<div class='value' data-value='{!! number_format($contact->annual_revenue, 2, '.', '') . '|' . $contact->currency_id !!}' data-multiple='true'>
						<span class='symbol'>{!! $contact->currency->symbol !!}</span>{!! $contact->amountFormat('annual_revenue') !!}
					</div>

					<div class='edit-single left-icon ' icon='{!! currency_icon($contact->currency->code, $contact->currency->symbol) !!}' alter-icon='{!! $contact->currency->symbol !!}' base-id='{!! $base_currency->id !!}' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' class='numeric' name='annual_revenue' value='{!! $contact->annual_revenue !!}'>
						
			            <div class='icon clickable amount'>
			            	{!! Form::hidden('currency_id', $contact->currency_id, ['class' => 'reset-currency']) !!}
				            <i class='dropdown-toggle {!! currency_icon($contact->currency->code, $contact->currency->symbol) !!}' data-toggle='dropdown' animation='fadeIn|fadeOut'>{!! is_null(currency_icon($contact->currency->code, $contact->currency->symbol)) ? $base_currency->symbol : '' !!}</i>
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
						{!! $contact->fax !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='fax' value='{!! $contact->fax !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Website</label>

					<div class='value' data-value='{!! $contact->website !!}'>
						<a href='{!! quick_url($contact->website) !!}' target='_blank'>
							{!! $contact->website !!}
						</a>
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='website' value='{!! $contact->website !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Contact Owner</label>

					<div class='value' data-value='{!! $contact->contact_owner !!}' data-realtime='contact_owner'>
						{!! $contact->contactowner->name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						{!! Form::select('contact_owner', $admin_users_list, $contact->contact_owner, ['class' => 'form-control select-type-single']) !!}
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
							{!! $contact->createdByName() !!}<br>
							<span class='c-shadow sm'>{!! $contact->created_ampm !!}</span>
						</p>
					</div>
				</div> <!-- end field -->

				<div class='field'>
					<label>Modified By</label>

					<div class='value' data-realtime='updated_by'>
						<p class='compact'>
							{!! $contact->updatedByName() !!}<br>
							<span class='c-shadow sm'>{!! $contact->updated_ampm !!}</span>
						</p>	
					</div>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Access</label>

					<div id='access' class='value' data-value='{!! $contact->access !!}'>
						{!! $contact->access_html !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						{!! Form::select('access', $access_list, $contact->access, ['class' => 'form-control select-type-single-b']) !!}
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
						{!! $contact->street !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='street' value='{!! $contact->street !!}'>
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
						{!! $contact->state !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='state' value='{!! $contact->state !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Country</label>

					<div class='value' data-value='{!! $contact->country_code !!}'>
						{!! non_property_checker($contact->country, 'ascii_name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						{!! Form::select('country_code', $countries_list, $contact->country_code, ['class' => 'form-control select-type-single']) !!}
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
						{!! $contact->city !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='city' value='{!! $contact->city !!}'>
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
						{!! $contact->zip !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						<input type='text' name='zip' value='{!! $contact->zip !!}'>
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

	<div id='client-portal' class='full content-section'>
		<div class='col-xs-12'>
			<h4 class='title-sm-bold'>Client Portal Permissions</h4>
		</div>			
		
		<div class='full'>	
			<div class='col-xs-12 col-md-6' data-realtime-update='true' data-realtime-success-msg='0' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>		
	        	{!! Form::hidden('client_permission', true) !!}

	        	@foreach($client_roles as $module => $roles)
					<div class='field editable toggle-permission'>
						<label>{!! ucfirst($module) !!}</label>

						<div class='value'>
							<label class='switch parent-permission'>
								<input type='checkbox' name='{!! $module . '_access' !!}' value='1' @if($contact->moduleAccess($module)) checked @endif>
								<span class='slider round'></span>
							</label>

							<div class='child-permission' @if(!$contact->moduleAccess($module)) style='opacity: 0.5' @endif>
								@foreach($roles as $role)
									<div class='inline-checkbox'>
				        				<p class='pretty top-space danger smooth'>
				        				    <input type='radio' name='{!! $module . '_role' !!}' value='{!! $role->id !!}' @if(!(strpos($role->name, 'view_all') !== false)) data-default='true' @endif @if(!$contact->moduleAccess($module)) disabled='disabled' @endif @if($contact->moduleRole($module) == $role->id) checked='checked' @endif>
				        				    <label><i class='mdi mdi-check'></i></label> {!! $role->display_name !!}		        					
				        				</p>

				        				@if(strpos($role->name, 'view_all') !== false)
			        						<i class='tik-info fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='{!! 'View&nbsp;all&nbsp;' . $module . 's&nbsp;realted&nbsp;with the&nbsp;key&nbsp;account' !!}'></i>
		        						@else
		        							<i class='tik-info fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='{!! 'View&nbsp;' . $module . 's&nbsp;realted&nbsp;with the&nbsp;contact' !!}'></i>
		        						@endif
									</div>
								@endforeach
							</div>
						</div>
					</div> <!-- end field -->
	        	@endforeach
        	</div>
		</div>	
	</div> <!-- end client-portal -->

	<div id='description' class='full content-section'>
		<div class='col-xs-12 col-md-8'>
			<h4 class='title-sm-bold'>Description Information</h4>
		</div>

		<div class='full'>	
			<div class='col-xs-12 col-md-8'>
				<div class='field auto editable'>
					<div class='value'>
						{!! $contact->description !!}
					</div>

					<div class='edit-single textarea' data-action='{!! route('admin.contact.single.update', $contact->id) !!}'>
						{!! Form::textarea('description', $contact->description, ['rows' => 0]) !!}
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
		<h4 class='title-sm-bold table-title'>
			<a class='title-icon' data-toggle='tooltip' data-placement='top' title='Contact&nbsp;Hierarchy'><i class='fa fa-sitemap'></i></a>  
			Reporting Contacts
		</h4>

	     <div class='right-top'>
			<button type='button' class='btn btn-type-a plain add-new-common' data-item='contact' data-action='{!! route('admin.contact.store') !!}' data-content='contact.partials.form' data-default='{!! $contact->hierarchy_form_default !!}' save-new='false'>
				<i class='mdi mdi-plus'></i> Add Contact
			</button>
	     </div>

	    <table id='append-contact' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'reporting-contact-data/' . $contact->id !!}' datacolumn='{!! $contacts_table['json_columns'] !!}' databtn='{!! table_showhide_columns($contacts_table) !!}' perpage='10'>
			<thead>
				<tr>
					<th data-priority='1' data-class-name='all' style='max-width: 210px'>contact&nbsp;name</th>
					<th data-priority='3'>phone</th>
					<th data-priority='4' data-class-name='align-r'>
						<span data-toggle='tooltip' data-placement='top' title='Open&nbsp;deals&nbsp;amount'>open&nbsp;deals&nbsp;amt</span>
					</th> 
					<th data-priority='5' data-class-name='align-l-space'>email</th>						
					<th data-priority='6' data-orderable='false' data-class-name='center'>status</th>
					<th data-priority='2' data-orderable='false' data-class-name='align-r all' class='action-2'></th>       			      			        			
				</tr>
			</thead>
		</table>
	</div>
</div>	

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
							<p>Contact Source was updated from Web <span class='bold'>Download</span> to <span class='bold'>Cold Call</span></p>
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
							<p>Contact Owner was changed from <span class='bold'>Shaikh Jaber</span> to <span class='bold'>Hasina</span></p>
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

		@include('admin.contact.partials.timeline-shortinfo')
	</div>	
</div>	