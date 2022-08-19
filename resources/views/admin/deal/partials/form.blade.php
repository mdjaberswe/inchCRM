<div class='modal-body vertical perfectscroll'>
	<div class='full form-group-container'>
		<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
			<div class='form-group'>
				<label for='name'>Deal Name <span class='c-danger'>*</span></label>		
				{!! Form::text('name', null, ['class' => 'form-control']) !!}
				<span field='name' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
			    <label for='amount'>Amount</label>
		        <div class='full left-icon clickable amount' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
		            <i class='dropdown-toggle {!! currency_icon($base_currency->code, $base_currency->symbol) !!}' data-toggle='dropdown' animation='headShake|headShake'>{!! is_null(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>
		            <ul class='dropdown-menu up-caret select sm currency-list'>
		            	<div class='full perfectscroll max-h-100'>
			                {!! $currency_list !!}
			            </div>    
		            </ul>
		            {!! Form::text('amount', null, ['class' => 'form-control']) !!}
		            {!! Form::hidden('currency_id', $base_currency->id) !!}
		            <span field='amount' class='validation-error'></span>
		            <span field='currency_id' class='validation-error'></span>
		        </div>
			</div> <!-- end form-group -->

			<div class='form-group'>
			    <label for='closing_date'>Closing Date</label>
		        <div class='full left-icon'>
		            <i class='fa fa-calendar-times-o'></i>
		            {!! Form::text('closing_date', $closing_date, ['class' => 'form-control datepicker', 'placeholder' => 'yyyy-mm-dd']) !!}
		            <span field='closing_date' class='validation-error'></span>
		        </div>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='deal_pipeline_id'>Pipeline</label>				
				{!! Form::select('deal_pipeline_id', $deal_pipelines_list, $default_pipeline->id, ['class' => 'form-control white-select-type-single', 'data-child-option' => 'deal_stage_id', 'data-url' => route('admin.dealpipeline.stage.dropdown')]) !!}
				<span field='deal_pipeline_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='deal_stage_id'>Stage</label>
				<select name='deal_stage_id' class='form-control white-select-type-single' data-option-related='probability'>
					{!! $default_pipeline->stage_options_html !!}
				</select>
				{!! Form::hidden('deal_stage', 0) !!}
				<span field='deal_stage_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
			    <label for='probability'>Probability</label>			    
		        <div class='full left-icon'>
		            <i class='fa fa-percent'></i>
		            {!! Form::text('probability', $default_stage->probability, ['placeholder' => 'Enter probability', 'class' => 'form-control']) !!}
		            {!! Form::hidden('forecast_percentage', 0) !!}
		            <span field='probability' class='validation-error'></span>
		        </div>    
			</div> <!-- end form-group -->
		</div>	

		<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
			<div class='form-group'>
				<label for='deal_owner'>Deal Owner</label>
				{!! Form::select('deal_owner', $admin_users_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
				<span field='deal_owner' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='account_id'>Account <span class='c-danger'>*</span></label>		
				{!! Form::select('account_id', $accounts_list, null, ['class' => 'form-control white-select-type-single', 'data-append-request' => 'true', 'data-parent' => 'account', 'data-child' => 'contact']) !!}
				<span field='account_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group' data-toggle='tooltip' data-placement='bottom' title='Please specify Account'>
				<label for='contact_id'>Contact</label>		
				{!! Form::select('contact_id', $contacts_list, null, ['class' => 'form-control white-select-type-single', 'data-append' => 'contact', 'disabled' => true]) !!}
				{!! Form::hidden('primary_contact', null, ['data-default' => 'true']) !!}
				<span field='contact_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='deal_type_id'>Deal Type</label>		
				{!! Form::select('deal_type_id', $deal_types_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='deal_type_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='source_id'>Source</label>		
				{!! Form::select('source_id', $sources_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='source_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='campaign_id'>Campaign</label>		
				{!! Form::select('campaign_id', $campaigns_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='campaign_id' class='validation-error'></span>
			</div> <!-- end form-group -->
		</div>	

		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
			<div class='form-group'>
				<label for='description'>Description</label>
		        {!! Form::textarea('description', null, ['class' => 'form-control sm']) !!}
		        <span field='description' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<div class='full show-if' @if(isset($form) && $form == 'create') scroll='true' flush='true' @endif>
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
					<div class='full none'>
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
		</div>	
	</div> <!-- end form-group-container -->	
</div> <!-- end modal-body -->	

@if(isset($form) && $form == 'edit')
    {!! Form::hidden('id', null) !!}
@endif