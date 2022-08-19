<a class='modal-image add-multiple {!! (isset($form) && $form == 'create') ? 'far' : null !!}' data-avt='account' data-image='{!! asset('img/company.png') !!}' data-item='image' data-action='{!! route('admin.avatar.upload') !!}' data-content='partials.modals.upload-avatar' data-default='linked_type:account' save-new='false' data-modalsize='sub' modal-footer='hide' modal-files='true' save-txt='Crop and Set' modal-title='Account Image'>
	<img src="{!! asset('img/company.png') !!}" alt='Account Image'/>
	{!! Form::hidden('image', null) !!}
	<span class='icon'><i class='fa fa-camera'></i></span>
</a>

<div class='modal-body vertical perfectscroll'>
	<div class='full form-group-container'>
		<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
			<div class='form-group'>
				<label for='account_name'>Account Name <span class='c-danger'>*</span></label>		
				{!! Form::text('account_name', null, ['class' => 'form-control']) !!}
				<span field='account_name' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='parent_account'>Parent Account</label>
				{!! Form::select('parent_account', $parent_accounts_list, null, ['class' => 'form-control white-select-type-single']) !!}
				{!! Form::hidden('hierarchy_id', null) !!}
				<span field='parent_account' class='validation-error'></span>
				<span field='hierarchy_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='no_of_employees'>No. of Employees</label>		
				{!! Form::text('no_of_employees', null, ['class' => 'form-control']) !!}
				<span field='no_of_employees' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
			    <label for='annual_revenue'>Annual Revenue</label>

		        <div class='full left-icon clickable amount' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
		            <i class='dropdown-toggle {!! currency_icon($base_currency->code, $base_currency->symbol) !!}' data-toggle='dropdown' animation='headShake|headShake'>{!! is_NULL(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>
		            <ul class='dropdown-menu up-caret select sm currency-list'>
		            	<div class='full perfectscroll max-h-100'>
			                {!! $currency_list !!}
			            </div>    
		            </ul>
		            {!! Form::text('annual_revenue', null, ['class' => 'form-control']) !!}
		            {!! Form::hidden('currency_id', $base_currency->id) !!}
		            <span field='annual_revenue' class='validation-error'></span>
		            <span field='currency_id' class='validation-error'></span>
		        </div>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='account_email'>Email</label>		
				{!! Form::text('account_email', null, ['class' => 'form-control']) !!}
				<span field='account_email' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='account_phone'>Phone</label>		
				{!! Form::text('account_phone', null, ['class' => 'form-control']) !!}
				<span field='account_phone' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='fax'>Fax</label>		
				{!! Form::text('fax', null, ['class' => 'form-control']) !!}
				<span field='fax' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='website'>Website</label>		
				{!! Form::text('website', null, ['class' => 'form-control']) !!}
				<span field='website' class='validation-error'></span>
			</div> <!-- end form-group -->
		</div>	

		<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
			<div class='form-group'>
				<label for='account_owner'>Account Owner</label>
				{!! Form::select('account_owner', $admin_users_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
				<span field='account_owner' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='account_type_id'>Account Type</label>		
				{!! Form::select('account_type_id', $account_types_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='account_type_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='industry_type_id'>Industry</label>		
				{!! Form::select('industry_type_id', $industry_types_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='industry_type_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='street'>Street</label>		
				{!! Form::text('street', null, ['class' => 'form-control']) !!}
				<span field='street' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='city'>City</label>		
				{!! Form::text('city', null, ['class' => 'form-control']) !!}
				<span field='city' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='state'>State</label>		
				{!! Form::text('state', null, ['class' => 'form-control']) !!}
				<span field='state' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='zip'>Zip Code</label>		
				{!! Form::text('zip', null, ['class' => 'form-control']) !!}
				<span field='zip' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='country_code'>Country</label>		
				{!! Form::select('country_code', $countries_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='country_code' class='validation-error'></span>
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