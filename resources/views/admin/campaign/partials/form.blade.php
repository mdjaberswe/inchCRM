<div class='modal-body perfectscroll'>
	<div class='form-group'>
		<label for='name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Campaign Name <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::text('name', null, ['class' => 'form-control']) !!}
			<span field='name' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='campaign_owner' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Campaign Owner</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('campaign_owner', $campaign_owners_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
			<span field='campaign_owner' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='campaign_type' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Type</label>
		
		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('campaign_type', $type_list, null, ['class' => 'form-control white-select-type-single']) !!}
			<span field='campaign_type' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='status' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Status</label>
		
		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('status', $status_list, null, ['class' => 'form-control white-select-type-single-b']) !!}
			<span field='status' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='date' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Date</label>
		
		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			<div class='full'>
				<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
					<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='Start Date'>
						<i class='fa fa-calendar-check-o'></i>
						{!! Form::text('start_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Start Date']) !!}
						<span field='start_date' class='validation-error'></span>
					</div> <!-- end form-group -->
				</div>

				<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
					<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='End Date'>
						<i class='fa fa-calendar-times-o'></i>
						{!! Form::text('end_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'End Date']) !!}
						<span field='end_date' class='validation-error'></span>
					</div> <!-- end form-group -->
				</div>
			</div>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='date' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Activity</label>
		
		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			<div class='full'>
				<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
					<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='Numbers Sent'>
						<i class='fa fa-send'></i>
						{!! Form::text('numbers_sent', null, ['class' => 'form-control', 'placeholder' => 'Numbers Sent']) !!}
						<span field='numbers_sent' class='validation-error'></span>
					</div> <!-- end form-group -->
				</div>

				<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
					<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='Expected Response'>
						<i class='fa fa-percent'></i>
						{!! Form::text('expected_response', null, ['class' => 'form-control', 'placeholder' => 'Expected Response']) !!}
						<span field='expected_response' class='validation-error'></span>
					</div> <!-- end form-group -->
				</div>
			</div>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='finance' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Finance</label>
		
		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			<div class='full'>
				<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 div-double-input'>
					<div class='full left-icon clickable amount' data-toggle='tooltip' data-placement='top' title='Expected Revenue' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
			            <i class='dropdown-toggle {!! currency_icon($base_currency->code, $base_currency->symbol) !!}' data-toggle='dropdown' animation='headShake|headShake'>{!! is_null(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>
			            <ul class='dropdown-menu up-caret select sm currency-list global'>
			            	<div class='full perfectscroll max-h-100'>
				                {!! $currency_list !!}
				            </div>    
			            </ul>
						{!! Form::text('expected_revenue', null, ['class' => 'form-control', 'placeholder' => 'Expected Revenue']) !!}
						{!! Form::hidden('currency_id', $base_currency->id) !!}
						<span field='expected_revenue' class='validation-error'></span>
						<span field='currency_id' class='validation-error'></span>
					</div>
				</div>

				<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 div-double-input triple'>
					<div class='full left-icon clickable amount' data-toggle='tooltip' data-placement='top' title='Budgeted Cost' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
			            <i class='dropdown-toggle {!! currency_icon($base_currency->code, $base_currency->symbol) !!}' data-toggle='dropdown' animation='headShake|headShake'>{!! is_null(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>
			            <ul class='dropdown-menu up-caret select sm currency-list global'>
			            	<div class='full perfectscroll max-h-100'>
				                {!! $currency_list !!}
				            </div>    
			            </ul>
						{!! Form::text('budgeted_cost', null, ['class' => 'form-control', 'placeholder' => 'Budgeted Cost']) !!}
						<span field='budgeted_cost' class='validation-error'></span>
					</div>
				</div>

				<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 div-double-input'>
					<div class='full left-icon clickable amount' data-toggle='tooltip' data-placement='top' title='Actual Cost' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
			            <i class='dropdown-toggle {!! currency_icon($base_currency->code, $base_currency->symbol) !!}' data-toggle='dropdown' animation='headShake|headShake'>{!! is_null(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>
			            <ul class='dropdown-menu up-caret select sm currency-list global'>
			            	<div class='full perfectscroll max-h-100'>
				                {!! $currency_list !!}
				            </div>    
			            </ul>
						{!! Form::text('actual_cost', null, ['class' => 'form-control', 'placeholder' => 'Actual Cost']) !!}
						<span field='actual_cost' class='validation-error'></span>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='description' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Description</label>

	    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
	        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
	        <span field='description' class='validation-error'></span>
	    </div>
	</div> <!-- end form-group -->
</div> <!-- end modal-body -->	

@if(isset($form) && $form == 'edit')
    {!! Form::hidden('id', null) !!}
@endif