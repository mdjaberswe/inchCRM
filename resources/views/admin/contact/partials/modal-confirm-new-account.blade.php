<div class='modal fade' id='confirm-new-account'>
    <div class='modal-dialog'>
        <div class='modal-loader'>
            <div class='spinner'></div>
        </div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
	        	<h4 class='modal-title'>Move to new Account</h4>
    	    </div> <!-- end modal-header -->

    		{!! Form::open(['route' => ['admin.contact.confirm.account', $contact->id], 'method' => 'post', 'class' => 'form-type-a']) !!}
    		    <div class='modal-body vertical near-10 perfectscroll'>  
                    <div class='full form-group-container'>                
                        <div class='form-group show-if' scroll='true'>
                            <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                                <label for='confirmation' class='padding-0'>Choose what you want to do with records <i class='fa fa-info-circle small' data-toggle='tooltip' data-placement='bottom' title='Deals,&nbsp;Projects,&nbsp;Estimates,&nbsp;Invoices'></i></label>

                                <div class='inline-input'>
                                    <p class='pretty top-space info smooth'>
                                        <input type='radio' name='confirmation' value='keep' checked>
                                        <label><i class='mdi mdi-check'></i></label>  Keep the contact records related to the <strong>current</strong> account
                                    </p> 
                                    <br>
                                    <p class='pretty top-space info smooth'>
                                        <input type='radio' name='confirmation' value='new' class='indicator'>
                                        <label><i class='mdi mdi-check'></i></label> Assign to another contact of the current account
                                    </p>
                                </div>    
                            </div>
                        </div> <!-- end form-group -->

                        <div class='full none'>
                            <div class='form-group'>
                                <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                                    <label for='assign_contact' class='padding-0'>Assign To</label>
                                    {!! Form::select('assign_contact', $contact->getAssignNewContactAttribute(['' => '-None-']), null, ['class' => 'form-control assign-contact white-select-type-single']) !!} 
                                    <span field='assign_contact' class='validation-error'></span>
                                    <span field='account_id' class='validation-error'></span>
                                    <span field='id' class='validation-error'></span>
                                </div>
                            </div> <!-- end form-group -->

                            <div class='form-group'>
                                <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                                    <label for='permissions'>Contact Records <i class='small fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='Related&nbsp;to&nbsp;the&nbsp;current&nbsp;account'></i></label>

                                    <div class='inline-input'>
                                        <div class='toggle-permission'>
                                            <div class='parent-permission'>
                                                <span>Deal</span>

                                                <label class='switch'>
                                                    <input type='checkbox' name='deal' value='1' checked>
                                                    <span class='slider round'></span>
                                                </label>
                                            </div>

                                            <div class='child-permission'>                          
                                                <div class='inline-info'>
                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='deal_categories[]' value='open' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Open                                    
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='deal_categories[]' value='closed_won' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Closed Won                                    
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='deal_categories[]' value='closed_lost' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Closed Lost                                    
                                                    </p>
                                                </div>
                                            </div>
                                        </div>            

                                        <div class='toggle-permission'>
                                            <div class='parent-permission'>
                                                <span>Project</span>

                                                <label class='switch'>
                                                    <input type='checkbox' name='project' value='1' checked>
                                                    <span class='slider round'></span>
                                                </label>
                                            </div>

                                            <div class='child-permission'>                          
                                                <div class='inline-info'>
                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='project_categories[]' value='open' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Open                                    
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='project_categories[]' value='completed' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Completed                                   
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='project_categories[]' value='cancelled' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Cancelled                                   
                                                    </p>
                                                </div>
                                            </div>
                                        </div>   

                                        <div class='toggle-permission'>
                                            <div class='parent-permission'>
                                                <span>Estimates</span>

                                                <label class='switch'>
                                                    <input type='checkbox' name='estimate' value='1' checked>
                                                    <span class='slider round'></span>
                                                </label>
                                            </div>

                                            <div class='child-permission'>                          
                                                <div class='inline-info'>
                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='estimate_categories[]' value='draft' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Draft                                    
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='estimate_categories[]' value='sent' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Sent                                    
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='estimate_categories[]' value='accepted' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Accepted                                    
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='estimate_categories[]' value='expired' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Expired                                    
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='estimate_categories[]' value='declined' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Declined                                    
                                                    </p>
                                                </div>
                                            </div>
                                        </div>   

                                        <div class='toggle-permission'>
                                            <div class='parent-permission'>
                                                <span>Invoices</span>

                                                <label class='switch'>
                                                    <input type='checkbox' name='invoice' value='1' checked>
                                                    <span class='slider round'></span>
                                                </label>
                                            </div>

                                            <div class='child-permission'>                          
                                                <div class='inline-info'>
                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='invoice_categories[]' value='draft' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Draft                                    
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='invoice_categories[]' value='sent' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Sent                                    
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='invoice_categories[]' value='unpaid' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Unpaid                                    
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='invoice_categories[]' value='paid' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Paid                                    
                                                    </p>

                                                    <p class='pretty column info smooth'>
                                                        <input type='checkbox' name='invoice_categories[]' value='partially_paid' data-default='true' checked>
                                                        <label><i class='mdi mdi-check'></i></label> Partially Paid                                    
                                                    </p>                                            
                                                </div>
                                            </div>
                                        </div>   
                                    </div>  

                                    <div class='full'>
                                        <span field='deal_categories' class='validation-error block'></span>
                                        <span field='project_categories' class='validation-error block'></span>
                                        <span field='estimate_categories' class='validation-error block'></span>
                                        <span field='invoice_categories' class='validation-error block'></span>
                                    </div>  
                                </div>
                            </div> <!-- end form-group -->
                        </div>   
                    </div> <!-- end form-group-container -->     
    		    </div> <!-- end modal-body -->
    		    
    		    {!! Form::hidden('id', $contact->id) !!}
                {!! Form::hidden('account_id', $contact->account_id) !!}
    		{!! Form::close() !!}

	 		<div class='modal-footer space btn-container'>
	 		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	 		    <button type='button' class='confirm-account-btn btn btn-info'>Save</button>
	 		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end confirm-new-account -->

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $('#confirm-new-account .cancel,#confirm-new-account .close').click(function()
            {
                $("*[data-confirm-account='true']").find('.cancel-single').click();
            });   

            $('#confirm-new-account .confirm-account-btn').click(function()
            {
                $('#confirm-new-account .processing').html("<div class='loader-ring-sm'></div>");
                $('#confirm-new-account .processing').show();             

                var form = $('#confirm-new-account form');    
                var formUrl = form.prop('action');
                var formData = form.serialize();
                var editSingle = $("*[data-confirm-account='true']");
                var field = $($("*[data-confirm-account='true']").parent('.field'));
                var value = $(field.find('.value'));

                $.ajax({
                    type    : 'POST',
                    url     : formUrl,
                    data    : formData,
                    dataType: 'JSON',
                    success : function(data)
                              {
                                if(data.status == true)
                                {
                                    $('#confirm-new-account span.validation-error').html('');
                                    $('#confirm-new-account .processing').html("<span class='fa fa-check-circle success'></span>");                               
                                    
                                    value.attr('data-value', data.val);
                                    value.html(data.html);

                                    $("*[data-realtime='parent_id']").attr('data-value', '');
                                    $("*[data-realtime='parent_id']").html('');                                

                                    var assignlist = $('.assign-contact').empty();
                                    var parentlist = $("select[name='parent_id']").empty();
                                    var parentField = $("select[name='parent_id']").closest('.field');

                                    $('<option/>', { value : '', text : '-None-' }).appendTo(assignlist);
                                    $('<option/>', { value : '', text : '-None-' }).appendTo(parentlist);

                                    $.each(data.list, function(id, name)
                                    {
                                        $('<option/>', { value : id, text : name }).appendTo(assignlist);
                                        $('<option/>', { value : id, text : name }).appendTo(parentlist);
                                    });
                                    
                                    parentField.find('.value').attr('data-value', '');
                                    parentField.find('.value').html('');
                                    resetOverview(editSingle, data.status);

                                    delayModalHide('#confirm-new-account', 1);
                                    $.notify({ message: 'Update was successful' }, globalVar.successNotify);
                                }
                                else
                                {
                                    $('#confirm-new-account span.validation-error').html('');
                                    $.each(data.errors, function(index, value)
                                    {
                                        $("#confirm-new-account span[field='"+index+"']").html(value);
                                    });

                                    $('#confirm-new-account .processing').html("<span class='fa fa-exclamation-circle error'></span>");
                                }
                               }
                });
            });
        });
    </script>
@endpush