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

		<div class='full none account_owner-list'>
			<label for='account_owner' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Account Owner</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('account_owner', $admin_users_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
				<span field='account_owner' class='validation-error'></span>
			</div>
		</div> <!-- end account owner -->	

		<div class='full none account_type_id-list'>
			<label for='account_type_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Account Type</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('account_type_id', $account_types_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='account_type_id' class='validation-error'></span>
			</div>
		</div> <!-- end account type -->

		<div class='full none city-list'>
			<label for='city' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>City</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('city', null, ['class' => 'form-control']) !!}
				<span field='city' class='validation-error'></span>
			</div>
		</div> <!-- end city -->	

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

		<div class='full none account_email-list'>
			<label for='account_email' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Email</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('account_email', null, ['class' => 'form-control']) !!}
				<span field='account_email' class='validation-error'></span>
			</div>
		</div> <!-- end phone -->	

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

		<div class='full none industry_type_id-list'>
			<label for='industry_type_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Industry</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('industry_type_id', $industry_types_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='industry_type_id' class='validation-error'></span>
			</div>
		</div> <!-- end account type -->

		<div class='full none no_of_employees-list'>
			<label for='no_of_employees' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>No. of Employees</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('no_of_employees', null, ['class' => 'form-control']) !!}
				<span field='no_of_employees' class='validation-error'></span>
			</div>
		</div> <!-- end employee -->	

		<div class='full none parent_id-list'>
			<label for='parent_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Parent Account</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('parent_id', $parent_accounts_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='parent_id' class='validation-error'></span>
			</div>
		</div> <!-- end account -->

		<div class='full none account_phone-list'>
			<label for='account_phone' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Phone</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('account_phone', null, ['class' => 'form-control']) !!}
				<span field='account_phone' class='validation-error'></span>
			</div>
		</div> <!-- end phone -->	

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