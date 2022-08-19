<div class='modal-body perfectscroll'>
	<div class='form-group show-if multiple'>
		<label for='related' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Field Name</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('related', $field_list, null, ['class' => 'form-control white-select-type-single']) !!}
			<span field='related' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group related-input none'>
		<div class='full none amount-list'>
			<label for='amount' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Amount</label>

		    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
		        <div class='full left-icon clickable amount dropup' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
		            <i class='dropdown-toggle {!! currency_icon($base_currency->code, $base_currency->symbol) !!}' data-toggle='dropdown' animation='fadeIn|fadeOut'>{!! is_null(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>
		            <ul class='dropdown-menu down-caret select sm currency-list'>
		            	<div class='full perfectscroll max-h-50'>
			                {!! $currency_list !!}
			            </div>    
		            </ul>
		            {!! Form::text('amount', null, ['class' => 'form-control']) !!}
		            {!! Form::hidden('currency_id', $base_currency->id) !!}
		            <span field='amount' class='validation-error'></span>
		            <span field='currency_id' class='validation-error'></span>
		        </div>
		    </div>
		</div> <!-- end amount -->	

		<div class='full none closing_date-list'>
		    <label for='closing_date' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Closing Date</label>
	        
	        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
		        <div class='full left-icon'>
		            <i class='fa fa-calendar-times-o'></i>
		            {!! Form::text('closing_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'yyyy-mm-dd']) !!}
		            <span field='closing_date' class='validation-error'></span>
		        </div>
		    </div>    
		</div> <!-- end closing_date -->

		<div class='full none name-list'>
			<label for='name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Deal Name</label>		
			
			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::text('name', null, ['class' => 'form-control']) !!}
				<span field='name' class='validation-error'></span>
			</div>	
		</div> <!-- end name -->

		<div class='full none deal_pipeline_id-list'>
			<label for='deal_pipeline_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Pipeline</label>				
			
			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('deal_pipeline_id', $deal_pipelines_list, $default_pipeline->id, ['class' => 'form-control white-select-type-single']) !!}
				<span field='deal_pipeline_id' class='validation-error'></span>
			</div>
		</div> <!-- end deal pipeline id -->

		<div class='full none deal_stage_id-list'>
			<label for='deal_stage_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Stage</label>
			
			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('deal_stage_id', $all_stages_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span class='modal-hint block'>* Only stage related pipelines deals will be updated</span>
				<span field='deal_stage_id' class='validation-error'></span>
			</div>	
		</div> <!-- end deal stage id -->

		<div class='full none probability-list'>
		    <label for='probability' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Probability</label>			    
	        
		    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
		        <div class='full left-icon'>
		            <i class='fa fa-percent'></i>
		            {!! Form::text('probability', null, ['class' => 'form-control']) !!}
		            <span field='probability' class='validation-error'></span>
		        </div>
		    </div>    
		</div> <!-- end probability -->

		<div class='full none access-list'>
			<label for='access' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Access</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('access', ['private' => 'Private', 'public' => 'Public Read Only', 'public_rwd' => 'Public Read/Write/Delete'], null, ['class' => 'form-control white-select-type-single-b']) !!}
				<span field='access' class='validation-error'></span>
			</div>
		</div> <!-- end access -->	

		<div class='full none deal_owner-list'>
			<label for='deal_owner' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Deal Owner</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('deal_owner', $admin_users_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
				<span field='deal_owner' class='validation-error'></span>
			</div>
		</div> <!-- end deal owner -->	

		<div class='full none account_id-list'>
			<label for='account_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Account</label>		
			
			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('account_id', $accounts_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='account_id' class='validation-error'></span>
			</div>
		</div> <!-- end account id -->

		<div class='full none contact_id-list'>
			<label for='contact_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Contact</label>		
			
			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('contact_id', $contacts_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span class='modal-hint block'>* Only contact related account deals will be updated</span>
				<span field='contact_id' class='validation-error'></span>
			</div>	
		</div> <!-- end contact id -->

		<div class='full none deal_type_id-list'>
			<label for='deal_type_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Deal Type</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('deal_type_id', $deal_types_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='deal_type_id' class='validation-error'></span>
			</div>
		</div> <!-- end deal type -->

		<div class='full none source_id-list'>
			<label for='source_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Source</label>		
			
			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('source_id', $sources_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='source_id' class='validation-error'></span>
			</div>	
		</div> <!-- end source id -->

		<div class='full none campaign_id-list'>
			<label for='campaign_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Campaign</label>		
			
			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::select('campaign_id', $campaigns_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='campaign_id' class='validation-error'></span>
			</div>	
		</div> <!-- end campaign id -->

		<div class='full none description-list'>
			<label for='description' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Description</label>

			<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
				{!! Form::textarea('description', null, ['class' => 'form-control sm']) !!}
				<span field='description' class='validation-error'></span>
			</div>
		</div> <!-- end description -->	
	</div> <!-- end form-group -->
</div> <!-- end modal-body -->