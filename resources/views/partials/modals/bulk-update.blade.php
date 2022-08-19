<div class='modal fade' id='bulk-update-form'>
    <div class='modal-dialog'>
    	<div class='modal-loader'>
    		<div class='spinner'></div>
    	</div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
   	        	<h4 class='modal-title'>Mass Update</h4>
    	    </div> <!-- end modal-header -->

    		{!! Form::open(['route' => (array_key_exists('route', $page) && route_has($page['route'] . '.bulk.update')) ? $page['route'] . '.bulk.update' : null, 'class' => 'form-type-a']) !!}
                @if(array_key_exists('view', $page) && view()->exists($page['view'] . '.partials.bulk-update-form'))
                    @include($page['view'] . '.partials.bulk-update-form')
                @endif    
    		{!! Form::close() !!}

	 		<div class='modal-footer space btn-container'>
	 		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	 		    <button type='button' class='save btn btn-info'>Update</button>
	 		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end bulk-update -->

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $('#bulk-update-form .save').click(function()
            {                   
                var form = $(this).closest('.modal').find('form');             
                massUpdate(form, true);
            });
        });

        function bulkUpdate(checkedCount)
        {
            $('#bulk-update-form form').trigger('reset');
            $('#bulk-update-form form').find('.select2-hidden-accessible').trigger('change');
            $('#bulk-update-form form').find('.white-select-type-single').select2('destroy').select2({containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});
            $('#bulk-update-form form').find('.white-select-type-single-b').select2('destroy').select2({minimumResultsForSearch : -1, containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});
            $('#bulk-update-form .processing').html('');
            $('#bulk-update-form .processing').hide();
            $('#bulk-update-form .none').hide();
            $('#bulk-update-form .validation-error').html('');
            $('#bulk-update-form').modal({
                show : true,
                backdrop: false,
                keyboard: false
            });
        }

        function massUpdate(form)
        {
            $('#bulk-update-form .processing').html("<div class='loader-ring-sm'></div>");
            $('#bulk-update-form .processing').show();

            var table = globalVar.jqueryDataTable;
            var formUrl = form.prop('action');
            var fieldName = '{!! $page['field'] or null !!}' + '[]';
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
                                $('#bulk-update-form span.validation-error').html('');
                                $('#bulk-update-form .processing').html("<span class='fa fa-check-circle success'></span>");                                   
                                delayModalHide('#bulk-update-form', 1);

                                if(typeof table != 'undefined')
                                {
                                    table.ajax.reload(null, false);
                                }
                            }
                            else
                            {
                                $('#bulk-update-form span.validation-error').html('');
                                $.each(data.errors, function(index, value)
                                {
                                    $("#bulk-update-form span[field='"+index+"']").html(value);
                                });
                                $('#bulk-update-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
                            }
                          }
            });
        }
    </script>
@endpush        