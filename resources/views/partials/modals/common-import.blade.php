<div class='modal fade' id='import-form'>
    <div class='modal-dialog'>
    	<div class='modal-loader'>
    		<div class='spinner'></div>
    	</div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
   	        	<h4 class='modal-title'>Import <span class='capitalize'></span></h4>
    	    </div> <!-- end modal-header -->

            <div class='full content'>        		
                @include($page['view'] . '.partials.import-csv')        		
            </div>    

	 		<div class='modal-footer space btn-container'>
	 		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	 		    <button type='button' class='save csv btn btn-info'>Next</button>
	 		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end import-form -->

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $('.import-btn').click(function()
            {
                $('#import-form .processing').html('');
                $('#import-form .processing').hide();

                $('#import-form .none').hide();
                $('#import-form span.validation-error').html('');
                $('#import-form .error-content').html('');

                $('#import-form .form-group').hide();
                $('#import-form .modal-loader').show();

                $('#import-form .save').show();
                $('#import-form .save').html('Next'); 
                $('#import-form .save').removeClass('import');
                $('#import-form .save').addClass('csv');

                $('#import-form .cancel').removeClass('btn-info'); 
                $('#import-form .cancel').addClass('btn-default'); 
                $('#import-form .cancel').html('Cancel'); 

                if(typeof $(this).data('item') !== 'undefined')
                {
                    $('#import-form .modal-title').html("Import <span class='capitalize'>" + $(this).data('item') + 's</span>');
                }

                $('#import-form').modal({
                    show : true,
                    backdrop: false,
                    keyboard: false
                });

                $.ajax(
                {
                    type    : 'GET',
                    url     : $(this).data('url'),
                    data    : { 'module' : $(this).data('item') },
                    success : function(data)
                              {
                                if(data.status == true)
                                {
                                    $dataObj = $(data.html);

                                    if($dataObj.length)
                                    {
                                        $('#import-form .modal-content').css('height', 'auto');
                                        $('#import-form .content').html($dataObj);    
                                        $('#import-form form').trigger('reset');               
                                        $('#import-form form').find('.select2-hidden-accessible').trigger('change');                                        
                                        $('#import-form form').find('option').prop('disabled', false);
                                        $('#import-form form').find('.white-select-type-single').select2('destroy').select2({containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});
                                        $('#import-form form').find('.white-select-type-single-b').select2('destroy').select2({minimumResultsForSearch : -1, containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});

                                        var ps = new PerfectScrollbar('#import-form .modal-body'); 
                                        $('#import-form .modal-body').animate({ scrollTop: 0 }, 10);
                                        $('#import-form .modal-loader').fadeOut(850);                                   
                                        pluginInit();                                                                   
                                    }    
                                }
                                else
                                {
                                    $('#import-form .modal-loader').fadeOut(1000);
                                    $('#import-form .form-group').css('opacity', 0).slideDown('slow').animate({opacity: 1});
                                    $('#import-form .processing').show();
                                    $('#import-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
                                    delayModalHide('#import-form', 2);
                                }
                              }
                });  
            });

            $(document).on('click', '#import-form .csv', function(e)      
            {
                e.preventDefault();
                if(!$(this).hasClass('import'))
                {
                    $('#import-form .processing').html("<div class='loader-ring-sm'></div>");
                    $('#import-form .processing').show();             

                    var form = $(this).closest('.modal').find('form');
                    var formUrl = form.prop('action');
                    var formData = new FormData($('#import-file-form').get(0));

                    $.ajax(
                    {
                        type    : 'POST',
                        url     : formUrl,
                        data    : formData,
                        dataType: 'JSON',
                        processData: false,
                        contentType: false,
                        success : function(data)
                                  {
                                    if(data.status == true)
                                    {
                                        $('#import-form span.validation-error').html('');
                                        $('#import-form .processing').html('');    
                                        $('#import-form .processing').hide();     

                                        $dataObj = $(data.html);

                                        if($dataObj.length)
                                        {
                                            $('#import-form .modal-content').css('height', 'auto');
                                            $('#import-form .content').html($dataObj);                                        
                                            var ps = new PerfectScrollbar('#import-form .modal-body'); 
                                            $('#import-form .modal-body').animate({ scrollTop: 0 }, 10);                                   
                                            pluginInit();

                                            $('#import-form .modal-title').html(data.modalTitle);
                                            $('#import-form .save').removeClass('csv');
                                            $('#import-form .save').addClass('import');
                                            $('#import-form .save').html('Import');                                        

                                            $.each(data.info, function(index, value)
                                            {
                                                $("#import-form [name='"+index+"']").val(value);
                                            });
                                        }                                           
                                    }
                                    else
                                    {
                                        $('#import-form span.validation-error').html('');
                                        $.each(data.errors, function(index, value)
                                        {
                                            $("#import-form span[field='"+index+"']").html(value);
                                        });
                                        $('#import-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
                                        $('#import-form .modal-body').animate({ scrollTop: 0 });
                                    }
                                  }
                    });
                }    
            });

            $(document).on('click', '#import-form .import', function(e)    
            {
                e.preventDefault();
                $('#import-form .processing').html("<div class='loader-ring-sm'></div>");
                $('#import-form .processing').show();             

                var form = $(this).closest('.modal').find('form');
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
                                    $('#import-form .error-content').html('');
                                    $('#import-form .processing').html('');    
                                    $('#import-form .processing').hide();     

                                    $dataObj = $(data.html);

                                    if($dataObj.length)
                                    {
                                        $('#import-form .modal-content').animate({height: '225px'});
                                        $('#import-form .content').html($dataObj);                                        
                                        var ps = new PerfectScrollbar('#import-form .modal-body'); 
                                        $('#import-form .modal-body').animate({ scrollTop: 0 }, 10);                                   

                                        $('#import-form .modal-title').html(data.modalTitle);
                                        $('#import-form .save').removeClass('import');
                                        $('#import-form .save').hide();
                                        $('#import-form .cancel').addClass('btn-info'); 
                                        $('#import-form .cancel').removeClass('btn-default'); 
                                        $('#import-form .cancel').html('Okay'); 
                                        delayModalHide('#import-form', 5);

                                        if(typeof globalVar.jqueryDataTable != 'undefined')
                                        {
                                            // globalVar.jqueryDataTable.page('first').draw('page');
                                        }
                                    }                                           
                                }
                                else
                                {
                                    $('#import-form .error-content').html('');
                                    $.each(data.errors, function(index, fieldErrors)
                                    {
                                        $.each(fieldErrors, function(key, value)
                                        {
                                            if(key == 0)
                                            {
                                                $('#import-form .error-content').append("<span class='validation-error'>"+value+"</span>");
                                            }
                                            else
                                            {
                                                $('#import-form .error-content').append("<br><span class='validation-error'>"+value+"</span>");
                                            }                                            
                                        });                                        
                                    });
                                    $('#import-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
                                    $('#import-form .modal-body').animate({ scrollTop: 0 });
                                }
                              }
                });
            });
        });
    </script>
@endpush        