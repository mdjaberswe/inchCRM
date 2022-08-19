<div class='modal-body perfectscroll'>
    <div class='form-group'>
        <label for='name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Name <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::text('name', null, ['placeholder' => 'Enter payment method name', 'class' => 'form-control']) !!}
            <span field='name' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='description' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Description</label>

        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
            <span field='description' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <div class='col-xs-12 col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9 col-lg-offset-3 col-lg-9'>
            <p class='pretty info smooth'>
                <input type='checkbox' name='status' value='1' checked>
                <label><i class='mdi mdi-check'></i></label>
                <span class='c-on-white-bg'>Active</span>
            </p> 
            <span field='status' class='validation-error'></span>   
        </div>
    </div> <!-- end form-group -->
</div> <!-- end modal-body -->

@if(isset($form) && $form == 'edit')
    {!! Form::hidden('id', null) !!}
@endif