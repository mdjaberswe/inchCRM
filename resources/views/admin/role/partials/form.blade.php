<div class='form-group'>
    <label for='name' class='col-xs-12 col-sm-3 col-md-2 col-lg-2'>Role Name <span class='c-danger'>*</span></label>

    <div class='col-xs-12 col-sm-9 col-md-10 col-lg-10'>
    	{!! Form::text('name', isset($role) ? $role->display_name : null, ['class' => 'form-control']) !!}
    	<span error-field='name' class='validation-error'>{!! $errors->first('name', ':message') !!}</span>
    </div>
</div> <!-- end form-group -->

<div class='form-group'>
	{!! Form::label('description', 'Role Description', ['class' => 'col-xs-12 col-sm-3 col-md-2 col-lg-2']) !!}

	<div class='col-xs-12 col-sm-9 col-md-10 col-lg-10'>
		{!! Form::textarea('description', null, ['class' => 'form-control']) !!}
		<span error-field='description' class='validation-error'>{!! $errors->first('description', ':message') !!}</span>
	</div>
</div> <!-- end form-group -->

<div class='form-group'>
	{!! Form::label('permissions', 'Permissions', ['class' => 'col-xs-12 col-sm-3 col-md-2 col-lg-2']) !!}

	<div class='col-xs-12 col-sm-9 col-md-10 col-lg-10'>
		@foreach($permissions_groups as $permissions_group)
			<div class='full permissions-group'>
				<div class='full div-type-title'>
					<div class='col-xs-12 col-sm-6 col-md-4 col-lg-4 toggle-header'>
						<h4 class='left-justify title-type-b'>{!! $permissions_group['display_name'] !!} Permissions</h4>

						<label class='right-justify switch all' data-toggle='tooltip' data-placement='top' title='All On/Off'>
							<input type='checkbox' @if($permissions_group['all_checked']){!! 'checked' !!}@endif>
							<span class='slider round'></span>
						</label>
					</div>	
				</div> <!-- end div-type-title -->

				@foreach($permissions_group['modules'] as $module)
					<div class='full div-type-b'>
						<div class='col-xs-12 col-sm-6 col-md-4 col-lg-4 div-type-c'>
							<span class='left-justify para-type-a'>{!! ucfirst($module->display_name) !!}</span>

							<label class='right-justify switch'>
								@if($permissions_group['module_permissions']['has_permission'][$module->name] == true)
									<input type='checkbox' name='permissions[]' value="{!! $module->id !!}" checked>
								@else
									<input type='checkbox' name='permissions[]' value="{!! $module->id !!}">
								@endif						
								<span class='slider round'></span>
							</label>
						</div> <!-- end div-type-c -->
						
						@if(count($permissions_group['module_permissions'][$module->name]) > 0)    				
		    				{!! display_module_permissions($permissions_group['module_permissions'][$module->name]) !!}				
						@endif
					</div>
				@endforeach
			</div> <!-- end permissions-group -->
		@endforeach
	</div>
</div> <!-- end form-group -->

@if(isset($role->id))
	{!! Form::hidden('id', $role->id) !!}
@endif

<div class='form-group'>
    <div class='col-xs-12 col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10 col-lg-offset-2 col-lg-10'>
        {!! Form::submit('Save', ['name' => 'save', 'id' => 'save', 'class' => 'btn btn-primary']) !!}
        @if(!isset($role))
        	{!! Form::hidden('add_new', 0) !!}
        	{!! Form::submit('Save and New', ['name' => 'save_and_new', 'id' => 'save-and-new', 'class' => 'btn btn-secondary']) !!}
        @endif
        {!! link_to_route('admin.role.index', 'Cancel', [], ['class' => 'btn btn-secondary']) !!}
    </div>
</div> <!-- end form-group -->