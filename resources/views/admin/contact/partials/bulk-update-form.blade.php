<div class='modal-body perfectscroll'>
	<div class='form-group show-if multiple'>
		<label for='related' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Field Name</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('related', $field_list, null, ['class' => 'form-control white-select-type-single']) !!}
			<span field='related' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group related-input none'>
		<div class='full none annual_revenue-list'>
			<label for='annual_revenue' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Annual Revenue</label>

		    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
		        <div class='full left-icon clickable amount dropup' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
		            <i class='dropdown-toggle {!! currency_icon($base_currency->code, $base_currency->symbol) !!}' data-toggle='dropdown' animation='fadeIn|fadeOut'>{!! is_null(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>
		            <ul class='dropdown-menu down-caret select sm currency-list'>
		            	<div class='full perfectscroll max-h-50'>
			                {!! $currency_list !!}
			            </div>    
		            </ul>
		            {!! Form::text('annual_revenue', null, ['class' => 'form-control']) !!}
		            {!! Form::hidden('currency_id', $base_currency->id) !!}
		            <span field='annual_revenue' class='validation-error'></span>
		            <span field='currency_id' class='validation-error'></span>
		        </div>
		    </div>
		</div> <!-- end annual revenue -->	
		
		<div class='full none access-list'>
			<label for='access' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Access</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('access', ['private' => 'Private', 'public' => 'Public Read Only', 'public_rwd' => 'Public Read/Write/Delete'], null, ['class' => 'form-control white-select-type-single-b']) !!}
				<span field='access' class='validation-error'></span>
			</div>
		</div> <!-- end access -->	

		<div class='full none account_id-list'>
			<label for='account_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Account</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('account_id', $accounts_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='account_id' class='validation-error'></span>
			</div>
		</div> <!-- end account -->

		<div class='full none city-list'>
			<label for='city' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>City</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('city', null, ['class' => 'form-control']) !!}
				<span field='city' class='validation-error'></span>
			</div>
		</div> <!-- end city -->	

		<div class='full none contact_owner-list'>
			<label for='contact_owner' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Contact Owner</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('contact_owner', $admin_users_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
				<span field='contact_owner' class='validation-error'></span>
			</div>
		</div> <!-- end contact owner -->	

		<div class='full none parent_id-list'>
			<label for='parent_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Reporting To</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('parent_id', $contacts_info_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span class='modal-hint block'>* Only 'Reporting To' related account contacts will be updated</span>
				<span field='parent_id' class='validation-error'></span>
			</div>
		</div> <!-- end contact type -->	

		<div class='full none contact_type_id-list'>
			<label for='contact_type_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Contact Type</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('contact_type_id', $contact_types_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='contact_type_id' class='validation-error'></span>
			</div>
		</div> <!-- end contact type -->	

		<div class='full none country_code-list'>
			<label for='country_code' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Country</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('country_code', $countries_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='country_code' class='validation-error'></span>
			</div>
		</div> <!-- end country dropdown -->	

		<div class='full none description-list'>
			<label for='description' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Description</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::textarea('description', null, ['class' => 'form-control sm']) !!}
				<span field='description' class='validation-error'></span>
			</div>
		</div> <!-- end description -->	

		<div class='full none facebook-list'>
			<label for='facebook' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Facebook</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('facebook', null, ['class' => 'form-control']) !!}
				<span field='facebook' class='validation-error'></span>
			</div>
		</div> <!-- end facebook -->	

		<div class='full none fax-list'>
			<label for='fax' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Fax</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('fax', null, ['class' => 'form-control']) !!}
				<span field='fax' class='validation-error'></span>
			</div>
		</div> <!-- end fax -->	

		<div class='full none phone-list'>
			<label for='phone' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Phone</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('phone', null, ['class' => 'form-control']) !!}
				<span field='phone' class='validation-error'></span>
			</div>
		</div> <!-- end phone -->	

		<div class='full none source_id-list'>
			<label for='source_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Source</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('source_id', $sources_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='source_id' class='validation-error'></span>
			</div>
		</div> <!-- end source dropdown -->	

		<div class='full none status-list'>
			<label for='status' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Status</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('status', ['1' => 'Active', '0' => 'Inactive'], null, ['class' => 'form-control white-select-type-single-b']) !!}
				<span field='status' class='validation-error'></span>
			</div>
		</div> <!-- end status -->

		<div class='full none skype-list'>
			<label for='skype' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Skype</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('skype', null, ['class' => 'form-control']) !!}
				<span field='skype' class='validation-error'></span>
			</div>
		</div> <!-- end skype -->	

		<div class='full none state-list'>
			<label for='state' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>State</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('state', null, ['class' => 'form-control']) !!}
				<span field='state' class='validation-error'></span>
			</div>
		</div> <!-- end state -->	

		<div class='full none street-list'>
			<label for='street' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Street</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('street', null, ['class' => 'form-control']) !!}
				<span field='street' class='validation-error'></span>
			</div>
		</div> <!-- end street -->	

		<div class='full none title-list'>
			<label for='title' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Job Title</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('title', null, ['class' => 'form-control']) !!}
				<span field='title' class='validation-error'></span>
			</div>
		</div> <!-- end title -->	

		<div class='full none twitter-list'>
			<label for='twitter' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Twitter</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('twitter', null, ['class' => 'form-control']) !!}
				<span field='twitter' class='validation-error'></span>
			</div>
		</div> <!-- end title -->	

		<div class='full none website-list'>
			<label for='website' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Website</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('website', null, ['class' => 'form-control']) !!}
				<span field='website' class='validation-error'></span>
			</div>
		</div> <!-- end website -->	

		<div class='full none zip-list'>
			<label for='zip' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Zip Code</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('zip', null, ['class' => 'form-control']) !!}
				<span field='zip' class='validation-error'></span>
			</div>
		</div> <!-- end zip -->
	</div> <!-- end form-group -->
</div> <!-- end modal-body -->