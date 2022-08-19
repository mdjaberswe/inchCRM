<div class='modal fade medium' id='new-msg-form'>
    <div class='modal-dialog'>
    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
    	        <h4 class='modal-title'>New Message</h4>
    	    </div> <!-- end modal-header -->

	    	{!! Form::open(['route' => 'admin.user.message', 'method' => 'post', 'class' => 'form-type-a']) !!}
	    	    <div class='modal-body perfectscroll'>   
                    <div class='form-group'>
                        <label for='receiver' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>To <span class='c-danger'>*</span></label>

                        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                            {!! Form::select('receiver[]', $receivers_list, $staff->id, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple']) !!}
                            <span field='receiver' class='validation-error'></span>
                        </div>
                    </div> <!-- end form-group -->

                    <div class='form-group'>
                        <label for='message' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Message <span class='c-danger'>*</span></label>

                        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                            {!! Form::textarea('message', null, ['class' => 'form-control md', 'placeholder' => 'Write a message...']) !!}
                            <span field='message' class='validation-error'></span>
                        </div>
                    </div> <!-- end form-group -->
                </div> <!-- end modal-body -->    
	    	{!! Form::close() !!}

    		<div class='modal-footer space btn-container'>
    		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
    		    <button type='button' class='send btn btn-info'>Send</button>
    		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end add-new-form -->

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $('#new-msg').click(function()
            {
                // reset to default values
                $('#new-msg-form form').trigger('reset');               
                $('#new-msg-form form').find('.select2-hidden-accessible').trigger('change');
                
                $('#new-msg-form form').find('option').prop('disabled', false);
                $('#new-msg-form form').find('.white-select-type-single').select2('destroy').select2({containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});
                $('#new-msg-form form').find('.white-select-type-single-b').select2('destroy').select2({minimumResultsForSearch : -1, containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});

                $('#new-msg-form .processing').html('');
                $('#new-msg-form .processing').hide();
                $('#new-msg-form .none').slideUp();
                $('#new-msg-form span.validation-error').html('');
                $('#new-msg-form .modal-body').animate({ scrollTop: 0 });
                $('#new-msg-form').modal();             
            });

            $('#new-msg-form .send').click(function()
            {               
                var form = $(this).parent().parent().find('form');              
                
                $('#new-msg-form .processing').html("<div class='loader-ring-sm'></div>");
                $('#new-msg-form .processing').show();  

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
                                    $('#new-msg-form span.validation-error').html('');
                                    $('#new-msg-form .processing').html("<span class='fa fa-check-circle success'></span>");                                
                                    delayModalHide('#new-msg-form', 1);                   
                                }
                                else
                                {
                                    $('#new-msg-form span.validation-error').html('');
                                    $.each(data.errors, function(index, value)
                                    {
                                        $("#new-msg-form span[field='"+index+"']").html(value);
                                    });

                                    $('#new-msg-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
                                }
                              }
                });
            });
        });
    </script>
@endpush    