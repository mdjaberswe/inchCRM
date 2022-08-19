<div class='modal-body perfectscroll'>
    <div class='form-group'>
        <label for='name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Currency Name <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::text('name', null, ['placeholder' => 'Enter currency name', 'class' => 'form-control']) !!}
            <span field='name' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='code' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Currency Code <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::text('code', null, ['placeholder' => 'ISO Code', 'class' => 'form-control currency-code']) !!}
            <span field='code' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='exchange_rate' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Exchange Rate (default) <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            <div class='full'>
                <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 div-double-input aside'>
                    <div class='full right-icon'>
                        <span class='icon-txt face-code'></span>
                        <span class='symbol'>=</span>
                        {!! Form::text('face_value', 1, ['class' => 'form-control']) !!}
                        <span field='face_value' class='validation-error'></span>
                    </div> <!-- end form-group -->
                </div>

                <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 div-double-input aside'>
                    <div class='full right-icon'>
                        <span class='icon-txt base-code'>{!! $base_currency->code !!}</span>
                        {!! Form::text('exchange_rate', null, ['class' => 'form-control']) !!}
                        <span field='exchange_rate' class='validation-error'></span>
                    </div> <!-- end form-group -->
                </div>
            </div>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='symbol' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Currency Symbol <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::text('symbol', null, ['class' => 'form-control symbol']) !!}
            <span field='symbol' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='symbol_position' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Symbol Position</label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::select('symbol_position', ['before' => 'Before amount', 'after' => 'After amount'], null, ['class' => 'form-control white-select-type-single-b']) !!}
            <span field='symbol_position' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='decimal_separator' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Decimal Separator</label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::select('decimal_separator', $separators_list, '.', ['class' => 'form-control white-select-type-single-b']) !!}
            <span field='decimal_separator' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='thousand_separator' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Thousand Separator</label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::select('thousand_separator', $separators_list, ',', ['class' => 'form-control white-select-type-single-b']) !!}
            <span field='thousand_separator' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->
</div> <!-- end modal-body -->

@if(isset($form) && $form == 'edit')
    {!! Form::hidden('id', null) !!}
    {!! Form::hidden('base', null) !!}
@endif