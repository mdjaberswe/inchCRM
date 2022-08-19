<a class='modal-image add-multiple {!! (isset($form) && $form == 'edit') ? 'near' : null !!}' data-avt='lead' data-image='{!! asset('img/avatar.png') !!}' data-item='image' data-action='{!! route('admin.avatar.upload') !!}' data-content='partials.modals.upload-avatar' data-default='linked_type:lead' save-new='false' data-modalsize='sub' modal-footer='hide' modal-files='true' save-txt='Crop and Set' modal-title='Lead Image'>
	<img src="{!! asset('img/avatar.png') !!}" alt='Lead Image'/>
	{!! Form::hidden('image', null) !!}
	<span class='icon'><i class='fa fa-camera'></i></span>
</a>

<div class='modal-body vertical perfectscroll'>
	<div class='full form-group-container'>
		<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
			<div class='form-group'>
				<label for='first_name'>First Name</label>		
				{!! Form::text('first_name', null, ['class' => 'form-control']) !!}
				<span field='first_name' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='last_name'>Last Name <span class='c-danger'>*</span></label>		
				{!! Form::text('last_name', null, ['class' => 'form-control']) !!}
				<span field='last_name' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='title'>Job Title</label>		
				{!! Form::text('title', null, ['class' => 'form-control']) !!}
				<span field='title' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='email'>Email</label>		
				{!! Form::text('email', null, ['class' => 'form-control']) !!}
				<span field='email' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='phone'>Phone</label>		
				{!! Form::text('phone', null, ['class' => 'form-control']) !!}
				<span field='phone' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='company'>Company</label>		
				{!! Form::text('company', null, ['class' => 'form-control']) !!}
				<span field='company' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='no_of_employees'>No. of Employees</label>		
				{!! Form::text('no_of_employees', null, ['class' => 'form-control']) !!}
				<span field='no_of_employees' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
			    <label for='annual_revenue'>Annual Revenue</label>

		        <div class='full left-icon clickable amount' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
		            <i class='dropdown-toggle {!! currency_icon($base_currency->code, $base_currency->symbol) !!}' data-toggle='dropdown' animation='headShake|headShake'>{!! is_null(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>
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
		</div>	

		<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
			<div class='form-group'>
				<label for='lead_owner'>Lead Owner</label>
				{!! Form::select('lead_owner', $admin_users_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
				<span field='lead_owner' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='lead_stage_id'>Lead Stage <span class='c-danger'>*</span></label>		
				{!! Form::select('lead_stage_id', $lead_stages_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='lead_stage_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='source_id'>Lead Source</label>		
				{!! Form::select('source_id', $sources_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='source_id' class='validation-error'></span>
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