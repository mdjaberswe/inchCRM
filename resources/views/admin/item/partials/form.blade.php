<div class='modal-body perfectscroll'>                                    
    <div class='form-group'>
        <label for='name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Item <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::text('name', null, ['placeholder' => 'Enter item', 'class' => 'form-control']) !!}
            <span field='name' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='price' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Unit Price <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            <div class='full left-icon clickable amount' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
                <i class='dropdown-toggle {!! currency_icon($base_currency->code, $base_currency->symbol) !!}' data-toggle='dropdown' animation='headShake|headShake'>{!! is_null(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>
                <ul class='dropdown-menu up-caret select sm currency-list'>
                    <div class='full perfectscroll max-h-100'>
                        {!! $currency_list !!}
                    </div>    
                </ul>
                {!! Form::text('price', null, ['placeholder' => 'Enter item price', 'class' => 'form-control']) !!}
                {!! Form::hidden('currency_id', $base_currency->id) !!}
                <span field='price' class='validation-error'></span>
                <span field='currency_id' class='validation-error'></span>
            </div>    
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='tax' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Tax</label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            <div class='full left-icon'>
                <i class='fa fa-percent'></i>
                {!! Form::text('tax', null, ['placeholder' => 'Enter tax', 'class' => 'form-control']) !!}
                <span field='tax' class='validation-error'></span>
            </div>    
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='discount' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Discount</label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            <div class='full left-icon'>
                <i class='fa fa-percent'></i>
                {!! Form::text('discount', null, ['placeholder' => 'Enter discount', 'class' => 'form-control']) !!}
                <span field='discount' class='validation-error'></span>
            </div>    
        </div>
    </div> <!-- end form-group -->
</div> <!-- end modal-body -->

@if(isset($form) && $form == 'edit')
    {!! Form::hidden('id', null) !!}
@endif