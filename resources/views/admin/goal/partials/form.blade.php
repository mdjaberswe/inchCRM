<div class='modal-body perfectscroll'>
	<div class='form-group'>
		<label for='goal_owner' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Goal Owner</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('goal_owner', $goal_owners_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
			<span field='goal_owner' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Goal Name <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::text('name', null, ['class' => 'form-control']) !!}
			<span field='name' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='start_date' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Date <span class='c-danger'>*</span></label>
		
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
		<label for='deals_count' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Deals Count</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::text('deals_count', null, ['class' => 'form-control', 'placeholder' => 'No. of Deals']) !!}
			<span field='deals_count' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
	    <label for='sales_amount' class='col-xs-12 col-sm-3 col-md-3 col-lg-3 lines'>Sales Amount<br><span class='dark-hint'>(won deals amount)</span></label>

	    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
	        <div class='full left-icon clickable amount' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
	            <i class='dropdown-toggle {!! currency_icon($base_currency->code, $base_currency->symbol) !!}' data-toggle='dropdown' animation='headShake|headShake'>{!! is_null(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>
	            <ul class='dropdown-menu up-caret select sm currency-list'>
	            	<div class='full perfectscroll max-h-100'>
		                {!! $currency_list !!}
		            </div>    
	            </ul>
	            {!! Form::text('sales_amount', null, ['class' => 'form-control']) !!}
	            {!! Form::hidden('currency_id', $base_currency->id) !!}
	            <span field='sales_amount' class='validation-error'></span>
	            <span field='currency_id' class='validation-error'></span>
	        </div>
	    </div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='leads_count' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Leads Count</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::text('leads_count', null, ['class' => 'form-control', 'placeholder' => 'No. of Leads']) !!}
			<span field='leads_count' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='accounts_count' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Accounts Count</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::text('accounts_count', null, ['class' => 'form-control', 'placeholder' => 'No. of Accounts']) !!}
			<span field='accounts_count' class='validation-error'></span>
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