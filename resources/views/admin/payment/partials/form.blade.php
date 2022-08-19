<div class='modal-body perfectscroll'>                                    
    <div class='form-group'>
        <label for='amount' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Amount <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>            
            @if(isset($invoice))
                <div class='full left-icon amount' icon='{!! currency_icon($invoice->currency->code, $invoice->currency->symbol) !!}' alter-icon='{!! $invoice->currency->symbol !!}' base-id='{!! $invoice->currency->id !!}'>
                <i class='disabled {!! currency_icon($invoice->currency->code, $invoice->currency->symbol) !!}'>{!! is_null(currency_icon($invoice->currency->code, $invoice->currency->symbol)) ? $invoice->currency->symbol : '' !!}</i>        
            @else
                <div class='full left-icon amount' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
                <i class='disabled {!! currency_icon($base_currency->code, $base_currency->symbol) !!}'>{!! is_null(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>            
             @endif
                {!! Form::text('amount', null, ['class' => 'form-control']) !!}
                <span field='amount' class='validation-error'></span>
            </div>                      
        </div>
    </div> <!-- end form-group -->   

    <div class='form-group'>
        <label for='payment_date' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Payment Date <span class='c-danger'>*</span></label>

        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            <div class='full left-icon'>
                <i class='fa fa-calendar'></i>
                {!! Form::text('payment_date', date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                <span field='payment_date' class='validation-error'></span>
            </div>
        </div>
    </div> <!-- end form-group --> 

    <div class='form-group'>
        <label for='payment_method_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Payment Method <span class='c-danger'>*</span></label>

        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::select('payment_method_id', $payment_methods_list, null, ['class' => 'form-control white-select-type-single-b']) !!}
            <span field='payment_method_id' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='transaction_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Transaction Id</label>

        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::text('transaction_id', null, ['class' => 'form-control']) !!}
            <span field='transaction_id' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
    	<label for='note' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Note</label>

        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::textarea('note', null, ['class' => 'form-control']) !!}
            <span field='note' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->
</div> <!-- end modal-body -->

{!! Form::hidden('invoice_id', isset($invoice) ? $invoice->id : null) !!}

@if(isset($form) && $form == 'edit')
    {!! Form::hidden('id', null) !!}
@endif