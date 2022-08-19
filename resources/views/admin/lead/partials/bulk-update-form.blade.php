<div class='modal-body perfectscroll'>
	<div class='form-group show-if multiple'>
		<label for='related' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Field Name</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('related', $lead_field_list, null, ['class' => 'form-control white-select-type-single']) !!}
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

		<div class='full none city-list'>
			<label for='city' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>City</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('city', null, ['class' => 'form-control']) !!}
				<span field='city' class='validation-error'></span>
			</div>
		</div> <!-- end city -->	

		<div class='full none company-list'>
			<label for='company' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Company</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('company', null, ['class' => 'form-control']) !!}
				<span field='company' class='validation-error'></span>
			</div>
		</div> <!-- end company -->	

		<div class='full none country_code-list'>
			<label for='country_code' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Country</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('country_code', $countries_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='country_code' class='validation-error'></span>
			</div>
		</div> <!-- end country dropdown -->	

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
			<label for='source_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Lead Source</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('source_id', $sources_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='source_id' class='validation-error'></span>
			</div>
		</div> <!-- end lead source dropdown -->	

		<div class='full none lead_stage_id-list'>
			<label for='lead_stage_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Lead Stage</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('lead_stage_id', $lead_stages_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='lead_stage_id' class='validation-error'></span>
			</div>
		</div> <!-- end lead stage dropdown -->	

		<div class='full none no_of_employees-list'>
			<label for='no_of_employees' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>No. of Employees</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('no_of_employees', null, ['class' => 'form-control']) !!}
				<span field='no_of_employees' class='validation-error'></span>
			</div>
		</div> <!-- end employee -->	

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