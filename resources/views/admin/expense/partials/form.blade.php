<div class='modal-body perfectscroll'>     
    <div class='form-group'>
        <label for='name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Expense Name</label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
            <span field='name' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='expense_category' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Category <span class='c-danger'>*</span></label>

        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::select('expense_category', $expense_categories_list, null, ['class' => 'form-control white-select-type-single']) !!}
            <span field='expense_category' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='amount' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Amount</label>

        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            <div class='full left-icon clickable amount' icon='{!! currency_icon($base_currency->code, $base_currency->symbol) !!}' alter-icon='{!! $base_currency->symbol !!}' base-id='{!! $base_currency->id !!}'>
                <i class='dropdown-toggle {!! currency_icon($base_currency->code, $base_currency->symbol) !!}' data-toggle='dropdown' animation='headShake|headShake'>{!! is_null(currency_icon($base_currency->code, $base_currency->symbol)) ? $base_currency->symbol : '' !!}</i>
                <ul class='dropdown-menu up-caret select sm currency-list'>
                    <div class='full perfectscroll max-h-100'>
                        {!! $currency_list !!}
                    </div>    
                </ul>
                {!! Form::text('amount', null, ['class' => 'form-control']) !!}
                {!! Form::hidden('currency_id', $base_currency->id) !!}
                <span field='amount' class='validation-error'></span>
                <span field='currency_id' class='validation-error'></span>
            </div>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='payment_method_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Payment Method</label>

        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::select('payment_method_id', $payment_methods_list, null, ['class' => 'form-control white-select-type-single-b']) !!}
            <span field='payment_method_id' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group'>
        <label for='expense_date' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Expense Date <span class='c-danger'>*</span></label>

        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            <div class='full left-icon'>
                <i class='fa fa-calendar'></i>
                {!! Form::text('expense_date', date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                <span field='expense_date' class='validation-error'></span>
            </div>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group fetch-show-if' child-class='account-depend' data-url='{!! route('admin.account.project.data') !!}'>
        <label for='account' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Account</label>

        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::select('account', $accounts_list, null, ['class' => 'form-control white-select-type-single']) !!}
            <span field='account' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group project-input show-if-true account-depend'>
        <label for='project' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Project</label>

        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::select('project', ['' => '-None-'], null, ['class' => 'form-control white-select-type-single', 'data-default' => 'project_id']) !!}
            <span field='project' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group billable-input show-if-true account-depend'>
        <div class='col-xs-12 col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9 col-lg-offset-3 col-lg-9'>
            <p class='pretty info smooth'>
                <input type='checkbox' name='billable' value='1'>
                <label><i class='mdi mdi-check'></i></label>
                <span class='c-on-white-bg'>Billable</span>
            </p> 
            <span field='billable' class='validation-error'></span>   
        </div>
    </div> <!-- end form-group -->
</div> <!-- end modal-body -->

@if(isset($form) && $form == 'edit')    
    {!! Form::hidden('project_id', '') !!}
    {!! Form::hidden('id', null) !!}  
@endif