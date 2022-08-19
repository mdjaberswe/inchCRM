<div class='modal-body perfectscroll'>
    <div class='form-group'>
        <label for='name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Name <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::text('name', null, ['placeholder' => 'Enter deal stage name', 'class' => 'form-control']) !!}
            <span field='name' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='category' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Sales Plan Category <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            <select name='category' class='form-control white-select-type-single-b' data-option-related='probability'>
                <option value='open'>Open</option>
                <option value='closed_won' relatedval='100' freeze='true'>Closed Won</option>
                <option value='closed_lost' relatedval='0' freeze='true'>Closed Lost</option>
            </select>
            <span field='category' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='probability' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Probability <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            <div class='full left-icon'>
                <i class='fa fa-percent'></i>
                {!! Form::text('probability', null, ['placeholder' => 'Enter probability', 'class' => 'form-control']) !!}
                <span field='probability' class='validation-error'></span>
            </div>    
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='position' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Position <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::select('position', $position_list, $max_position_id, ['class' => 'form-control position white-select-type-single']) !!}
            <span field='position' class='validation-error'></span>
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