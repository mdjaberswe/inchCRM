<div class='modal fade large' id='common-edit'>
    <div class='modal-dialog'>
    	<div class='modal-loader'>
    		<div class='spinner'></div>
    	</div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
   	        	<h4 class='modal-title capitalize'>Edit</h4>
    	    </div> <!-- end modal-header -->

    		{!! Form::open(['route' => null, 'method' => 'put', 'class' => 'form-type-a']) !!}
    		    <div id='common-edit-content' class='full'></div>
    		{!! Form::close() !!}

	 		<div class='modal-footer space btn-container'>
	 		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	 		    <button type='button' class='save btn btn-info'>Save</button>
	 		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end edit-form -->

@push('scripts')
	<script>
		$(document).ready(function()
		{
			$(document).on('click', '.common-edit-btn', function(event)
			{
				var id = $(this).attr('editid');
				var defaultData = typeof $(this).attr('data-default') !== 'undefined' ? $(this).attr('data-default') : null;
				var data = {'id' : id, 'html' : true, 'default' : defaultData};
				var tr = $($(this).closest('tr'));
				var url = $(this).attr('data-url');
				var updateUrl = $(this).attr('data-posturl');
				var title = typeof $(this).attr('modal-title') === 'undefined' ? 'Edit ' + $(this).attr('data-item') : $(this).attr('modal-title');
				title += typeof $(this).attr('modal-sub-title') === 'undefined' ? '' : " <span class='shadow bracket'>" + $(this).attr('modal-sub-title') + "</span>";
				
				$('#common-edit #common-edit-content').hide();
				$('#common-edit .modal-title').html(title);	

				$('#common-edit').addClass('large');
				if(typeof $(this).attr('modal-small') !== 'undefined')
				{
					$('#common-edit').removeClass('large');
					$('#common-edit').removeClass('medium');

					if($(this).attr('modal-small') != 'true')
					{
						$('#common-edit').addClass($(this).attr('modal-small'));
					}
				}

				getCommonEditData(id, data, url, updateUrl, tr, '#common-edit');
			});

			$('#common-edit .save').click(function()
			{				
				var form = $(this).parent().parent().find('form');
				modalCommonUpdate(form, '#common-edit');
			});
		});

		function modalCommonUpdate(form, modalId)
		{
			$(modalId + ' .processing').html("<div class='loader-ring-sm'></div>");
			$(modalId + ' .processing').show();			    

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
			                	$(modalId + ' span.validation-error').html('');
			                    $(modalId + ' .processing').html("<span class='fa fa-check-circle success'></span>");				                    
		                    	delayModalHide(modalId, 1);

		                    	if(typeof data.tabTable != 'undefined' && typeof globalVar.dataTable[data.tabTable] != 'undefined')
		                    	{
		                    		table = globalVar.dataTable[data.tabTable];
		                    	}

		                    	if(typeof table != 'undefined')
		                    	{
		                    		table.ajax.reload(null, false);
		                    		if(typeof data.saveId != 'undefined')
		                    		{
		                    			focusSavedRow(table, data.saveId);
		                    		}	
		                    	}
		                    	
    		                    if($('.calendar').get(0))
    		                    {
    	                    		var event = $.parseJSON(data.updateEvent);
    	                    		$('.calendar').fullCalendar('removeEvents', event.id);
    	                    		$('.calendar').fullCalendar('renderEvent', event);
    		                    }


    		                    if(typeof data.orgChartRefresh != 'undefined' && data.orgChartRefresh == true)
    		                    {
    		                    	var $orgChartContainer = $($(".view-hierarchy[data-module='"+ data.module +"']").get(0));
    		                    	var $orgChart = $($orgChartContainer.find('.orgchart'));
    		                    	var chartPosition = $orgChart.css('transform') != 'none' ? $orgChart.css('transform') : null;
    		                    	var centerTopNode = chartPosition == null ? true : false;
    		                    	orgChartRefresh($orgChartContainer.attr('id'), null, centerTopNode, chartPosition);
    		                    }	

    		                    if(typeof data.funnelJsonData != 'undefined')
    		                    {
   		                    		initD3Funnel(data.funnelId, data.funnelJsonData.arrayObject, data.funnelJsonData.pinched);
    		                    }

    		                    if(typeof data.pieData != 'undefined')
    		                    {
    		                    	initChartJsPie(data.pieId, data.pieData.labels, data.pieData.values, data.pieData.backgrounds);
    		                    }	

    		                    if(typeof data.timeline != 'undefined')
    		                    {
    		                    	initChartJsTimeline(data.timelineId, data.timeline.days, data.timeline.years, data.timeline.labels, data.timeline.alldata, data.timeline.backgrounds, data.timeline.borders);
    		                    }

    		                	if(typeof data.realtime != 'undefined')
    		                	{
    		                		$(data.realtime).each(function(index, value)
    		                		{
		                				$("*[data-realtime='"+value[0]+"']").html(value[1]);	                			
    		                		});
    		                	}

    		                	if(typeof data.notifyMsgs != 'undefined' && data.notifyMsgs.length)
    		                	{
    		                		$.each(data.notifyMsgs, function(index, msg)
    		                		{
    		                			$.notify({ message: msg }, globalVar.successNotify);
    		                		});
    		                	}

    		                	if(typeof data.viewName != 'undefined' && data.viewName != null)
    		                	{
    		                		$(".breadcrumb-select[name='view'] option[value='"+data.viewId+"']").html(data.viewName);
    		                		$(".breadcrumb-select[name='view']").trigger('change');
    		                		$(".breadcrumb-action.delete").attr('modal-sub-title', data.viewName);
    		                	}
			                }
			                else
			                {
			                	$(modalId + ' span.validation-error').html('');
			                	$.each(data.errors, function(index, value)
			                	{
			                		$(modalId + " span[field='"+index+"']").html(value);
			                	});
			                	$(modalId + ' .processing').html("<span class='fa fa-exclamation-circle error'></span>");
			                }
			              }
			});
		}

		function getCommonEditData(id, data, url, updateUrl, tr, modalId)
		{	
			// reset to default values
			$(modalId + ' form').trigger('reset');
			$(modalId + ' form').find('.select2-hidden-accessible').trigger('change');

			$(modalId + ' .processing').html('');
			$(modalId + ' .processing').hide();
			$(modalId + ' span.validation-error').html('');
			$(modalId + ' .save').attr('disabled', true);
			$(modalId + ' #common-edit-content').hide();
			$(modalId + ' .form-group').hide();
			$(modalId + ' .modal-loader').show();	

			$(modalId).modal({
                show : true,
                backdrop: false,
                keyboard: false
            });

			$.ajax(
			{
				type 	: 'GET',
				url 	: url,
				data 	: data,
				success	: function(data)
						  {
							if(data.status == true)
							{
								$dataObj = $(data.html);

								if($dataObj.length)
								{
								    $(modalId + ' #common-edit-content').html($dataObj);

								    if($(modalId + ' .toggle-permission').get(0))
								    {
								    	$(modalId + ' .child-permission').css('opacity', 1);
								    	$(modalId + ' .child-permission').find('input').attr('disabled', false);
								    	$(modalId + ' .child-permission').find("input[data-default='true']").prop('checked', true);
								    }	

								    if($(modalId + ' .modal-image').get(0))
								    {
								    	var defaultImage = $(modalId + ' .modal-image').data('image');
								    	$(modalId + ' .modal-image img').hide();
								    	$(modalId + ' .modal-image img').attr('src', defaultImage);
								    	$(modalId + ' .modal-image img').fadeIn(1500);
								    }

								    var imageLeft = $(modalId + ' .modal-title').width() + 50;
								    $(modalId + ' .modal-image').css('left', imageLeft + 'px');

								    var ps = new PerfectScrollbar(modalId + ' .modal-body');				                    
								    modalCurrency(tr, modalId);
								    pluginInit();
								}	

								$(modalId + ' form').prop('action', updateUrl);
								$(modalId + " input[name='_method']").val('PUT');
								$(modalId + ' form').find('select').prop('disabled', false);
								$(modalId + ' form').find('input').prop('readOnly', false);

								var hide = '';
								var show = '';

								if(typeof data.info.selectlist != 'undefined')
								{
									$.each(data.info.selectlist, function(fieldName, options)
									{
										var selectlist = $(modalId + " select[name='"+fieldName+"']").empty();

										$('<option/>', {
								            value: '',
								            text: '-None-'
								        }).appendTo(selectlist);

										$.each(options, function(optVal, displayText)
										{
											$('<option/>', {
									            value: optVal,
									            text: displayText
									        }).appendTo(selectlist); 
										});
									});	
								}

								$.each(data.info, function(index, value)
								{
									if($(modalId + " *[name='"+index+"']").get(0))
									{
										if($(modalId + " *[name='"+index+"']").is(':checkbox'))
										{
											if($(modalId + " *[name='"+index+"']").val() == value)
											{
												$(modalId + " *[name='"+index+"']").prop('checked', true);
											}
											else
											{
												$(modalId + " *[name='"+index+"']").prop('checked', false);
											}
										}
										else
										{
											$(modalId + " *[name='"+index+"']").not(':radio').val(value).trigger('change');
										}

										if($(modalId + " *[name='"+index+"']").is(':radio'))
										{
											$(modalId + " *[name='"+index+"']").each(function(index, obj)
											{
												if($(obj).val() == value)
												{
													$(obj).prop('checked', true);
												}
											});
										}
									}

									if(index == 'freeze')
									{
										$.each(value, function(key, val)
										{
											if($(modalId + " *[name='"+val+"']").is('select'))
											{
											    $(modalId + " *[name='"+val+"']").prop('disabled', true);
											}
											else
											{
												$(modalId + " *[name='"+val+"']").prop('readOnly', true);
											}
										});	
									}

									if(index == 'show')
									{
										$.each(value, function(key, val)
										{	
											show += modalId + " *[name='"+val+"'],";
										});

										show = show.slice(0,-1);
									}

									if(index == 'hide')
									{
										$.each(value, function(key, val)
										{
											$(modalId + " ."+val+"-input").hide();
											hide += '.'+val+'-input'+',';
										});

										hide = hide.slice(0,-1);
									}

									if(index == 'modal_image')
									{
										$(modalId + " .modal-image img").attr('src', value);
									}					
								});

								$(modalId + ' .datepicker').each(function(index, value)
								{
									$(this).datepicker('update', $(this).val());
								});

								$(modalId + ' .modal-loader').fadeOut(1000);

								$(show).closest('.none').show();
								$(show).closest('.none').parent('.none').show();
								$(modalId + ' .form-group').not(hide).show();	
								$(modalId + ' .modal-body').animate({ scrollTop: 1 }, 10);
								$(modalId + ' #common-edit-content').slideDown();
								$(modalId + ' .modal-body').animate({ scrollTop: 0 }, 10);							
								$(modalId + ' .save').attr('disabled', false);
							}
							else
							{
								$(modalId + ' .modal-loader').fadeOut(1000);
								$(modalId + ' .form-group').css('opacity', 0).slideDown('slow').animate({opacity: 1});
								$(modalId + ' .processing').show();
								$(modalId + ' .processing').html("<span class='fa fa-exclamation-circle error'></span>");
								delayModalHide(modalId, 2);
							}
						  }
			});
		}
	</script>
@endpush