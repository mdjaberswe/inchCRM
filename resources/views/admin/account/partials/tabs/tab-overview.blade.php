<div class='full'>
	<div class='col-xs-12 col-md-8'>
		<h3 class='title-section with-image'>
			<a class='modal-image add-multiple' data-avt='{!! 'account' . $account->id !!}' data-item='image' data-action='{!! route('admin.avatar.upload') !!}' data-content='partials.modals.upload-avatar' data-default='linked_type:account|linked_id:{!! $account->id !!}' save-new='false' data-modalsize='' modal-footer='hide' modal-files='true' save-txt='Crop and Save' modal-title='Account Image'>
				<img src='{!! $account->avatar !!}' alt='{!! $account->name !!}'/>
				<span class='icon'><i class='fa fa-camera'></i></span>
			</a>
			<span data-realtime='account_name'>{!! $account->account_name !!}</span> 
			@if($account->has_hierarchy)
				<span class='c-shadow center m-0-2'> - </span> <a class='title-icon shadow tab-link' tabkey='orgchart' data-toggle='tooltip' data-placement='top' title='Account&nbsp;Hierarchy'><i class='fa fa-sitemap'></i></a> 
			@endif
		</h3> <!-- end title-section -->	

		<div class='full section-line'>
			<div class='field zero-border editable'>
				<label>Account Owner</label>

				<div class='value' data-value='{!! $account->account_owner !!}' data-realtime='account_owner'>
					{!! $account->owner->name !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
					{!! Form::select('account_owner', $admin_users_list, $account->account_owner, ['class' => 'form-control select-type-single']) !!}
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
				<label>Industry</label>

				<div class='value' data-value='{!! $account->industry_type_id !!}' data-realtime='industry_type_id'>
					{!! non_property_checker($account->industry, 'name') !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
					{!! Form::select('industry_type_id', $industry_types_list, $account->industry_type_id, ['class' => 'form-control select-type-single']) !!}
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
				<label>Annual Revenue</label>

				<div class='value' data-value='{!! number_format($account->annual_revenue, 2, '.', '') . '|' . $account->currency_id !!}' data-multiple='true' data-realtime='annual_revenue'>
					<span class='symbol'>{!! $account->currency->symbol !!}</span>{!! $account->amountFormat('annual_revenue') !!}
				</div>

				<div class='edit-single left-icon ' icon='{!! currency_icon($account->currency->code, $account->currency->symbol) !!}' alter-icon='{!! $account->currency->symbol !!}' base-id='{!! $base_currency->id !!}' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
					<input type='text' class='numeric' name='annual_revenue' value='{!! $account->annual_revenue !!}'>
					
		            <div class='icon clickable amount'>
		            	{!! Form::hidden('currency_id', $account->currency_id, ['class' => 'reset-currency']) !!}
			            <i class='dropdown-toggle {!! currency_icon($account->currency->code, $account->currency->symbol) !!}' data-toggle='dropdown' animation='fadeIn|fadeOut'>{!! is_null(currency_icon($account->currency->code, $account->currency->symbol)) ? $base_currency->symbol : '' !!}</i>
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
		</div> <!-- end section-line -->	

		<div class='full section-line'>
			<div class='field zero-border editable'>
				<label>Phone</label>

				<div class='value' data-realtime='account_phone'>
					{!! $account->account_phone !!}
				</div>

				<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
					<input type='text' name='account_phone' value='{!! $account->account_phone !!}'>
					<div class='edit-single-btn'>
						<a class='save-single'>Save</a>
						<a class='cancel-single'>Cancel</a>
					</div>
				</div>

				<a class='edit'><i class='fa fa-pencil'></i></a>
			</div> <!-- end field -->
		</div> <!-- end section-line -->
	</div>

	<div id='next-action' class='col-xs-12 col-md-4'>
		{!! $account->next_task_html !!}
	</div>
</div>

<div class='full show-hide-details'>
	<div class='col-xs-12'>
		<a class='link-caps' url='{!! route('admin.view.toggle', 'account') !!}'>
			@if($account_hide_details)
				SHOW DETAILS <i class='fa fa-angle-down'></i>
			@else
				HIDE DETAILS <i class='fa fa-angle-up'></i>
			@endif	
		</a>
	</div>	
</div>

<div class='full details-content @if($account_hide_details) none @endif'>
	<div id='account-info' class='full content-section'>
		<div class='col-xs-12'>
			<h4 class='title-sm-bold top-30'>Account Information</h4>
		</div>
		
		<div class='full'>	
			<div class='col-xs-12 col-md-6'>
				<div class='field editable'>
					<label>Account Name</label>

					<div class='value'>
						{!! $account->account_name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='account_name' value='{!! $account->account_name !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Parent Account</label>

					<div class='value' data-value='{!! $account->parent_id !!}'>
						{!! non_property_checker($account->parentAccount, 'account_name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						{!! Form::select('parent_id', array_except($parent_accounts_list, $account->id), $account->parent_id, ['class' => 'form-control select-type-single']) !!}
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
						{!! $account->no_of_employees !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' class='numeric' name='no_of_employees' value='{!! $account->no_of_employees !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Email</label>

					<div class='value' data-realtime='account_email'>
						{!! $account->account_email !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='account_email' value='{!! $account->account_email !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Phone</label>

					<div class='value' data-realtime='account_phone'>
						{!! $account->account_phone !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='account_phone' value='{!! $account->account_phone !!}'>
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
						{!! $account->fax !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='fax' value='{!! $account->fax !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Facebook</label>

					<div class='value' data-value='{!! non_property_checker($account->getSocialDataAttribute('facebook'), 'link') !!}'>
						<a href='{!! $account->getSocialLinkAttribute('facebook') !!}' target='_blank'>
							{!! non_property_checker($account->getSocialDataAttribute('facebook'), 'link') !!}
						</a>
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='facebook' value='{!! non_property_checker($account->getSocialDataAttribute('facebook'), 'link') !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Twitter</label>

					<div class='value'  data-value='{!! non_property_checker($account->getSocialDataAttribute('twitter'), 'link') !!}'>
						<a href='{!! $account->getSocialLinkAttribute('twitter') !!}' target='_blank'>
							{!! non_property_checker($account->getSocialDataAttribute('twitter'), 'link') !!}
						</a>
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='twitter' value='{!! non_property_checker($account->getSocialDataAttribute('twitter'), 'link') !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Skype</label>

					<div class='value'  data-value='{!! non_property_checker($account->getSocialDataAttribute('skype'), 'link') !!}'>
						{!! non_property_checker($account->getSocialDataAttribute('skype'), 'link') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='skype' value='{!! non_property_checker($account->getSocialDataAttribute('skype'), 'link') !!}'>
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
					<label>Account Type</label>

					<div class='value' data-value='{!! $account->account_type_id !!}' data-realtime='account_type_id'>
						{!! non_property_checker($account->type, 'name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						{!! Form::select('account_type_id', $account_types_list, $account->account_type_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Industry</label>

					<div class='value' data-value='{!! $account->industry_type_id !!}' data-realtime='industry_type_id'>
						{!! non_property_checker($account->industry, 'name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						{!! Form::select('industry_type_id', $industry_types_list, $account->industry_type_id, ['class' => 'form-control select-type-single']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Annual Revenue</label>

					<div class='value' data-value='{!! number_format($account->annual_revenue, 2, '.', '') . '|' . $account->currency_id !!}' data-multiple='true'>
						<span class='symbol'>{!! $account->currency->symbol !!}</span>{!! $account->amountFormat('annual_revenue') !!}
					</div>

					<div class='edit-single left-icon ' icon='{!! currency_icon($account->currency->code, $account->currency->symbol) !!}' alter-icon='{!! $account->currency->symbol !!}' base-id='{!! $base_currency->id !!}' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' class='numeric' name='annual_revenue' value='{!! $account->annual_revenue !!}'>
						
			            <div class='icon clickable amount'>
			            	{!! Form::hidden('currency_id', $account->currency_id, ['class' => 'reset-currency']) !!}
				            <i class='dropdown-toggle {!! currency_icon($account->currency->code, $account->currency->symbol) !!}' data-toggle='dropdown' animation='fadeIn|fadeOut'>{!! is_null(currency_icon($account->currency->code, $account->currency->symbol)) ? $base_currency->symbol : '' !!}</i>
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
					<label>Website</label>

					<div class='value' data-value='{!! $account->website !!}'>
						<a href='{!! quick_url($account->website) !!}' target='_blank'>
							{!! $account->website !!}
						</a>
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='website' value='{!! $account->website !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Account Owner</label>

					<div class='value' data-value='{!! $account->account_owner !!}' data-realtime='account_owner'>
						{!! $account->owner->name !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						{!! Form::select('account_owner', $admin_users_list, $account->account_owner, ['class' => 'form-control select-type-single']) !!}
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
							{!! $account->createdByName() !!}<br>
							<span class='c-shadow sm'>{!! $account->created_ampm !!}</span>
						</p>
					</div>
				</div> <!-- end field -->

				<div class='field'>
					<label>Modified By</label>

					<div class='value' data-realtime='updated_by'>
						<p class='compact'>
							{!! $account->updatedByName() !!}<br>
							<span class='c-shadow sm'>{!! $account->updated_ampm !!}</span>
						</p>	
					</div>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Access</label>

					<div id='access' class='value' data-value='{!! $account->access !!}'>
						{!! $account->access_html !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						{!! Form::select('access', $access_list, $account->access, ['class' => 'form-control select-type-single-b']) !!}
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->
			</div>	
		</div>	
	</div> <!-- end account-info -->

	<div id='address-info' class='full content-section'>
		<div class='col-xs-12'>
			<h4 class='title-sm-bold'>Address Information</h4>
		</div>
		
		<div class='full'>	
			<div class='col-xs-12 col-md-6'>
				<div class='field editable'>
					<label>Street</label>

					<div class='value'>
						{!! $account->street !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='street' value='{!! $account->street !!}'>
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
						{!! $account->state !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='state' value='{!! $account->state !!}'>
						<div class='edit-single-btn'>
							<a class='save-single'>Save</a>
							<a class='cancel-single'>Cancel</a>
						</div>
					</div>

					<a class='edit'><i class='fa fa-pencil'></i></a>
				</div> <!-- end field -->

				<div class='field editable'>
					<label>Country</label>

					<div class='value' data-value='{!! $account->country_code !!}'>
						{!! non_property_checker($account->country, 'ascii_name') !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						{!! Form::select('country_code', $countries_list, $account->country_code, ['class' => 'form-control select-type-single']) !!}
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
						{!! $account->city !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='city' value='{!! $account->city !!}'>
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
						{!! $account->zip !!}
					</div>

					<div class='edit-single' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						<input type='text' name='zip' value='{!! $account->zip !!}'>
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
						{!! $account->description !!}
					</div>

					<div class='edit-single textarea' data-action='{!! route('admin.account.single.update', $account->id) !!}'>
						{!! Form::textarea('description', $account->description, ['rows' => 0]) !!}
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
			Contacts
		</h4>

	     <div class='right-top'>
			<button type='button' class='btn btn-type-a plain add-new-common' data-item='contact' data-action='{!! route('admin.contact.store') !!}' data-content='contact.partials.form' data-default='{!! "account_id:$account->id|street:$account->street|city:$account->city|state:$account->state|zip:$account->zip|country_code:$account->country_code" !!}' save-new='false'>
				<i class='mdi mdi-plus'></i> Add Contact
			</button>
	     </div>

	    <table id='append-contact' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'account-contact-data/' . $account->id !!}' datacolumn='{!! $contacts_table['json_columns'] !!}' databtn='{!! table_showhide_columns($contacts_table) !!}' perpage='10'>
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
</div> <!-- end datatable-container -->	

<div class='full datatable-container'>
	<div class='col-xs-12 col-md-12'>
		<h4 class='title-sm-bold table-title'>
			<a class='title-icon tab-link' tabkey='orgchart' data-toggle='tooltip' data-placement='top' title='Account&nbsp;Hierarchy'><i class='fa fa-sitemap'></i></a> 
			Sub-Accounts
		</h4>

	     <div class='right-top'>
			<button type='button' class='btn btn-type-a plain add-new-common' data-item='account' data-action='{!! route('admin.account.store') !!}' data-content='account.partials.form' data-default='{!! $account->hierarchy_form_default !!}' save-new='false'>
				<i class='mdi mdi-plus'></i> Add Account
			</button>
	     </div>

	    <table id='sub-account' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'sub-account-data/' . $account->id !!}' datacolumn='{!! $sub_accounts_table['json_columns'] !!}' databtn='{!! table_showhide_columns($sub_accounts_table) !!}' perpage='10'>
			<thead>
				<tr>
					<th data-priority='1' data-class-name='all' style='max-width: 210px'>account&nbsp;name</th>
					<th data-priority='3'>phone</th>	
					<th data-priority='4' data-class-name='align-r'>
						<span data-toggle='tooltip' data-placement='top' title='Open&nbsp;deals&nbsp;amount'>open&nbsp;deals&nbsp;amt</span>
					</th> 
					<th data-priority='5' data-class-name='align-r'>invoice</th>
					<th data-priority='6' data-class-name='align-r'>payment</th>
					<th data-priority='2' data-orderable='false' data-class-name='align-r all' class='action-2'></th>       			      			        			
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
							<p>Account Source was updated from Web <span class='bold'>Download</span> to <span class='bold'>Cold Call</span></p>
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
							<p>Account Owner was changed from <span class='bold'>Shaikh Jaber</span> to <span class='bold'>Hasina</span></p>
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

		@include('admin.account.partials.timeline-shortinfo')
	</div>	
</div>