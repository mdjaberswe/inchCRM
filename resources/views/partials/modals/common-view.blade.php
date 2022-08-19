<div class='modal fade sub' id='common-view'>
    <div class='modal-dialog'>
    	<div class='modal-loader'>
    		<div class='spinner'></div>
    	</div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
   	        	<h4 class='modal-title capitalize'>Save View</h4>
    	    </div> <!-- end modal-header -->

    		{!! Form::open(['route' => ['admin.view.store', null], 'method' => 'post', 'class' => 'form-type-a']) !!}
                @include('partials.modals.common-view-form', ['form' => 'create'])
    		{!! Form::close() !!}

	 		<div class='modal-footer space btn-container'>
	 		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	 		    <button type='button' class='save btn btn-info'>Save</button>
	 		</div> <!-- end modal-footer -->
    	</div>
    </div> <!-- end modal-dialog -->
</div> <!-- end common-view-form -->

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $(document).on('click', '.save-as-view', function(event)
            {
                var moduleItem = $(this).attr('data-item');

                if($('#common-filter').css('display') == 'block')
                {
                    var selectedTr = $('#common-filter').find('table tbody tr').not('.none');
                    var formUrl = $('#common-filter form').prop('action');
                    var formData = { 'validationOnly' : true };
                    var fields = [];
                    var fieldConditions = [];
                    var fieldValues = [];
                    $.each(selectedTr, function(index, tr)
                    {
                        var trCondition = $(tr).find("td[data-type='condition'] select");
                        var trValue = '';
                        if(trCondition.val() != 'empty' && trCondition.val() != 'not_empty')
                        {
                            trValue = $(tr).find("td[data-type='value'] *[name]").val();

                            if($.isArray(trValue) && trValue.length == 0)
                            {
                                trValue = '';
                            }

                            if(trCondition.attr('name') == 'linked_type_condition')
                            {
                                trValue += '|' + $(tr).find("td[data-type='value'] *[name='linked_id']").val();
                            }
                        }

                        fields.push($(tr).data('field'));
                        fieldConditions.push(trCondition.val());
                        fieldValues.push(trValue);
                    });

                    formData['fields'] = fields;
                    formData['conditions'] = fieldConditions;
                    formData['values'] = fieldValues;

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
                                        $('#common-filter span.validation-error').html('');
                                        openViewModal(moduleItem);                                  
                                    }
                                    else
                                    {
                                        $('#common-filter span.validation-error').html('');
                                        $.each(data.errors, function(index, value)
                                        {
                                            $("#common-filter span[field='"+index+"']").html(value);
                                        });
                                    }
                                  }
                    });
                }
                else
                {
                    openViewModal(moduleItem);
                }
            });

            $('#common-view .save').click(function()
            {               
                $('#common-view .processing').html("<div class='loader-ring-sm'></div>");
                $('#common-view .processing').show();

                var table = globalVar.jqueryDataTable;
                var form = $(this).parent().parent().find('form');
                var formUrl = form.prop('action') + '/' + form.find("input[name='module']").val();
                var formData = {
                                'view_name'     : form.find("input[name='view_name']").val(),
                                'visible_to'    : form.find("select[name='visible_to']").val(),
                                'selected_users': form.find("select[name='selected_users[]']").val(),
                                'module'        : form.find("input[name='module']").val(),
                               };

                if($('#common-filter').css('display') == 'block')
                {
                    formData['has_filter_data'] = true;
                    var selectedTr = $('#common-filter').find('table tbody tr').not('.none');
                    var fields = [];
                    var fieldConditions = [];
                    var fieldValues = [];
                    $.each(selectedTr, function(index, tr)
                    {
                        var trCondition = $(tr).find("td[data-type='condition'] select");
                        var trValue = '';
                        if(trCondition.val() != 'empty' && trCondition.val() != 'not_empty')
                        {
                            trValue = $(tr).find("td[data-type='value'] *[name]").val();

                            if($.isArray(trValue) && trValue.length == 0)
                            {
                                trValue = '';
                            }

                            if(trCondition.attr('name') == 'linked_type_condition')
                            {
                                trValue += '|' + $(tr).find("td[data-type='value'] *[name='linked_id']").val();
                            }
                        }

                        fields.push($(tr).data('field'));
                        fieldConditions.push(trCondition.val());
                        fieldValues.push(trValue);
                    });

                    formData['fields'] = fields;
                    formData['conditions'] = fieldConditions;
                    formData['values'] = fieldValues;
                }

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
                                    $('#common-view span.validation-error').html('');
                                    $('#common-view .processing').html("<span class='fa fa-check-circle success'></span>");                                   
                                    delayModalHide('#common-view', 1);
                                    delayModalHide('#common-filter', 1);

                                    if(typeof data.tabTable != 'undefined' && typeof globalVar.dataTable[data.tabTable] != 'undefined')
                                    {
                                        table = globalVar.dataTable[data.tabTable];
                                    }

                                    if(typeof table != 'undefined')
                                    {
                                        table.ajax.reload(null, false);
                                    }

                                    if(typeof data.filterCount != 'undefined' && data.filterCount != null)
                                    {
                                        $(".common-filter-btn[data-item='"+data.module+"'] .num-notify").html(data.filterCount);
                                    }

                                    console.log($(".breadcrumb-select[name='view'] optgroup[label='MY VIEWS']").length);

                                    if($(".breadcrumb-select[name='view'] optgroup[label='MY VIEWS']").length == 0)
                                    {
                                        $(".breadcrumb-select[name='view'] optgroup[label='SYSTEM']").after("<optgroup label='MY VIEWS'></optgroup>");
                                    }

                                    var li = $(".breadcrumb-select[name='view']").closest('li');
                                    li.removeClass('prestar');
                                    li.find("optgroup[label='MY VIEWS']").append(data.viewHtml);
                                    li.find('.view-btns').html(data.viewActionHtml);
                                    $(".breadcrumb-select[name='view'] option:selected").removeAttr('selected');
                                    $(".breadcrumb-select[name='view'] optgroup[label='MY VIEWS'] option:last-child").attr('selected','selected');
                                    $(".breadcrumb-select[name='view']").trigger('change');
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                                else
                                {
                                    $('#common-view span.validation-error').html('');
                                    $.each(data.errors, function(index, value)
                                    {
                                        $("#common-view span[field='"+index+"']").html(value);
                                    });
                                    $('#common-view .processing').html("<span class='fa fa-exclamation-circle error'></span>");
                                }
                              }
                });
            });

            $(document).on("change", ".breadcrumb-select[name='view']", function(e)
            {
                var table = globalVar.jqueryDataTable;
                var formUrl = globalVar.baseAdminUrl + '/dropdown-view/' + $(this).val();
                var formData = { 'id' : $(this).val(), 'module' : $(this).data('module') };

                $.ajax(
                {
                    type    : 'POST',
                    url     : formUrl,
                    data    : formData,
                    dataType: 'JSON',
                    success : function(data)
                              {
                                var li = $(".breadcrumb-select[name='view']").closest('li');

                                if(data.status == true)
                                {
                                    li.removeClass('prestar');
                                    
                                    if(typeof data.tabTable != 'undefined' && typeof globalVar.dataTable[data.tabTable] != 'undefined')
                                    {
                                        table = globalVar.dataTable[data.tabTable];
                                    }

                                    if(typeof table != 'undefined')
                                    {
                                        table.ajax.reload(null, false);
                                    }

                                    if(typeof data.filterCount != 'undefined' && data.filterCount != null)
                                    {
                                        $(".common-filter-btn[data-item='"+data.module+"'] .num-notify").html(data.filterCount);
                                    }
                                    
                                    li.find('.view-btns').html(data.viewActionHtml);
                                    $('[data-toggle="tooltip"]').tooltip();
                                    $('html').getNiceScroll().resize();
                                }
                                else
                                {
                                    if(typeof data.viewId != 'undefined' && data.viewId != null)
                                    {
                                        $(".breadcrumb-select[name='view']").val(data.viewId);
                                        $(".breadcrumb-select[name='view']").trigger('change');                                 
                                    }

                                    $.notify({ message: 'Something went wrong.' }, globalVar.dangerNotify);
                                }                                
                              }
                });
            });
        });

        function openViewModal(moduleItem)
        {
            $('#common-view form').trigger('reset');               
            $('#common-view form').find('.select2-hidden-accessible').trigger('change');
            
            $('#common-view form').find('.white-select-type-single').select2('destroy').select2({containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});
            $('#common-view form').find('.white-select-type-single-b').select2('destroy').select2({minimumResultsForSearch : -1, containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});

            $('#common-view .processing').html('');
            $('#common-view .processing').hide();
            $('#common-view .none').hide();
            $('#common-view span.validation-error').html('');
            $("#common-view input[name='module']").val(moduleItem);

            $('#common-view').modal({
                show : true,
                backdrop: false,
                keyboard: false
            });
        } 
    </script>
@endpush