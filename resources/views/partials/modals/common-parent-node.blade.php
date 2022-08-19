<div class='modal-body perfectscroll'>                                    
    <div class='form-group'>
        <label for='parent_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Parent {!! ucfirst($module_name) !!}</label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::select('parent_id', ['' => '-None-'], null, ['class' => 'form-control white-select-type-single']) !!}
            
            @if($have_child)
	            <div class='full'>
	            	<p class='pretty top-space info smooth'>
	            	    <input type='checkbox' name='confirmation' value='all' checked>
	            	    <label><i class='mdi mdi-check'></i></label> Change parent along with all its childs node hierarchy
	            	</p> 
	            </div>
	        @endif    

            <div class='full'>
                <span field='parent_id' class='validation-error block'></span>
                <span field='module_id' class='validation-error block'></span>
                <span field='module_name' class='validation-error block'></span>
                <span field='hierarchy_id' class='validation-error block'></span>      
            </div>    
        </div>
    </div> <!-- end form-group -->
</div> <!-- end modal-body -->

{!! Form::hidden('module_id', null) !!}
{!! Form::hidden('module_name', null) !!}
{!! Form::hidden('hierarchy_id', null) !!}