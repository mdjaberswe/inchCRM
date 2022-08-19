@extends('layouts.master')

@section('content')

	<div class='row'>
	    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
	        <h4 class='title-type-a'>{!! $page['item_title'] !!}</h4>

	        @if($role->fixed == false)
		        <div class='right-top'>
		        	<a href="{!! route('admin.role.edit', $role->id) !!}" class='btn btn-type-a'><i class='fa fa-edit'></i> Edit Role</a>
		        </div>
		    @endif    

	        {!! Form::model($role, ['class' => 'form-type-b render']) !!}

		        <div class='form-group'>
		            <label for='name' class='col-xs-12 col-sm-3 col-md-2 col-lg-2'>Role Name <span class='c-danger'>*</span></label>

		            <div class='col-xs-12 col-sm-9 col-md-10 col-lg-10'>
		            	{!! Form::text('name', isset($role) ? $role->display_name : null, ['class' => 'form-control', 'readonly' => true]) !!}
		            </div>
		        </div> <!-- end form-group -->

		        <div class='form-group'>
		        	{!! Form::label('description', 'Role Description', ['class' => 'col-xs-12 col-sm-3 col-md-2 col-lg-2']) !!}

		        	<div class='col-xs-12 col-sm-9 col-md-10 col-lg-10'>
		        		{!! Form::textarea('description', null, ['class' => 'form-control', 'readonly' => true]) !!}
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
		        					</div>	
		        				</div> <!-- end div-type-title -->

		        				@foreach($permissions_group['modules'] as $module)
		        					<div class='full div-type-b'>
		        						<div class='col-xs-12 col-sm-6 col-md-4 col-lg-4 div-type-c'>
		        							<span class='left-justify para-type-a'>{!! ucfirst($module->display_name) !!}</span>

		        							<label class='right-justify switch'>
		        								@if($permissions_group['module_permissions']['has_permission'][$module->name] == true)
		        									<input type='checkbox' name='permissions[]' value="{!! $module->id !!}" checked disabled>
		        								@else
		        									<input type='checkbox' name='permissions[]' value="{!! $module->id !!}" disabled>
		        								@endif						
		        								<span class='slider round'></span>
		        							</label>
		        						</div> <!-- end div-type-c -->
		        						
		        						@if(count($permissions_group['module_permissions'][$module->name]) > 0)    				
		        		    				{!! display_module_permissions($permissions_group['module_permissions'][$module->name], true) !!}				
		        						@endif
		        					</div>
		        				@endforeach
		        			</div> <!-- end permissions-group -->
		        		@endforeach
		        	</div>
		        </div> <!-- end form-group -->
		    {!! Form::close() !!}    

	    </div> <!-- end div-type-a -->
	</div> <!-- end row -->

@endsection

@push('scripts')
	<script>
		$(document).ready(function()
		{
			$('main').click(function()
			{
				$('.div-type-e').slideUp(100);
			});

			$('main .div-type-d').click(function(e)
			{
				var thisDivPosition = parseInt($(this).offset().top) - parseInt($(this).closest('.div-type-a').offset().top);
				var containerDivHeight = $(this).closest('.div-type-a').height();
				var lowerGap = containerDivHeight - thisDivPosition;
				var comingDivHeight = $(this).find('.div-type-e').height() + 40;

				if(comingDivHeight > lowerGap)
				{
					$(this).find('.div-type-e').css('top', 'auto');
					$(this).find('.div-type-e').css('bottom', '100%');
				}
				
				e.stopPropagation();
				$('.div-type-e').not($(this).children('.div-type-e')).slideUp(100);
				$(this).find('.div-type-e').slideToggle(100);
			});

			$('.div-type-d .div-type-e').click(function(e)
			{
				e.stopPropagation();
			});
		});
	</script>
@endpush