<div class='modal fade large' id='convert-lead-form'>
    <div class='modal-dialog'>
        <div class='modal-loader'>
            <div class='spinner'></div>
        </div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
	        	<h4 class='modal-title'>Convert Lead <span class='shadow bracket'></span></h4>
    	    </div> <!-- end modal-header -->

    		{!! Form::open(['route' => ['admin.lead.convert', null], 'method' => 'post', 'class' => 'form-type-a']) !!}
    		    <div class='modal-body perfectscroll'>  
                    <div class='full form-group-container'> 
                        <div class='form-group'>
                            <label for='owner' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>New Record Owner</label>

                            <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                                {!! Form::select('owner', $admin_users_list, auth_staff()->id, ['class' => 'form-control white-select-type-single']) !!}
                                <span field='owner' class='validation-error'></span>
                            </div>
                        </div> <!-- end form-group -->

                        <div class='form-group leadstage-input {!! count($lead_stages_list) == 1 ? 'none' : '' !!}'>
                            <label for='lead_stage_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Convert Lead Stage</label>

                            <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                                {!! Form::select('lead_stage_id', $lead_stages_list, null, ['class' => 'form-control white-select-type-single']) !!}
                                <span field='lead_stage_id' class='validation-error'></span>
                            </div>
                        </div> <!-- end form-group -->

                        <div class='form-group show-if'>
                            <label for='account' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Account Name<span class='c-danger'>*</span></label>

                            <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                                <div class='inline-input m-bottom-7'>
                                    <span class='radio-factor'><input type='radio' name='account_type' value='new' checked> Create New Account</span>
                                    <span class='radio-factor'><input type='radio' name='account_type' value='add'> Add To Existing Account</span>             
                                </div>  

                                {!! Form::text('account_name', null, ['class' => 'form-control block factor factor-new', 'placeholder' => 'Enter account name']) !!}

                                <div class='full none factor factor-add'>
                                    {!! Form::select('account_id', $accounts_list, null, ['class' => 'form-control white-select-type-single']) !!}
                                </div>

                                <span field='account_type' class='validation-error'></span>
                                <span field='account_name' class='validation-error'></span>
                                <span field='account_id' class='validation-error'></span>
                            </div>
                        </div> <!-- end form-group -->

                        <div class='form-group'>
                            <label for='name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>New Contact</label>

                            <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                                {!! Form::text('name', null, ['class' => 'form-control', 'readonly' => true]) !!}
                                {!! Form::hidden('first_name', null) !!}
                                {!! Form::hidden('last_name', null) !!}
                                <span field='name' class='validation-error'></span>
                            </div>
                        </div> <!-- end form-group -->

                        <div class='form-group'>
                            <label for='email' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Contact Email <span class='c-danger'>*</span></label>

                            <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                                {!! Form::text('email', null, ['class' => 'form-control']) !!}      
                                <span field='email' class='validation-error'></span>
                            </div>
                        </div> <!-- end form-group -->

                        <div class='form-group'>
                            <label for='password' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Contact Password <span class='c-danger'>*</span></label>

                            <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9 div-type-h'>
                                <a data-toggle='tooltip' data-placement='top' title='Generate Password' class='password-generator'><i class='fa fa-key'></i></a>
                                <a data-toggle='tooltip' data-placement='top' title='Show Password' class='show-password'><i class='fa fa-eye'></i></a>
                                {!! Form::password('password', ['class' => 'form-control password']) !!}
                                <span field='password' class='validation-error'></span>
                            </div>
                        </div> <!-- end form-group -->

                        <div class='form-group show-if' scroll='true'>
                            <div class='col-xs-12 col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9 col-lg-offset-3 col-lg-9'>
                                <p class='pretty top-space info smooth'>
                                    <input type='checkbox' name='send_login_details' checked>
                                    <label><i class='mdi mdi-check'></i></label> Email login details to this contact
                                </p> 
                                <br>
                                <p class='pretty top-space info smooth'>
                                    <input type='checkbox' name='new_deal' value='new_deal' class='indicator'>
                                    <label><i class='mdi mdi-check'></i></label> Create new deal for this account
                                </p>
                            </div>
                        </div>

                        <div class='full none'>
                            <div class='form-group'>
                                <label for='deal_name' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Deal Name <span class='c-danger'>*</span></label>

                                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                                    {!! Form::text('deal_name', null, ['class' => 'form-control']) !!}
                                    <span field='deal_name' class='validation-error'></span>
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
                                <label for='closing_date' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Closing Date</label>

                                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                                    <div class='full left-icon'>
                                        <i class='fa fa-calendar-times-o'></i>
                                        {!! Form::text('closing_date', $closing_date, ['class' => 'form-control datepicker', 'placeholder' => 'yyyy-mm-dd']) !!}
                                        <span field='closing_date' class='validation-error'></span>
                                    </div>
                                </div>
                            </div> <!-- end form-group -->

                            <div class='form-group'>
                                <label for='deal_pipeline_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Deal Pipeline</label>              
                                
                                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                                    {!! Form::select('deal_pipeline_id', $deal_pipelines_list, $default_pipeline->id, ['class' => 'form-control white-select-type-single', 'data-child-option' => 'deal_stage_id', 'data-url' => route('admin.dealpipeline.stage.dropdown')]) !!}
                                    <span field='deal_pipeline_id' class='validation-error'></span>
                                </div>
                            </div> <!-- end form-group -->

                            <div class='form-group'>
                                <label for='deal_stage_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Deal Stage</label>
                                
                                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                                    {!! Form::select('deal_stage_id', $deal_stages_list, $default_stage->id, ['class' => 'form-control white-select-type-single']) !!}
                                    {!! Form::hidden('deal_stage', 0) !!}
                                    <span field='deal_stage_id' class='validation-error'></span>
                                </div>
                            </div> <!-- end form-group -->
                        </div> <!-- end full none -->
                    </div> <!-- end from-group-container -->    
    		    </div> <!-- end modal-body -->
    		    
    		    {!! Form::hidden('id', null) !!}	
    		{!! Form::close() !!}

	 		<div class='modal-footer space btn-container'>
	 		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	 		    <button type='button' class='convert-btn btn btn-info'>Convert</button>
	 		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end convert-lead-form -->

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $('#convert-lead-form .convert-btn').click(function()
            {				
            	var form = $(this).parent().parent().find('form');
            	
            	$('#convert-lead-form .processing').html("<div class='loader-ring-sm'></div>");
            	$('#convert-lead-form .processing').show();			    

            	var table = globalVar.jqueryDataTable;
            	var formUrl = form.prop('action');
            	var formData = form.serialize();

            	$.ajax(
            	{
            	    type    : 'POST',
            	    url     : formUrl,
            	    data    : formData,
            	    dataType: 'JSON',
            	    success : function(data)
            	              {
            	                if(data.status == true)
            	                {
            	                	$('#convert-lead-form span.validation-error').html('');
            	                    $('#convert-lead-form .processing').html("<span class='fa fa-check-circle success'></span>");				                    
                                	delayModalHide('#convert-lead-form', 1);

                                	if(typeof table != 'undefined')
                                	{
                                		table.ajax.reload(null, false);
                                	}

                                    if($('.funnel-container').get(0))
                                    {
                                        kanbanUpdateResponse(data)
                                    }
            	                }
            	                else
            	                {
            	                	$('#convert-lead-form span.validation-error').html('');
            	                	$.each(data.errors, function(index, value)
            	                	{
            	                		$("#convert-lead-form span[field='"+index+"']").html(value);
            	                	});
            	                	$('#convert-lead-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
            	                }
            	              }
            	});
            });
        });
    </script>
@endpush