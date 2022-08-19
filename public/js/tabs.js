$(document).ready(function()
{
	$('#item-tab li a').not('.not-load').click(function()
	{
		loadTabContent($(this));
	});


	$('#item-tab-details').on('click', '.tab-link', function(event)
	{
		loadTabContent($(this));
	});

	window.onpopstate = function(e)
	{
	    if(e.state)
	    {
	    	var item = $('#item-tab-details').attr('item').toLowerCase();

	    	if(e.state.html == null)
	    	{
	    		location.reload();
	    	}
	    	else
	    	{
    			$('#item-tab li a').removeClass('active');
    			$("#item-tab li a[tabkey='"+e.state.tabkey+"']").addClass('active');
    		    $('#item-tab-content').html(e.state.html);
    		    resetTabContent(item);
	    	}
	    }
	};	

	$('#item-tab-details').on('click.dt', '.show-hide', function(event)
	{
		var table = globalVar.jqueryDataTable;
		var tableId = '#' + $(this).attr('aria-controls');
		if(tableId != '#datatable' && typeof globalVar.dataTable[tableId] != 'undefined')
		{
			table = globalVar.dataTable[tableId];
		}
		
		var column = table.column($(this).index());				
        column.visible(!column.visible());
        if(column.visible())
        {
        	$(this).removeClass('unseen');
        }
        else
        {
        	$(this).addClass('unseen');
        }
	});

	$('#item-tab-details').on('click', '.show-hide-details a', function(event)
	{
		var detailsContent = $(this).closest('.show-hide-details').next('.details-content');		

		var hideDetails = 0;

		if(detailsContent.css('display') == 'none')
		{
			detailsContent.slideDown();			
			$(this).html("HIDE DETAILS <i class='fa fa-angle-up'></i>");			
		}
		else
		{
			hideDetails = 1;
			detailsContent.slideUp();
			$(this).html("SHOW DETAILS <i class='fa fa-angle-down'></i>");
		}

		var data = {'hide_details' : hideDetails}; 

		$.ajax({
		    type : 'GET',
		    data : data,
		    url  : $(this).attr('url')
		});		

		if(detailsContent.hasClass('none'))
		{
			var addHeight = 1;
			$(detailsContent).find('.content-section').each(function(index, obj)
			{
				addHeight += $(obj).height();
			});
			var newHeight = $('#item-tab-details').height() + addHeight;

			$('#item-tab-details').css('height', newHeight + 'px');
			$('html').getNiceScroll().resize();
			$('#item-tab-details').css('height', 'auto');
			detailsContent.removeClass('none');
		}
		else
		{
			$('html').getNiceScroll().resize();
		}		
	});

	$('#item-tab-details').on('click', '.save', function(e)
	{
		e.preventDefault();

		var form = $(this).closest('form');	
		var formUrl = form.prop('action');
		var formData = form.serialize();
		var item = $('#item-tab-details').attr('item');

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
		                	$('#item-tab-details span.validation-error').html('');  

		                	$.each(data.realtime, function(index, value)
		                	{
		                		$("span[realtime='"+index+"']").html(value);
		                	});  

		                	$.notify({ message: item + ' has been updated.' }, globalVar.successNotify);
		                }
		                else
		                {
		                	$('#item-tab-details span.validation-error').html('');

		                	$.each(data.errors, function(index, value)
		                	{
		                		$("#item-tab-details span[field='"+index+"']").html(value);
		                	});

		                	if(data.errors == null)
		                	{
		                		$.notify({ message: 'Something went wrong.' }, globalVar.dangerNotify);
		                	}
		                }
		              }
		});
	});

	$('#item-tab-details').on('click', '.private-users', function(event)
	{
		var modalTitle = $(this).attr('modal-title');
		var type = $(this).attr('type');
		var id = $(this).attr('editid');					
		var data = { 'id' : id, 'type' : type };
		var url = globalVar.baseAdminUrl + '/allowed-user-data/' + type + '/' + id;
		var saveUrl = globalVar.baseAdminUrl + '/allowed-user/' + type + '/' + id;

		$('#access-form form').trigger('reset');
		$('#access-form').find('.select2-hidden-accessible').trigger('change');
		$('#access-form .processing').html('');
		$('#access-form .processing').hide();
		$('#access-form span.validation-error').html('');
		$('#access-form .form-group').not('.always-show').hide();
		$('#access-form .modal-title .capitalize').html(type);
		$('#access-form .modal-title .shadow').html(modalTitle);
		$('#access-form .modal-loader').show();
		$('#access-form').modal();		

		$.ajax(
		{
			type 	: 'GET',
			url 	: url,
			data 	: data,
			success	: function(data)
					  {
						if(data.status == true)
						{
							$('#access-form').find('tbody').html(data.html);			  			
							$('[data-toggle="tooltip"]').tooltip();
							$('#access-form form').prop('action', saveUrl);
							$("#access-form input[name='id']").val(id);
							$("#access-form input[name='type']").val(type);
							$('#access-form .modal-loader').fadeOut(1000);
							$('#access-form .form-group').not('.always-show').css('opacity', 0).slideDown('slow').animate({opacity: 1});
						}
						else
						{
							$('#access-form .modal-loader').fadeOut(1000);
							$('#access-form .form-group').not('.always-show').css('opacity', 0).slideDown('slow').animate({opacity: 1});
							$('#access-form .processing').show();
							$('#access-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
							delayModalHide('#access-form', 2);
						}
					  }
		});
	});	

	$('#item-tab-details').on('change', '.reset-currency', function(event)
	{
		var div = $($(this).closest('div.amount'));	
		var currencyList = div.find('.currency-list');	
		var currencyId = $(this);
		var currencyIdVal = $(this).val();
		var icon = currencyList.find("li[value='"+currencyIdVal+"']").attr('icon');
		var alterIcon = currencyList.find("li[value='"+currencyIdVal+"']").attr('symbol');			
		var tagIcon = $(this).next('i');		

		resetCurrency(currencyList, currencyId, currencyIdVal, tagIcon, icon, alterIcon);
	});

	$('#item-tab-details').on('click', '.currency-list li', function(event)
	{
		var div = $($(this).closest('div.amount'));			
		var icon = $(this).attr('icon');
		var alterIcon = $(this).attr('symbol');
		var currencyIdVal = $(this).attr('value');
		var tagIcon = div.find('i');
		var currencyList = div.find('.currency-list');
		var currencyId = div.find("input[name='currency_id']");		

		resetCurrency(currencyList, currencyId, currencyIdVal, tagIcon, icon, alterIcon);
	});

	$('#item-tab-details').on('click', '.editable .edit', function(event)
	{
		$('#item-tab-details').find('.editable').addClass('edit-disabled');
		var field = $(this).closest('.field');
		var editSingle = field.find('.edit-single');
		var input = editSingle.find('input:text:first-child');
		var select = !editSingle.find('.choose-select').length ? editSingle.find('select') : editSingle.find('select.choose-select');
		var textarea = editSingle.find('textarea');
		var value = field.find('.value');
		var dataValue = value.attr('data-value');
		var dataMultiple = value.attr('data-multiple');
		var fieldVal = null;
		var secondFieldVal = null;

		if(typeof dataMultiple !== 'undefined')
		{
			fieldVal = dataValue.split('|')[0];
			secondFieldVal = dataValue.split('|')[1];
		}
		else
		{
			fieldVal = typeof dataValue !== 'undefined' ? dataValue : value.html().trim();
		}	

		if(field.hasClass('zero-border'))
		{
			editSingle.css('cssText', 'width: 250px!important;');
		}	

		field.find('.value').hide();
		editSingle.show();
			
		if(input.length && !(typeof input.attr('disabled') != 'undefined'))
		{
			input.val(fieldVal);
			input.focus();								
			input.setCursorPosition(input.val().length);

			if(secondFieldVal != null)
			{
				editSingle.find('input:nth-child(2)').val(secondFieldVal);
				editSingle.find('.amount input').val(secondFieldVal).trigger('change');
			}
		}

		if(typeof input.attr('disabled') != 'undefined')
		{
			input.val('');
		}

		if(select.length)
		{
			select.val(fieldVal);
			editSingle.attr('data-appear', 'true');
			editSingle.find('.select2-hidden-accessible').trigger('change');
			select.select2('open');
		}

		if(editSingle.find('.choose-select').length)
		{			
			editSingle.find("select[name='" + fieldVal + "_id']").val(secondFieldVal);
			editSingle.find('.select2-hidden-accessible').trigger('change');
			editSingle.attr('data-appear', 'false');
		}

		if(textarea.length)
		{
			textarea.val(fieldVal);
			textarea.focus();
			textarea.setCursorPosition(textarea.val().length);
		}				
	});

	$('#item-tab-details').on('keypress', '.edit-single input, .edit-single textarea', function(event)
	{
		var charCode = event.which;
		
		if(charCode == 13)
		{
			$(this).closest('.edit-single').find('.save-single').click();
		}
	});	

	$('#item-tab-details').on('click', '.edit-single a', function(event)
	{
		var editSingle = $(this).closest('.edit-single');
		var value = editSingle.prev('.value');
		var dataValue = value.attr('data-value');

		if($(this).hasClass('save-single'))
		{
			var actionUrl = editSingle.attr('data-action');
			var formData = editSingle.find('select, textarea, input').serialize();
			var formDataArray = editSingle.find('select, textarea, input').serializeArray();				
			var dataValueFormat = formDataArray.length > 1 ? formDataArray[0].value + '|' + formDataArray[1].value : formDataArray[0].value;
			var realtime = formDataArray[0].name;
			var optionHtml = editSingle.find('select option:selected').text();

			// confirm before move to new account
			if(typeof editSingle.attr('data-confirm-account') !== 'undefined' && editSingle.attr('data-confirm-account') == 'true')
			{
				if(dataValue != dataValueFormat)
				{
					$('#confirm-new-account .processing').html('');
					$('#confirm-new-account .processing').hide();
					$('#confirm-new-account span.validation-error').html('');
					$('#confirm-new-account .modal-body').animate({ scrollTop: 0 });
					$('#confirm-new-account form').trigger('reset');
					$('#confirm-new-account form').find('.select2-hidden-accessible').trigger('change'); 
					$('#confirm-new-account .none').hide();
					$("#confirm-new-account input[name='account_id']").val(dataValueFormat);

					$('#confirm-new-account').modal({
					    show : true,
					    backdrop: false,
					    keyboard: false
					});
					
					return false;
				}
			}

			$.ajax(
			{
				type 	: 'POST',
				url 	: actionUrl,
				data 	: formData,
				dataType: 'JSON',

				success : function(data)
				          {
    		                if(data.status == true)
    		                {
    		                	if(typeof dataValue !== 'undefined')
    		                	{
    		                		value.attr('data-value', dataValueFormat);
    		                		$("*[data-realtime='"+realtime+"']").attr('data-value', dataValueFormat);

    		                		if(optionHtml != '' && data.html == null)
    		                		{
    		                			optionHtml = dataValueFormat != '' ? optionHtml : '';
    		                			value.html(optionHtml);
    		                			$("*[data-realtime='"+realtime+"']").html(optionHtml);
    		                		}

    		                		if(data.html != null)
    		                		{
    		                			value.html(data.html);
    		                			$("*[data-realtime='"+realtime+"']").html(data.html);
    		                		}

    		                		if(typeof $("*[data-realtime='"+realtime+"']").data('datepicker') != 'undefined')
    		                		{
    		                			$("*[data-realtime='"+realtime+"']").closest('.field').find('.datepicker').datepicker('update', dataValueFormat);
    		                		}
    		                	}
    		                	else
    		                	{
    		                		value.html(dataValueFormat);
    		                		$("*[data-realtime='"+realtime+"']").html(dataValueFormat);
    		                	}

    		                	if(typeof data.updatedBy != 'undefined' && data.updatedBy != null)
    		                	{
    		                		$("*[data-realtime='updated_by']").html(data.updatedBy);
    		                	}	

    		                	if(typeof data.lastModified != 'undefined' && data.lastModified != null)
    		                	{
    		                		$("*[data-realtime='last_modified']").html(data.lastModified);
    		                	}	

    		                	if(typeof data.modalTitle != 'undefined' && data.modalTitle != null)
    		                	{
    		                		$('*[modal-title]').attr('modal-title', data.modalTitle);
    		                	}	

    		                	if(typeof data.realtime != 'undefined')
    		                	{
    		                		$(data.realtime).each(function(index, value)
    		                		{
		                				$("*[data-realtime='"+value[0]+"']").html(value[1]);	                			
    		                		});
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

    		                	$('#item-tab-details').find('.editable').removeClass('edit-false');
    		                	if(typeof data.editFalse != 'undefined')
    		                	{
    		                		$(data.editFalse).each(function(index, value)
    		                		{
		                				$($("*[name='"+value+"']").closest('.editable')).addClass('edit-false');	                			
    		                		});
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

    		                resetOverview(editSingle, data.status);
				          }          
			});
		}
		else
		{
			resetOverview(editSingle);
		}
	});

	$('#item-tab-details').on("change", "*[data-realtime-update='true'] input", function(event)
	{
		var realtimeUpdateContainer = $(this).closest("*[data-realtime-update='true']");
		var actionUrl = realtimeUpdateContainer.attr('data-action');
		var formData = realtimeUpdateContainer.find('input').serialize();

		$.ajax(
		{
			type 	: 'POST',
			url 	: actionUrl,
			data 	: formData,
			dataType: 'JSON',

			success : function(data)
			          {
		                if(data.status == true)
		                {
		                	if(typeof data.updatedBy != 'undefined' && data.updatedBy != null)
		                	{
		                		$("*[data-realtime='updated_by']").html(data.updatedBy);
		                	}	

            				realtimeUpdateContainer.attr('data-realtime-success-msg', 1);
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

		                	realtimeUpdateContainer.attr('data-realtime-success-msg', 0);
		                }
			          }          
		});
	});

	$('#item-tab-details').on("mouseenter", "*[data-realtime-update='true']", function(event)
	{
		$(this).attr('data-realtime-success-msg', 0);
	});
	
	$('#item-tab-details').on("mouseleave", "*[data-realtime-update='true']", function(event)
	{
		var successMsg = $(this).attr('data-realtime-success-msg');

		if(successMsg == '1' && !$(".alert.alert-success[role='alert']").get(0))
		{
			$.notify({ message: 'Update was successful' }, globalVar.successNotify);
		}
	});	
});

function loadTabContent(thisTabLink)
{
	if(!thisTabLink.hasClass('active'))
	{
		NProgress.start();

		var item = $('#item-tab-details').attr('item').toLowerCase();
		var itemId = $('#item-tab-details').attr('itemid');
		var infoType = thisTabLink.attr('tabkey');
		var thisTab = thisTabLink;
		var tabUrl = $('#item-tab-details').attr('taburl');
		var ItemIdUrl = itemId == '' ? itemId : '/' + itemId;
		var ajaxUrl = globalVar.baseAdminUrl + '/' + tabUrl + ItemIdUrl + '/' + infoType;		
		var ajaxData = { id : itemId, type : infoType };
		var lastUrlArg = window.location.href.split('/').last();
		var pushState = (lastUrlArg == itemId) ? itemId + '/' + infoType : infoType;

		if(thisTabLink.hasClass('tab-link'))
		{
			thisTab = $('#item-tab').find("a[tabkey='"+infoType+"']");
		}

		$.ajax(
		{
			type 	: 'POST',
			url		: ajaxUrl,
			data 	: ajaxData,

			success	: function(data)
					  {
					  	$dataObj = $(data);

					  	if($dataObj.length)
					  	{
					  		if(itemId != '' && data.length < 320000)
					  		{
					  			window.history.pushState({ 'html' : data, 'tabkey' : infoType }, '', pushState);
					  		}
					  		else
					  		{
					  			window.history.pushState({ 'html' : null, 'tabkey' : infoType }, '', pushState);
					  		}

				  		  	$('#item-tab li a').removeClass('active');
				  		  	thisTab.addClass('active');

				  			$('#item-tab-content').html($dataObj);	
				  			setTimeout(function() { NProgress.done(); $('.fade').removeClass('out'); }, 500);

				  			resetTabContent(item);
					  	}		    					  		    						
					  },

			error 	: function(jqXHR, textStatus, errorThrown)
					  {
					  	location.reload();
					  }			  
		});
	}	
}

function resetTabContent(item)
{
	$('html').getNiceScroll().resize();	
	$('[data-toggle="tooltip"]').tooltip();
	$('html, body').animate({ scrollTop: 0 }, 'fast');

	if($('.datepicker').get(0))
	{
		$('.datepicker').not('.only-view').datepicker({
		    format: 'yyyy-mm-dd'
		});
	}

	atWhoInit();
	dropzoneInit();
	orgChartInit();
	select2PluginInit();
	perfectScrollbarInit();
	tabDatatableInit(item);
}

function resetOverview(editSingle, reset = true)
{
	if(reset)
	{
		if(editSingle.parent().hasClass('zero-border'))
		{
			editSingle.css('cssText', 'width: auto!important;');
		}
		editSingle.prev('.value').show();
		editSingle.hide();
		$('#item-tab-details').find('.editable').removeClass('edit-disabled');

		$('[data-toggle="tooltip"]').tooltip();
		$('html').getNiceScroll().resize();
	}		
}
