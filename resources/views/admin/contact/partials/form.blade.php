<a class='modal-image add-multiple {!! (isset($form) && $form == 'create') ? 'far' : null !!}' data-avt='contact' data-image='{!! asset('img/avatar.png') !!}' data-item='image' data-action='{!! route('admin.avatar.upload') !!}' data-content='partials.modals.upload-avatar' data-default='linked_type:contact' save-new='false' data-modalsize='sub' modal-footer='hide' modal-files='true' save-txt='Crop and Set' modal-title='Contact Image'>
	<img src="{!! asset('img/avatar.png') !!}"/>
	{!! Form::hidden('image', null) !!}
	<span class='icon'><i class='fa fa-camera'></i></span>
</a>

<div class='modal-body vertical perfectscroll' onscroll='modalBodyScroll(this)'>
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
				<label for='email'>Email <span class='c-danger'>*</span></label>		
				{!! Form::text('email', null, ['class' => 'form-control']) !!}
				<span field='email' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
			    <label for='password'>Password @if(isset($form) && $form == 'create')<span class='c-danger'>*</span>@endif</label>

			    <div class='full div-type-h'>
			        <a data-toggle='tooltip' data-placement='top' title='Password' class='password-generator tooltip-right-10'><i class='fa fa-key'></i></a>
			        <a data-toggle='tooltip' data-placement='top' title='Show Password' class='show-password'><i class='fa fa-eye'></i></a>
			        <input type='password' name='password' class='form-control password' placeholder='{!! (isset($form) && $form == 'edit') ? 'Leave blank if you don&apos;t want to change it' : '' !!}'>
			        <span field='password' class='validation-error'></span>
			    </div>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='phone'>Phone</label>		
				{!! Form::text('phone', null, ['class' => 'form-control']) !!}
				<span field='phone' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='fax'>Fax</label>		
				{!! Form::text('fax', null, ['class' => 'form-control']) !!}
				<span field='fax' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='date_of_birth'>Date of Birth</label>		
				{!! Form::text('date_of_birth', null, ['class' => 'form-control datepicker']) !!}
				<span field='date_of_birth' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
			    <label for='permissions'>Client Portal Permissions</label>

		        <div class='inline-input space'>
		        	@foreach($client_roles as $module => $roles)
		        		<div class='toggle-permission'>
		        			<div class='parent-permission'>
		        				<span>{!! ucfirst($module) !!}</span>

		        				<label class='switch'>
		        					<input type='checkbox' name='{!! $module . '_access' !!}' value='1' checked>
		        					<span class='slider round'></span>
		        				</label>
		        			</div>

		        			<div class='child-permission'>	        				
		        				@foreach($roles as $role)
		        					<div class='inline-info'>
				        				<p class='pretty top-space info smooth'>
				        				    <input type='radio' name='{!! $module . '_role' !!}' value='{!! $role->id !!}' @if(!(strpos($role->name, 'view_all') !== false)) data-default='true' checked @endif>
				        				    <label><i class='mdi mdi-check'></i></label> {!! $role->display_name !!}		        					
				        				</p>

				        				@if(strpos($role->name, 'view_all') !== false)
			        						<i class='tik-info fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='{!! 'View&nbsp;all&nbsp;' . $module . 's&nbsp;realted&nbsp;with the&nbsp;key&nbsp;account' !!}'></i>
		        						@else
		        							<i class='tik-info fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='{!! 'View&nbsp;' . $module . 's&nbsp;realted&nbsp;with the&nbsp;contact' !!}'></i>
		        						@endif
		        					</div>
		        				@endforeach
		        			</div>
		        		</div>	
		        	@endforeach 				
		        </div>  

		        <div class='full'>
		        	<span field='deal_role' class='validation-error block'></span>
		        	<span field='project_role' class='validation-error block'></span>
		        	<span field='estimate_role' class='validation-error block'></span>
		        	<span field='invoice_role' class='validation-error block'></span>
		        </div>	
			</div> <!-- end form-group -->


		</div>	

		<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
			<div class='form-group'>
				<label for='contact_owner'>Contact Owner</label>
				{!! Form::select('contact_owner', $admin_users_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
				<span field='contact_owner' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='account_id'>Account Name <span class='c-danger'>*</span></label>		
				{!! Form::select('account_id', $accounts_list, null, ['class' => 'form-control white-select-type-single', 'data-append-request' => 'true', 'data-parent' => 'account', 'data-child' => 'contact']) !!}
				<span field='account_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group' data-toggle='tooltip' data-placement='bottom' title='Please specify Account Name'>
				<label for='supervisor'>Reporting To</label>		
				{!! Form::select('supervisor', $contacts_list, null, ['class' => 'form-control white-select-type-single', 'data-append' => 'contact', 'disabled' => true]) !!}
				{!! Form::hidden('parent_id', null, ['data-default' => 'true']) !!}
				{!! Form::hidden('invalid_parent_id', null, ['data-invalid' => 'true']) !!}
				<span field='supervisor' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='contact_type_id'>Contact Type</label>		
				{!! Form::select('contact_type_id', $contact_types_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='contact_type_id' class='validation-error'></span>
			</div> <!-- end form-group -->

			<div class='form-group'>
				<label for='source_id'>Source</label>		
				{!! Form::select('source_id', $sources_list, null, ['class' => 'form-control white-select-type-single']) !!}
				<span field='source_id' class='validation-error'></span>
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