<div class='modal fade medium' id='change-password-form'>
    <div class='modal-dialog'>
    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
	        	<h4 class='modal-title'>Change Password <span class='shadow bracket'></span></h4>
    	    </div> <!-- end modal-header -->

    		{!! Form::open(['route' => ['admin.user.password', null], 'method' => 'post', 'class' => 'form-type-a']) !!}
    		    <div class='modal-body perfectscroll'>   
    		    	<div class='form-group'>
    		    		<label for='password' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Password</label>

    		    		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
    		    			{!! Form::password('password', ['class' => 'form-control']) !!}
    		    			<span field='password' class='validation-error'></span>
    		    		</div>
    		    	</div> <!-- end form-group -->

    		    	<div class='form-group'>
    		    		<label for='password_confirmation' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Confirm Password</label>

    		    		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
    		    			{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
    		    			<span field='password_confirmation' class='validation-error'></span>
    		    		</div>
    		    	</div> <!-- end form-group -->
    		    </div> <!-- end modal-body -->
    		    
    		    {!! Form::hidden('id', null) !!}	
    		{!! Form::close() !!}

	 		<div class='modal-footer space btn-container'>
	 		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	 		    <button type='button' class='save btn btn-info'>Save</button>
	 		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end change-password-form -->

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $('#change-password-form .save').click(function()
            {				
            	var form = $(this).parent().parent().find('form');
            	
            	$('#change-password-form .processing').html("<div class='loader-ring-sm'></div>");
            	$('#change-password-form .processing').show();			    

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
            	                	$('#change-password-form span.validation-error').html('');
            	                    $('#change-password-form .processing').html("<span class='fa fa-check-circle success'></span>");				                    
                                	delayModalHide('#change-password-form', 1);

                                	if(typeof table != 'undefined')
                                	{
                                		table.ajax.reload(null, false);
                                	}
            	                }
            	                else
            	                {
            	                	$('#change-password-form span.validation-error').html('');
            	                	$.each(data.errors, function(index, value)
            	                	{
            	                		$("#change-password-form span[field='"+index+"']").html(value);
            	                	});
            	                	$('#change-password-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
            	                }
            	              }
            	});
            });
        });
    </script>
@endpush