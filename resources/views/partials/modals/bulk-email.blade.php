<div class='modal fade large' id='send-email-form'>
    <div class='modal-dialog'>
    	<div class='modal-content'>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
    	        <h4 class='modal-title'><i class='fa fa-send'></i> Send Message</h4>
    	    </div> <!-- end modal-header -->

    	    {!! Form::open(['url' => route($page['route'] . '.bulk.email'), 'class' => 'form-type-a', 'files' => true]) !!}

	    		<div class='modal-body'>                                    
	    		    <div class='form-group'>
	    		        <label for='from' class='col-xs-12 col-sm-2 col-md-1 col-lg-1'>From</label>
	    		        
	    		        <div class='col-xs-12 col-sm-10 col-md-11 col-lg-11'>
	    		            {!! Form::text('from', auth_staff()->email, ['class' => 'form-control']) !!}
	    		            <span field='from' class='validation-error'></span>
	    		        </div>
	    		    </div> <!-- end form-group -->

	    		    <div class='form-group'>
	    		        <label for='to' class='col-xs-12 col-sm-2 col-md-1 col-lg-1'>To</label>
	    		        
	    		        <div class='col-xs-12 col-sm-10 col-md-11 col-lg-11'>
	    		            {!! Form::text('to', null, ['class' => 'form-control', 'readonly' => true]) !!}
	    		            <span field='to' class='validation-error'></span>
	    		        </div>
	    		    </div> <!-- end form-group -->

	    		    <div class='form-group'>
	    		        <label for='subject' class='col-xs-12 col-sm-2 col-md-1 col-lg-1'>Subject</label>
	    		        
	    		        <div class='col-xs-12 col-sm-10 col-md-11 col-lg-11'>
	    		            {!! Form::text('subject', null, ['class' => 'form-control']) !!}
	    		            <span field='subject' class='validation-error'></span>
	    		        </div>
	    		    </div> <!-- end form-group -->

	    		    <div class='form-group'>
	    		    	<label for='subject' class='col-xs-12 col-sm-2 col-md-1 col-lg-1'>Message</label>

	    		        <div class='col-xs-12 col-sm-10 col-md-11 col-lg-11'>
	    		            {!! Form::textarea('message', null, ['class' => 'form-control editor']) !!}
	    		            <span field='message' class='validation-error'></span>
	    		        </div>

	    		        <div class='full center'>
	    		            <div class='col-xs-12 col-sm-offset-2 col-sm-10 col-md-offset-1 col-md-11 col-lg-offset-1 col-lg-11 processing'></div>                
	    		        </div>
	    		    </div> <!-- end form-group -->
	    		</div> <!-- end modal-body -->

	    		<div class='modal-footer space btn-container'>
	    		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	    		    <button type='button' class='send btn btn-info'>Send</button>
	    		</div> <!-- end modal-footer -->

	    	{!! Form::close() !!}
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end send-email-form -->

@push('scripts')
	<script>
		$(document).ready(function()
		{
			$('#send-email-form .send').click(function()
			{					
				var form = $(this).parent().parent('form');				
				sendEmail(form, true);
			});
		});

		function bulkEmail(checkedCount)
		{			
			$('#send-email-form').find("input[name='to']").val('~ '+ checkedCount +' recipient(s)');
			$('#send-email-form .processing').html('');
			$('#send-email-form .validation-error').html('');
			$('#send-email-form').modal({
                show : true,
                backdrop: false,
                keyboard: false
            });
		}

		function sendEmail(form)
		{
			$('#send-email-form .processing').html("<img src='{!! asset('img/preloader.gif') !!}'/>");
			$('#send-email-form .processing').show();

			var formUrl = form.prop('action');
			var fieldName = '{!! $page['field'] !!}' + '[]';
			var formData = $("input[name='"+ fieldName +"']:checked").serialize();
			formData += '&';
			formData += form.serialize();

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
			                    $('#send-email-form span.validation-error').html('');
			                    $('#send-email-form .processing').html("<p class='para-type-e'><span class='fa fa-check-circle'></span> Your email has been sent.</p>");
			                    delayModalHide('#send-email-form', 1);
			                }
			                else
			                {
			                    $('#send-email-form span.validation-error').html('');
			                    $.each(data.errors, function(index, value)
			                    {
			                    	$("#send-email-form span[field='"+index+"']").html(value);
			                    });

			                    $('#send-email-form .processing').html("<p class='para-type-e danger'><span class='fa fa-exclamation-circle'></span> One or more fields have an error. Please check and try again.</p>");
			                }
			              }
			});
		}
	</script>
@endpush		