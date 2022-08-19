<script>
	$(document).ready(function()
	{
		$(document).on('click', '#deal-stage-progress .pg .icon', function(e)
		{
			var dealId = $(this).closest('#deal-stage-progress').data('id');
			var formUrl = globalVar.baseAdminUrl + '/deal/' + dealId + '/single-update';
			var stageId = $(this).closest('.pg').find('input').val();
			var formData = { 'deal_stage_id' : stageId };
			var realtime = 'deal_stage_id';
			var optionHtml = $(this).data('stage');
            var current = $(this).closest('.pg').find('.current').length;

            if(!current)
            {
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
                                    $("*[data-realtime='"+realtime+"']").attr('data-value', stageId);
                                    $("*[data-realtime='"+realtime+"']").html(optionHtml);

                                    if(typeof data.updatedBy != 'undefined' && data.updatedBy != null)
                                    {
                                        $("*[data-realtime='updated_by']").html(data.updatedBy);
                                    }   

                                    if(typeof data.lastModified != 'undefined' && data.lastModified != null)
                                    {
                                        $("*[data-realtime='last_modified']").html(data.lastModified);
                                    }   

                                    if(typeof data.realReplace != 'undefined')
                                    {
                                        $(data.realReplace).each(function(index, value)
                                        {
                                            $(value[0]).replaceWith(value[1]);                              
                                        });
                                    }

                                    if(typeof data.innerHtml != 'undefined')
                                    {
                                        $(data.innerHtml).each(function(index, value)
                                        {
                                            $(value[0]).html(value[1]);

                                            if($(value[0]).is('select') && value[2] == true)
                                            {
                                                $(value[0]).closest('.field').find('.value').attr('data-value', '');
                                                $(value[0]).closest('.field').find('.value').html('');
                                            }
                                        });
                                    }

                                    if(typeof data.tabTable != 'undefined' && typeof globalVar.dataTable[data.tabTable] != 'undefined')
                                    {
                                        globalVar.dataTable[data.tabTable].page('first').draw('page');
                                    }

                                    $.notify({ message: 'Update was successful' }, globalVar.successNotify);
                                }
                                else
                                {
                                    $.each(data.errors, function(index, value)
                                    {
                                        $.notify({ message: value }, globalVar.dangerNotify);
                                    });

                                    if(data.errors == null)
                                    {
                                        $.notify({ message: 'Something went wrong.' }, globalVar.dangerNotify);
                                    }
                                }
                              }          
                });
            }
		});

        $('.dealpipeline.breadcrumb-select').on('change', function(e)
        {
            var formUrl = $(this).closest('form').prop('action');
            var formData = $(this).closest('form').serialize();
            var row = $(this).closest('.row');
            var thisSelect = $(this);
            var currentPipelineId = row.find('.funnel-stage').data('pipeline');

            NProgress.start();
            row.find('.funnel-container .content-loader.all').show();
            row.find('.funnel-container').css('opacity', 0.85);
            $(this).attr('disabled', true);

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
                                row.find('.funnel-wrap').html(data.html);
                                $('[data-toggle="tooltip"]').tooltip();

                                heightAdjustment();
                                perfectScrollbarInit();                               
                                sortableInit();                               

                                if(typeof data.realtime != 'undefined')
                                {
                                    $.each(data.realtime, function(index, value)
                                    {
                                        $("*[data-realtime='"+index+"']").html(value);
                                    });
                                }

                                $('#add-new-btn').attr('data-default', 'deal_pipeline_id:' + thisSelect.val());                                                                  
                            }
                            else
                            {
                                $.each(data.errors, function(index, value)
                                {
                                    $.notify({ message: value }, globalVar.dangerNotify);
                                });

                                $(this).attr('disabled', false);
                                thisSelect.val(currentPipelineId).trigger('change');
                            }

                            row.find('.funnel-container .content-loader.all').fadeOut(500);
                            setTimeout(function() { NProgress.done(); $('.fade').removeClass('out'); }, 500);
                            row.find('.funnel-container').css('opacity', 1);
                          }
            });
        });
	});
</script>