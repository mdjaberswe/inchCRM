$.ajaxSetup(
{
    headers: 
    {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    beforeSend: function(jqXHR)
    			{ 
    				globalVar.ajaxRequest.push(jqXHR);
    			},
    complete: function(jqXHR)
    		  {
        		var i = globalVar.ajaxRequest.indexOf(jqXHR);
        		if(i > -1) globalVar.ajaxRequest.splice(i, 1);
    		  }
});

NProgress.start();

$(document).ready(function()
{
	$('body').css('min-height', $(window).height() + 'px');
	setTimeout(function() { NProgress.done(); $('.fade').removeClass('out'); }, 1500);
	$('main').fadeIn(1500).css('display', 'inline-block');
	$('#content-loader').fadeOut(1500);

	$('[data-toggle="tooltip"]').tooltip();

	resetCheckboxRadio();

	$('.alert').not('.slight').delay(7500).fadeOut(1500);

	if($('header').get(0))
	{
		$(window).scroll(function()
		{
			if($(this).scrollTop() > 20)
			{
				$('header').addClass('scroll');			
			}
			else
			{			
				$('header').removeClass('scroll');	
			}
		});
	}

	if($('nav').get(0))
	{
		$('nav').css('height', $(window).height() - 51 + 'px');

		var listSize = $('nav ul li a').size();
		
		for(var i=0; i <= listSize-1; i++)
		{
			var parentArray = $('nav ul li a').eq(i).parents().map(function()
								{
								    return this.tagName;
								}).get();

			var navIndex = jQuery.inArray('NAV', parentArray);
			var navArray = parentArray.splice(0, navIndex);

			var left = -1;

			jQuery.each(navArray, function(key,value)
			{
				if(value == 'UL')
				{
					left = left + 1;
				}
			});

			if(left > 0)
			{
				$('nav ul li a').eq(i).css('text-indent', 26+left*15+'px');
				$('nav ul li a i').eq(i).css('text-indent', left*15+'px');
			}		
		}
	}

	if($('nav.compress ul').get(0))
	{
		var listSize = $('nav ul li').not('.heading').size();
		
		var firstRowListCount = 0;

		for(var i=0; i <= listSize-1; i++)
		{
			var parentArray = $('nav ul li').eq(i).parents().map(function()
								{
								    return this.tagName;
								}).get();

			var navIndex = jQuery.inArray('NAV', parentArray);
			var navArray = parentArray.splice(0, navIndex);

			if(navArray.length == 1 && navArray[0] == 'UL')
			{
				firstRowListCount = firstRowListCount + 1;
			}	
		}

		var requiredUlHeight = firstRowListCount * 39;

		var navHeight = $(window).height() - 51;

		if(requiredUlHeight > navHeight)
		{
			$('nav.compress ul').css('height', requiredUlHeight + 'px');
			$('nav.compress ul li ul').css('height', 'auto');
		}

		var logoWidth = Math.floor($('#logo').width());
		var ulChildWidth = 205 - logoWidth;
		$('nav.compress ul').css('width', logoWidth + 'px');
		$('#logo').css('width', $('nav.compress ul').width() + 'px');
		$('nav.compress ul li ul').css('width', ulChildWidth + 'px');
	}

	heightAdjustment();

	$('.breadcrumb form').trigger('reset');	
	$('.breadcrumb form').find('.select2-hidden-accessible').trigger('change');

	// Events
	$(window).resize(function()
	{
		responsiveMediaQuery();
		heightAdjustment();

		$('body').css('min-height', $(this).height() + 'px');
		$('nav').css('height', $(this).height() - 51 + 'px');	

		if($('#top-nav').hasClass('expand'))
		{
			$('#top-nav').css('width', $(this).width() - $('#logo').width() + 'px');
		}	

		if($('nav.compress ul').get(0))
		{
			var listSize = $('nav ul li').not('.heading').size();
			
			var firstRowListCount = 0;

			for(var i=0; i <= listSize-1; i++)
			{
				var parentArray = $('nav ul li').eq(i).parents().map(function()
									{
									    return this.tagName;
									}).get();

				var navIndex = jQuery.inArray('NAV', parentArray);
				var navArray = parentArray.splice(0, navIndex);

				if(navArray.length == 1 && navArray[0] == 'UL')
				{
					firstRowListCount = firstRowListCount + 1;
				}	
			}

			var requiredUlHeight = firstRowListCount * 39;

			var navHeight = $(window).height() - 51;

			if(requiredUlHeight > navHeight)
			{
				$('nav.compress ul').css('height', requiredUlHeight + 'px');
				$('nav.compress ul li ul').css('height', 'auto');
			}
			else
			{
				$('nav.compress ul').css('height', navHeight + 'px');
				$('nav.compress ul li ul').css('height', 'auto');
			}
		}	

		$('html').getNiceScroll().resize();
		$('nav').getNiceScroll().resize();
	});

	$('.menu-toggler').click(function()
	{
		$('#logo').toggleClass('compress');
		$('#top-nav').toggleClass('expand');
		$('nav').toggleClass('compress');
		$('main').toggleClass('expand');

		var hasCompress = $('nav.compress ul').get(0);

		if(hasCompress)
		{
			$('nav.compress').find('.collapse').css('display', 'none');
			$('nav.compress').find('span.fa-angle-left').removeClass('down');
			$('nav.compress').find('.tree').removeClass('active');

			var logoWidth = Math.floor($('#logo').width()) - 1;
			var ulChildWidth = 205 - logoWidth;
			$('nav.compress ul').css('width', logoWidth + 'px');
			$('#logo').css('width', $('nav.compress ul').width() + 'px');
			$('nav.compress ul li ul').css('width', ulChildWidth + 'px');

			var listSize = $('nav ul li').not('.heading').size();
			
			var firstRowListCount = 0;

			for(var i=0; i <= listSize-1; i++)
			{
				var parentArray = $('nav ul li').eq(i).parents().map(function()
									{
									    return this.tagName;
									}).get();

				var navIndex = jQuery.inArray('NAV', parentArray);
				var navArray = parentArray.splice(0, navIndex);

				if(navArray.length == 1 && navArray[0] == 'UL')
				{
					firstRowListCount = firstRowListCount + 1;
				}	
			}

			var requiredUlHeight = firstRowListCount * 39;

			var navHeight = $(window).height() - 51;

			if(requiredUlHeight > navHeight)
			{
				$('nav.compress ul').css('height', requiredUlHeight + 'px');
				$('nav.compress ul li ul').css('height', 'auto');
			}
		}
		
		if(typeof hasCompress === 'undefined')
		{
			$('nav ul').removeAttr('style');
			$('#logo').removeAttr('style');
			$('nav ul li ul').removeAttr('style');
			$('#top-nav').removeAttr('style');

			var listSize = $('nav ul li a').size();
			
			for(var i=0; i <= listSize-1; i++)
			{
				var parentArray = $('nav ul li a').eq(i).parents().map(function()
									{
									    return this.tagName;
									}).get();

				var navIndex = jQuery.inArray('NAV', parentArray);
				var navArray = parentArray.splice(0, navIndex);

				var left = -1;

				jQuery.each(navArray, function(key,value)
				{
					if(value == 'UL')
					{
						left = left + 1;
					}
				});

				if(left > 0)
				{
					$('nav ul li a').eq(i).css('text-indent', 26+left*15+'px');
					$('nav ul li a i').eq(i).css('text-indent', left*15+'px');
				}		
			}
		}
	});

	$('.mob-menu-toggler').click(function()
	{
		$('#logo').removeClass('compress');
		$('#top-nav').removeClass('expand');
		$('nav').removeClass('compress');
		$('main').removeClass('expand');

		$('#logo').removeAttr('style');
		$('#top-nav').removeAttr('style');
		$('nav ul').removeAttr('style');
		$('nav ul li ul').removeAttr('style');
		
		$('nav').find('.collapse').css('display', 'none');
		$('nav').find('span.fa-angle-left').removeClass('down');
		$('nav').find('.tree').removeClass('active');

		$('nav').toggleClass('show');
		$('main').toggleClass('shadow');
	});

	$('main').click(function()
	{
		$('nav').removeClass('show');
		$(this).removeClass('shadow');
	});
	
	$('.alert button.close').click(function()
	{
	    $(this).parent().hide();
	});

	$(document).on('shown.bs.tooltip', '[data-toggle="tooltip"]', function()
	{
		if(typeof $(this).attr('data-animation') != 'undefined')
		{
			var animationType = $(this).attr('data-animation');
			$('.tooltip').addClass('animated ' + animationType);
		}
	});

	$('nav').keydown(function(e)
	{
		switch(e.which)
		{
			case 38 :
				$('nav').getNiceScroll().resize();
			break;

			case 40 :
				$('nav').getNiceScroll().resize();
			break;
				
			default : return;
		}

		e.preventDefault();
	});

	$('nav').mousewheel(function()
	{
	    $('nav').getNiceScroll().resize();
	});

	$('nav ul li a').hover(function()
	{
		if($(this).hasClass('active') == false)
		{
			$(this).css('background-position', '-'+$(this).width()+'px');
		}		
	});

	$('nav ul li a').focus(function()
	{
		if($(this).hasClass('active') == false)
		{
			$(this).css('background-position', '-'+$(this).width()+'px');
		}	
	});	

	$('nav ul li a').click(function()
	{
		if($(this).hasClass('active') == false)
		{
			$(this).css('background-position', '-'+$(this).width()+'px');
		}	
	});

	$('nav ul li a').mouseleave(function()
	{
		$(this).css('background-position', 0+'px');
	});

	var previousClickTime = new Date();

	$('nav .tree').click(function()
	{
		var currentClickTime = new Date();
		var diffTime = (currentClickTime.getTime() - previousClickTime.getTime()) / 1000;

		if(diffTime > 0.5)
		{
			$(this).parent('li').parent('ul').find('.collapse').not($(this).next('.collapse')).slideUp();
			$(this).parent('li').parent('ul').find('.tree').not($(this)).removeClass('active');
			$(this).parent('li').parent('ul').find('span.fa-angle-left').not($(this).children('span.fa-angle-left')).removeClass('down');
			$(this).toggleClass('active');
			$(this).children('span.fa-angle-left').toggleClass('down');
			$(this).next('.collapse').slideToggle();

			previousClickTime = currentClickTime;
		}
	});

	var previousUpTime = new Date();

	$(document).on('mousedown', '.orgchart', function()	
	{
		var thisOrgChart = $(this);
		var currentDownTime = new Date();
		var diffTime = (currentDownTime.getTime() - previousUpTime.getTime()) / 1000;

		setTimeout(function()
		{
			if(thisOrgChart.css('cursor') == 'move' && diffTime > 1)
			{
				thisOrgChart.addClass('dragging');
			}			
		}, 400);
	});

	$(document).on('mouseup', '.orgchart', function()	
	{
		$(this).removeClass('dragging');
		previousUpTime = new Date();
	});	

	$(document).on('click, mouseleave', '.view-hierarchy', function()
	{
		$(this).find('.orgchart').removeClass('dragging');
	});

	$(document).on('mousewheel', '.orgchart', function(e)	
	{
		e.stopPropagation();
	});	

	$(document).on('click', '.orgchart-export', function(e)	
	{
		var orgChartId = $(this).data('orgchart-id');
		var exportFileName = $(this).data('export-name');
		var exportFileType = $(this).data('export-type');	

		if(typeof globalVar.orgChart[orgChartId] != 'undefined')
		{
			globalVar.orgChart[orgChartId].export(exportFileName, exportFileType);
		}
	});	

	$(document).on('mouseleave', '.node', function(e)	
	{
		$(this).find('.dropdown-toggle').attr('area-expand', 'false');
		$(this).find('.node-btn').removeClass('open');
	});	

	$(document).on('click', '.node .dropdown-menu a', function(e)	
	{
		$(this).closest('.node-btn').children('.dropdown-toggle').attr('area-expand', 'false');
		$(this).closest('.node-btn').removeClass('open');
	});

	$(document).on('mouseleave', '.progress-line .pg .icon', function(e)
	{
	    $(this).closest('.progress-line').find('.icon').tooltip();
	});

	$(document).on('click', '.dropdown-toggle', function()	
	{
		var defaultAnimation = 'flipInY|flipInY';
		var animation = $(this).attr('animation');
		animation = typeof animation !== 'undefined' ? animation : defaultAnimation;
		animation = animation.split('|');
		var appear = animation[0];
		var disappear = animation[1];
		var dropdownMenu = $(this).parent().find('.dropdown-menu');
		$($(this).closest('.dropdown-menu')).removeClass('animated ' + disappear);		
		dropdownMenu.addClass('animated ' + appear);
		$('html').getNiceScroll().resize();
	});

	$('main').on('click', '.form-type-a, .form-type-b', function()	
	{
		var hoverStatus = false;
		var inlineInputSize = $('.inline-input').size();

		for(var i=0; i <= inlineInputSize-1; i++)
		{
			if($('.inline-input').eq(i).is(':hover'))
			{
				hoverStatus = true;
			}
		}
			
		if(hoverStatus == false)
		{
			$('.inline-input').removeClass('focus');
		}
	});

	$('.form-type-a .modal-body').scroll(function()
	{
		var modalImage = $($(this).closest('form')).find('.modal-image');

		if($(this).scrollTop() > 15)
		{
			modalImage.addClass('small');	
		}
		else
		{
			modalImage.removeClass('small');	
		}
	});

	$('.modal-delete').click(function(event)
	{
		event.preventDefault();
		var formUrl = $(this).parent('form').prop('action');
		var formData = $(this).parent('form').serialize();
		var itemName = $(this).parent('form').attr('data-item');
		var message = 'This ' + itemName.toLowerCase() + ' will be removed along with all associated data.<br>Are you sure you want to delete this ' + itemName.toLowerCase() + '?'; 
		confirmDelete(formUrl, formData, null, itemName, message);
	});

	$(document).on('click', '.show-if input', function()
	{
		var showIf = $(this).closest('.show-if');
		var indicatorChecked = showIf.find('.indicator').prop('checked');
		var noneBox = $(showIf.next('.none'));	
		var modalBody = $($(this).closest('.modal-body'));
		var fromGroupContainer = $(modalBody.find('.form-group-container'));
		var containerHeight = parseInt(fromGroupContainer.height());
		var noneHeight = parseInt(noneBox.height());
		var down = containerHeight + noneHeight;
		var up = nonNegative(containerHeight - noneHeight - 465);
		var scroll = 0;

		if($(this).hasClass('indicator') && $(this).prop('checked'))
		{
			if(showIf.attr('flush'))
			{
				noneBox.find("input[type='checkbox']:enabled").prop('checked', false);
				noneBox.find('select').val('');
				noneBox.find('.select2-hidden-accessible').trigger('change');
			}
				
			showIf.next('.none').slideDown();
			scroll = down;
		}
		else
		{
			if(!indicatorChecked)
			{
				showIf.next('.none').slideUp();
				scroll = up;
			}
		}

		if((showIf.attr('scroll') && $(this).hasClass('indicator')) || ($(this).is(':radio') && showIf.next('.none').css('display') == 'block'))
		{
			modalBody.animate({ scrollTop: scroll });
		}
	});

	$(document).on('change', '.icon-changer', function(e)
	{
		var form = $(this).closest('form');
		var icon = $('option:selected', this).attr('data-icon');
		var childIcon = $(this).attr('data-child');

		if($(this).val() != '' && $(this).val() != null)
		{
			form.find("*[data-parent='"+ childIcon +"']").attr('class', icon);
		}	
	});

	$(document).on('change', '.multiple-child', function(e)
	{
		var form = $(this).closest('form');
		var childGroupClass = '.' + $(this).attr('data-child');
		var optionGroup = $('option:selected', this).attr('for');

		if($(this).val() != '' && $(this).val() != null)
		{
			if(typeof optionGroup != 'undefined')
			{
				var showGroup = $(form.find(childGroupClass + "[data-for='"+ optionGroup +"']"));

				form.find(childGroupClass).not(showGroup).each(function(index, group)
				{
					$(group).find('select').val('');
					$(group).find('.select2-hidden-accessible').trigger('change');	
					$(group).find('input').val('');
					$(group).find('.validation-error').html('');
				});
				form.find(childGroupClass).not(showGroup).hide();

				if(showGroup.css('display') != 'block')
				{
					showGroup.find('select').val('');
					showGroup.find('.select2-hidden-accessible').trigger('change');	
					showGroup.find('input').val('');
					showGroup.find('.validation-error').html('');
					showGroup.slideDown();
				}				
			}
			else
			{
				form.find(childGroupClass).each(function(index, group)
				{
					$(group).find('select').val('');
					$(group).find('.select2-hidden-accessible').trigger('change');	
					$(group).find('input').val('');
					$(group).find('.validation-error').html('');
				});
				form.find(childGroupClass).hide();
			}
		}
		else
		{
			form.find(childGroupClass).hide();
		}
	});

	$(document).on('change', '.show-if select', function()
	{
		var modalDialog = $($(this).closest('.modal-dialog'));
		var modalLoader = modalDialog.find('.modal-loader').css('display');
		if(modalLoader == 'block')
		{
			return false;
		}

		var selectVal = $(this).val();
		var dropdownClass = '.' + selectVal + '-list';
		var showIf = $(this).closest('.show-if');
		var noneBox = $(showIf.next('.none'));	
		var modalBody = $(this).closest('.modal-body');

		if(selectVal != '')
		{
			if(typeof noneBox.find(dropdownClass).data('default') != 'undefined')
			{
				defaultVal = noneBox.find(dropdownClass).data('default');
				noneBox.find(dropdownClass + ' select').val(defaultVal);
				noneBox.find(dropdownClass + ' input').val(defaultVal);
			}
			else
			{
				noneBox.find('select').val('');					
				noneBox.find('input').val('');
			}

			noneBox.find('.select2-hidden-accessible').trigger('change');
			noneBox.find('.validation-error').html('');
			noneBox.find('.none').not(dropdownClass).hide();	
			noneBox.find(dropdownClass).show();	

			if(typeof $(this).attr('data-for-child') != 'undefined' && typeof $(noneBox.find(dropdownClass)).attr('data-for-parent') != 'undefined')
			{
				$(noneBox.find(dropdownClass)).find('option').each(function(index, option)
				{
					if(typeof $(option).attr('for') != 'undefined')
					{
						$(option).attr('for', selectVal);
					}					
				});
			}

			if(typeof showIf.attr('data-slide') != 'undefined')
			{
				noneBox.hide();				
			}	

			if(typeof showIf.data('show-only') != 'undefined')
			{
				if(showIf.data('show-only') == selectVal)
				{
					noneBox.slideDown();
				}
				else
				{
					noneBox.hide();
					noneBox.find('.none').hide();
				}
			}
			else
			{
				noneBox.slideDown();
			}												
		}
		else
		{
			noneBox.hide();
			noneBox.find('.none').hide();
		}
	});

	$('.show-if-true').hide();

	$('.fetch-show-if select').change(function()
	{
		var showIf = $(this).closest('.fetch-show-if');
		var dataUrl = showIf.attr('data-url');
		var showIfTrue = '.show-if-true.' + showIf.attr('child-class');
		var modalId = $(this).closest('.modal').attr('id');
		var selectVal = $(this).val();
		var nextSelectDiv = $(showIf.next('.show-if-true'));
		var nextSelect = nextSelectDiv.find('select').empty();
		var nextSelectDefault = nextSelect.attr('data-default');

		$('<option/>', { value : '', text : '-None-' }).appendTo(nextSelect);

		if(selectVal == '')
		{     
		    $(showIfTrue).slideUp();                
		}
		else
		{
		    $.ajax(
		    {
		        type    : 'GET',
		        url     : dataUrl,
		        data    : { 'id' : selectVal },
		        dataType: 'JSON',
		        success : function(data)
		                  {
		                    if(data.status == true)
		                    {
		                        $.each(data.optionList, function(id, name)
		                        {
		                            $('<option/>', { value : id, text : name }).appendTo(nextSelect);
		                        });

		                        if(modalId == 'edit-form')
		                        {
		                            var defaultVal = $("input[name='"+nextSelectDefault+"']").val();
		                            nextSelect.val(defaultVal).trigger('change');
		                            $("input[name='"+nextSelectDefault+"']").val('');
		                        }                                    
		                        
		                        nextSelectDiv.find('span.validation-error').html('');
		                    }
		                    else
		                    {
		                        nextSelectDiv.find('span.validation-error').html(data.error);
		                    }
		                  }
		    });

		    $(showIfTrue).slideDown();
		} 

		nextSelect.trigger('change');   
	});	

	$(document).on("change", "select[data-append-request='true']", function(e)
	{
		var form = $($(this).closest('form'));
		var parent = $(this).data('parent');
		var child = $(this).data('child');
		var dataUrl = globalVar.baseAdminUrl + '/dropdown-append-list/' + parent + '/' + child;
 		var field = $(this).attr('name');
		var id = $(this).val();
		var append = $(form.find("select[data-append='"+ child +"']"));
		var appendlist = append.empty();
		var appendDiv = $(append.closest('.form-group'));
		var invalidInput = $(appendDiv.find("input[data-invalid='true']"));
		var hiddenInput = $(appendDiv.find("input[data-default='true']"));

		$('<option/>', { value : '', text : '-None-' }).appendTo(appendlist);
		append.val('');
		appendDiv.find('.select2-hidden-accessible').trigger('change');

		if(id == '')
		{     
		    append.attr('disabled', true);
		    appendDiv.tooltip('enable');
		}
		else
		{
			append.attr('disabled', false);
			appendDiv.tooltip('disable');

		    $.ajax(
		    {
		        type    : 'GET',
		        url     : dataUrl,
		        data    : { 'field' : field, 'id' : id },
		        dataType: 'JSON',
		        success : function(data)
		                  {
		                    if(data.status == true)
		                    {
		                        $.each(data.selectOptions, function(id, name)
		                        {
		                        	if(id != invalidInput.val())
		                        	{
		                        		$('<option/>', { value : id, text : name }).appendTo(appendlist);
		                        	}		                            
		                        });  

		                        if(hiddenInput.val() != null && hiddenInput.val() != '')
		                        {
		                        	$("select[data-append='"+ child +"']").val(hiddenInput.attr('value'));
		                        	$(append.closest('.form-group')).find('.select2-hidden-accessible').trigger('change');
		                        	hiddenInput.val('');
		                        }                             
		                        
		                        appendDiv.find('span.validation-error').html('');
		                    }
		                    else
		                    {
		                        appendDiv.find('span.validation-error').html(data.error);
		                    }
		                  }
		    });
		}
	});

	$('.inline-input').focusin(function()
	{
		$(this).addClass('focus');
	});

	$('.inline-input').focusout(function()
	{
		if($(this).is(':hover') == false)
		{
			$(this).removeClass('focus');
		}	
	});

	$(document).on('change', '.related-field .parent-field select', function(e)
	{
		var container = $($(this).closest('.related-field'));
		var parent = $($(this).closest('.parent-field'));
		var child = $(container.find('.child-field'));
		var editSingle = $($(this).closest('.edit-single'));

		if($(this).val() == '')
		{
			child.find("*[data-field]").hide();
			child.find("*[data-default='true']").show();
			child.find("*[data-child='true']").val(null);
		}
		else
		{			
			child.find("*[data-field]").hide();
			var selectChild = $(child.find("*[data-field='" + $(this).val() + "']"));
			var appearEditSingle = (typeof editSingle.attr('data-appear') != 'undefined' && editSingle.attr('data-appear') == 'false');

			if(!editSingle.length || appearEditSingle)
			{
				selectChild.find('select').val('');
				selectChild.find('.select2-hidden-accessible').trigger('change');
			}

			var selectVal = selectChild.find('select').val();
			selectChild.show();
			child.find("*[data-child='true']").val(selectVal);
		}
	});

	$(document).on('change', '.related-field .child-field select', function(e)
	{
		var container = $($(this).closest('.child-field'));
		container.find("*[data-child='true']").val($(this).val());
	});

	$(document).on("change", ".related-field .child-field *[data-child='true']", function(e)
	{	
		var container = $($(this).closest('.related-field'));
		var parent = $(container.find('.parent-field'));
		var parentVal = parent.find('select').val();
		var child = $($(this).closest('.child-field'));

		if($(this).val() == '')
		{
			child.find("*[data-field]").hide();
			child.find("*[data-default='true']").show();
		}
		else
		{
			child.find("*[data-field]").hide();
			var selectChild = $(child.find("*[data-field='" + parentVal + "']"));	
			selectChild.find('select').val($(this).val());
			selectChild.find('.select2-hidden-accessible').trigger('change'); 		
			selectChild.show();
		}		
	});	

	$(document).on('click', '.toggle-permission .switch', function()
	{
		var checked = $(this).find('input').prop('checked');
		var togglePermission = $($(this).closest('.toggle-permission'));
		var childPermission = $(togglePermission.find('.child-permission'));

		if(checked == true)
		{
			childPermission.css('opacity', 1);
			childPermission.find('input').attr('disabled', false);
			childPermission.find("input[data-default='true']").prop('checked', true);
		}
		else
		{
			childPermission.css('opacity', 0.5);
			childPermission.find('input').attr('disabled', true);
			childPermission.find('input').prop('checked', false);
		}
	});

	$('main').on('click', '.inline-input span', function()
	{
		$('.inline-input').not($(this).parent()).removeClass('focus');
		$(this).parent().addClass('focus');

		var checked = $(this).find('input').prop('checked');
		var inputType = $(this).find('input').prop('type');

		if(checked == true)
		{
			if(inputType != 'radio')
			{
				$(this).find('input').prop('checked', false);
			}			
		}
		else
		{
			$(this).find('input').prop('checked', true);
		}
	});

	$('main').on('click', '.inline-input span input', function()	
	{
		var checked = $(this).prop('checked');
		if(checked == true)
		{
			$(this).prop('checked', false);
		}
		else
		{
			$(this).prop('checked', true);
		}
	});

	$('.input-msg').on('keyup keydown blur change', function(e)
	{
		var val = $(this).val();
		var currentHeight = $(this).height() + 22;
		var currentScrollHeight = $(this).prop('scrollHeight');
		var messageBox = $(this).parent().find('.div-message-box');

		if($(this).val() == '')
		{
			$(this).css('height', '50px');
			messageBox.css('height', '425px');
			$(this).css('overflow', 'hidden');
		}
		else
		{
			if(currentHeight !== currentScrollHeight && currentScrollHeight < 150)
			{				
				var minusHeight = currentScrollHeight - 50;
				var changeHeight = 425 - minusHeight;
				$(this).css('height', currentScrollHeight + 'px');
				$(this).css('overflow', 'hidden');
				messageBox.css('height', changeHeight + 'px');
			}		
			else
			{
				if(currentScrollHeight > 150)
				{
					$(this).css('overflow', 'auto');
				}				
			}	
		}		
	});

	$('.nav.nav-tabs li a').mouseleave(function()
	{
	    $('[data-toggle="tooltip"]').tooltip('hide');
	});

	$('.left-icon, .right-icon').tooltip('disable');

	$(document).on('mouseover', '.left-icon, .right-icon', function()	
	{
		if($(this).find('input').val() == '')
		{
			$(this).tooltip('disable');
		}
		else
		{
			$(this).tooltip('enable');

			if($(this).parent().children('.tooltip').length == 0)
			{
				$(this).tooltip('show');
			}			
		}
	});	

	$(document).on('mouseleave', '.funnel-wrap', function(e)	
	{
		$(this).children('.funnel-container-arrow.left').css('left', '-70px');
		$(this).children('.funnel-container-arrow.right').css('right', '-70px');
	});

	$(document).on('mouseover', '.funnel-stage', function(e)	
	{
		var container = $($(this).parent('.funnel-container'));		
		kanbanLeftRight(container);
	});

	$(document).on('mouseenter', '.funnel-stage', function(e)
	{
		if($(this).find('.li-container').height() < $(this).height() && $(this).hasClass('loading'))
		{
			ajaxKanbanCard($(this).find('.funnel-card-container'));
		}
	});

	$(document).on('mouseover', '.funnel-container-arrow.left', function(e)	
	{
		var container = $($(this).parent('.funnel-wrap').find('.funnel-container'));
		container.stop(true);	
		container.animate({scrollLeft : 0}, 1500);
	});

	$(document).on('mouseover', '.funnel-container-arrow.right', function(e)		
	{
		var container = $($(this).parent('.funnel-wrap').find('.funnel-container'));	
		var containerWidth = container.innerWidth();
		var totalStages = container.children('.funnel-stage').size();
		var totalStagesWidth = totalStages * 300;
		var containerMaxRightPos = totalStagesWidth > containerWidth ? totalStagesWidth : containerWidth;
		var goRightVal = nonNegative(containerMaxRightPos - containerWidth);
		container.stop(true);
		container.animate({scrollLeft : goRightVal}, 1500);
	});

	$(document).on('mouseleave', '.funnel-container-arrow', function(e)		
	{
		var container = $($(this).parent('.funnel-wrap').find('.funnel-container'));
		container.stop(true);
	});

	$(document).on('click', '.funnel-bottom-btn', function()	
	{
		$(this).closest('.funnel-card').find('.funnel-btn-group').slideToggle();
	});	

	$('#save, #save-and-new').click(function(e)
	{
		e.preventDefault();

		if($('input[name=add_new]').get(0))
		{
			$('input[name=add_new]').val(0);
			if($(this).attr('name') == 'save_and_new')
			{
				$('input[name=add_new]').val(1);
			}
		}

		var form = $(this).closest('form');
		var formUrl = form.prop('action');
		var formData = form.serialize();

		ajaxValidation(form, formUrl, formData);
	});

	$('.note-editor').on('click', '[data-dismiss="modal"]', function(e)
	{
		e.stopPropagation();
	});

	$('.shortcode a').click(function()
	{
		var textArea = $(this).parent('.shortcode').parent().find('textarea');
		var textEditor = $(this).parent('.shortcode').prev('.note-editor').find('.note-editable');
		var shortcode = $(this).attr('shortcode');

		if(textEditor.text())
		{
			textEditor.append('<br>' + shortcode);
		}
		else
		{
			if(textEditor.html() == '<br>')
			{				
				textEditor.html(shortcode);			
			}
			else
			{
				textEditor.append(shortcode);
			}			
		}	

		textArea.val(textEditor.html());			
	});

	$('.parentfield').change(function()
	{
		var form = $(this).closest('form');
		var childInputs = form.find('input, select').not(this);
		var validChildsChecker = $('option:selected', this).attr('childfield').split('.');

		childInputs.each(function(index, obj)
		{
			var childParent = $(obj).attr('parent');

			if (typeof childParent !== 'undefined' && childParent !== false)
			{
				if($.inArray(childParent, validChildsChecker) !== -1)
				{
					$(obj).closest('.form-group').show();
				}
				else
				{
					$(obj).closest('.form-group').hide();
				}
			}
		});

		$('html').getNiceScroll().resize();
	});

	$('.account').change(function()
	{
		var form = $($(this).closest('form'));
		var div = form.find('div.amount');	
		var currencyList = div.find('.currency-list');
			
		if(typeof currencyList !== 'undefined')
		{	
			var icon = div.attr('icon');
			var alterIcon = div.attr('alter-icon');
			var baseId = div.attr('base-id');
			var tagIcon = div.find('i');			
			var currencyId = div.find("input[name='currency_id']");		
			var accountId = $(this).val();

			if(accountId == '' && tagIcon.attr('event') != 'edit')
			{
				resetCurrency(currencyList, currencyId, baseId, tagIcon, icon, alterIcon);
			}
			else
			{
				$.ajax(
				{
					type  	: 'GET',
					url		: globalVar.baseAdminUrl + '/account-single-data',
					data	: {'id' : accountId},
					dataType: 'JSON',
					success	: function(data)
							  {
							  	if(data.status == true)
							  	{
							  		if(tagIcon.attr('event') != 'edit')
							  		{
							  			resetCurrency(currencyList, currencyId, data.currency, tagIcon, data.currencyIcon, data.currencySymbol);
							  		}
							  		
							  		tagIcon.removeAttr('event');
							  	}
							  	else
							  	{
							  		resetCurrency(currencyList, currencyId, baseId, tagIcon, icon, alterIcon);
							  	}
							  }
				});				
			}
		}
	});

	$(document).on('click', '.currency-list:not(.global) li', function()	
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

	$(document).on('click', '.currency-list.global li', function()
	{
		var form = $($(this).closest('form'));
		var div = form.find('div.amount');		
		var icon = $(this).attr('icon');
		var alterIcon = $(this).attr('symbol');
		var currencyIdVal = $(this).attr('value');
		var tagIcon = div.find('i');
		var currencyList = div.find('.currency-list');
		var currencyId = div.find("input[name='currency_id']");		

		resetCurrency(currencyList, currencyId, currencyIdVal, tagIcon, icon, alterIcon);
	});

	$(document).on('click', '.amount i', function(e)
	{
		$(this).parent('.amount').find('.perfectscroll').animate({ scrollTop: 0 });
	});

	$(document).on('click', '.password-generator', function(e)
	{
		var password = $.passGen({'length' : 10, 'numeric' : true, 'lowercase' : true, 'uppercase' : true, 'special' : false });
		$(this).parent().find('input.password').val(password);
	});

	$(document).on('click', '.show-password', function(e)	
	{
		var input = $(this).parent().find('input.password');
		var inputType = input.prop('type');
		if(inputType == 'password')
		{
			input.prop('type', 'text');
		}
		else
		{
			input.prop('type', 'password');
		}
	});

	$('.para-type-f').click(function()
	{
		var input = $(this).parent().find('input');

		if(input.prop('checked') == true)
		{
			input.prop('checked', false);
		}
		else
		{
			input.prop('checked', true);
		}
	});

	$(document).on('click', 'p .more', function(e)
	{
		var para = $($(this).closest('p'));
		var extend = para.find('.extend');

		if(extend.css('display') == 'none')
		{
			extend.show();
			$(this).html("<br>show less");
		}
		else
		{
			extend.hide();
			$(this).html('<span>...</span> more');
		}
	});

	$(document).on('mouseleave', '.timeline-info', function(e)
	{
		$(this).find('.circle').fadeOut(1500);
	});

	$(document).on('mouseleave', '.timeline-details', function(e)
	{
		$($(this).closest('.timeline-info')).find('.circle').fadeOut(1500);
	});

	$(document).on('focusin', '.comment-form textarea', function(e)
	{
		var form = $($(this).closest('.comment-form'));
		var timelineContainer = $(form.parent());
		$(this).css('height', '60px');
		form.find('.form-group.bottom').slideDown('fast');
		$(timelineContainer.find('.timeline-info')).find('.cancel').trigger('click');
	});

	$(document).on('click', '.comment-form .cancel', function(e)
	{
		e.preventDefault();
		var form = $($(this).closest('.comment-form'));
		resetCommentForm(form, false);
	});

	$(document).on('click', '.save-comment', function(e)
	{
		e.preventDefault();
		var saveBtn = $(this);
		var form = $($(this).closest('.comment-form'));
		var timeline = $(form.parent().find('.timeline'));
		var postUrl = form.data('posturl');
		var comment = $(form.find('textarea'));

		if(comment.val() != null && comment.val() != '')
		{
			var data = form.find('textarea, input').serialize();
			
			$.ajax({
				type 		: 'POST',
				url 		: postUrl,
				data 		: data,
				dataType 	: 'JSON',
				beforeSend	: function(xhr, opts)
				              {		
								saveBtn.attr('disabled', true);
				    		  },
				success		: function(data, textStatus, jqXHR)
							  {
							  	if(data.status == true)
							  	{
							  		if(typeof data.html != 'undefined')
							  		{
							  			timeline.find('.timeline-info').removeClass('top');
							  			timeline.find('.end-down:gt(0)').hide();
							  			$(data.html).hide().prependTo('.timeline').fadeIn(1350);
							  			$('[data-toggle="tooltip"]').tooltip();
							  			resetCommentForm(form);
							  			$('html').getNiceScroll().resize();
							  		}
							  	}
							  	else
							  	{
							  		notifyErrors(data.errors);
							  	}

							  	saveBtn.attr('disabled', false);
							  },
				error 		: function(jqXHR, textStatus, errorThrown)
						  	  {
						  	  	var errorMsg = jqXHR.status ? jqXHR.status + " " + errorThrown : 'Internal Server Error';
						  	  	$.notify({ message: errorMsg }, globalVar.dangerNotify);
						  	  	saveBtn.attr('disabled', false);
						  	  }	  			  
			});
		}
		else
		{
			comment.focus();
		}
	});

	$(document).on('click', '.update-comment', function(e)
	{
		e.preventDefault();
		var saveBtn = $(this);
		var form = $($(this).closest('.timeline-form'));
		var postUrl = form.data('posturl');
		var comment = $(form.find('textarea'));
		var timelineInfo = $($(this).closest('.timeline-info'));

		if(timelineInfo.hasClass('pin'))
		{
			var timeline = $(this).closest('.timeline-pin').next('.timeline');
		}
		else
		{
			var timeline = $($(this).closest('.timeline'));
		}

		if(comment.val() != null && comment.val() != '')
		{
			var data = form.find('textarea, input').serialize();
			
			$.ajax({
				type 		: 'POST',
				url 		: postUrl,
				data 		: data,
				dataType 	: 'JSON',
				beforeSend	: function(xhr, opts)
				              {		
								saveBtn.attr('disabled', true);
				    		  },
				success 	: function(data)
							  {
							  	if(data.status == true)
							  	{
							  		if(typeof data.html != 'undefined')
							  		{
							  			if(data.location == 0)
							  			{
							  				$(timeline.prev('.timeline-pin')).html(data.html);
							  			}		 
							  			else
							  			{
							  				timeline.find(".timeline-info[data-id='"+data.location+"']").replaceWith(data.html);
							  			}

							  			timeline.find('.timeline-info:first-child').addClass('top');
							  			timeline.find('.timeline-info:not(:first-child)').removeClass('top');
							  			timeline.find('.end-down:gt(0)').hide();

							  			$('[data-toggle="tooltip"]').tooltip();
							  			$('html').getNiceScroll().resize();
							  		}
							  	}
							  	else
							  	{
							  		notifyErrors(data.errors);
							  	}

							  	saveBtn.attr('disabled', false);
							  },
				error 		: function(jqXHR, textStatus, errorThrown)
						  	  {
						  	  	var errorMsg = jqXHR.status ? jqXHR.status + " " + errorThrown : 'Internal Server Error';
						  	  	$.notify({ message: errorMsg }, globalVar.dangerNotify);
						  	  	saveBtn.attr('disabled', false);
						  	  }	  			  
			});
		}
		else
		{
			comment.focus();
		}
	});

	$(document).on('click', '.edit-dz-remove', function(e)
	{
		var container = $($(this).closest('.dropzone-container'));
		var preview = $($(this).closest('.dz-preview'));
		var filename = preview.find('.dz-filename').data('original');
		container.find("input[value='"+ filename +"']").attr('name', 'removed_files[]');
		preview.remove();
	});	

	$(document).on('click', '.timeline-edit', function(e)
	{
		var thisTimelineInfo = $($(this).closest('.timeline-info'));
		var content = $(this).closest('.timeline-details-content');
		var container = $(this).closest('.timeline-details');
		var timelineContainer = $($(this).closest('.timeline-info')).parent().parent();

		$.ajax({
			type 		: 'GET',
			url 		: $(this).data('url'),
			data 		: { id : $(this).data('id')},
			dataType 	: 'JSON',
			success 	: function(data)
						  {
						  	if(data.status == true)
						  	{
						  		if(typeof data.html != 'undefined' && content.css('display') != 'none')
						  		{
						  			content.hide();
						  			container.append(data.html);
						  			textOverflowTitle('.dz-filename span');
						  			$(timelineContainer.find('.timeline-info').not(thisTimelineInfo)).find('.cancel').trigger('click');
						  			timelineContainer.find('.comment-form .cancel').trigger('click');
						  			atWhoInit();
						  			dropzoneInit();
						  			$('[data-toggle="tooltip"]').tooltip();
						  			$('html').getNiceScroll().resize();						  			
						  		}
						  	}
						  	else
						  	{
						  		$.notify({ message: 'Something went wrong.' }, globalVar.dangerNotify);
						  	}
						  }
		});
	});

	$(document).on('click', '.timeline-form .cancel', function(e)
	{
		e.preventDefault();
		var dropzoneId = $($(this).closest('.timeline-form')).find('.modalfree-dropzone').attr('id');
		globalVar.dropzone[dropzoneId].removeAllFiles(true);
		$($(this).closest('.timeline-details')).find('.timeline-details-content').show();
		$(this).closest('.timeline-form').remove();
	});

	$(document).on('click', '.load-timeline', function(e)
	{
		e.preventDefault();
		var timelineInfo = $($(this).closest('.timeline-info'));

		if(!timelineInfo.hasClass('disable'))
		{
			timelineInfo.addClass('loading');
			timelineInfo.find('.load-icon').show();

			var timeline = $($(this).closest('.timeline'));
			var url = timeline.data('url');
			var relatedType = timeline.data('relatedtype');
			var relatedId = timeline.data('relatedid');
			var latestId = $(timelineInfo.prev('.timeline-info')).data('id');

			$.ajax({
				type 		: 'GET',
				url 		: url,
				data 		: { type : relatedType, typeid : relatedId, latestid : latestId },
				dataType 	: 'JSON',
				success 	: function(data)
							  {
							  	if(data.status == true)
							  	{
							  		if(typeof data.html != 'undefined')
							  		{
							  			timelineInfo.remove();
							  			$(data.html).hide().appendTo('.timeline').fadeIn(1150);
							  			$('[data-toggle="tooltip"]').tooltip();
							  			$('html').getNiceScroll().resize();
							  		}
							  	}
							  	else
							  	{
							  		notifyErrors(data.errors, true);
							  		timelineInfo.removeClass('loading');
							  		timelineInfo.find('.load-icon').hide();
							  	}
							  }
			});
		}
	});

	$(document).on('click', '.pin-btn', function(e)
	{
		var pin = $(this).attr('data-pin');

		if(typeof pin != 'undefined' && (pin == 1 || pin == 0))
		{
			if(pin == 1)
			{
				var timeline = $($(this).closest('.timeline'));
			}
			else
			{
				var timeline = $(this).closest('.timeline-pin').next('.timeline');
			}

			var postUrl = $(this).data('url');

			$.ajax({
				type 		: 'POST',
				url 		: postUrl,
				data 		: { pin : pin },
				dataType 	: 'JSON',
				success 	: function(data)
							  {
							  	if(data.status == true)
							  	{				  			
						  			if(typeof data.pinHtml != 'undefined' && data.pinHtml != null)
						  			{
						  				timeline.prev('.timeline-pin').html(data.pinHtml);
						  				timeline.find(".timeline-info[data-id='"+data.pinLocation+"']").remove();
						  			
						  				if(typeof data.timelineInfoCount != 'undefined' && data.timelineInfoCount == 0)
						  				{
						  					timeline.html('');
						  				}
						  			}

						  			if(typeof data.unpinHtml != 'undefined' && data.unpinHtml != null)
						  			{
						  				if(typeof data.timelineInfoCount != 'undefined' && data.timelineInfoCount <= 1)
						  				{
						  					timeline.html('');
						  				}

						  				if(data.unpinLocation != null)
						  				{
						  					$(timeline.prev('.timeline-pin')).find(".timeline-info[data-id='"+data.unpinLocation+"']").remove();
						  				}		  				
						  				
						  				if(data.prevLocation == 0)
						  				{
						  					timeline.find('.timeline-info').removeClass('top');
						  					timeline.prepend(data.unpinHtml);
						  				}
						  				else
						  				{
						  					timeline.find(".timeline-info[data-id='"+data.prevLocation+"']").after(data.unpinHtml);						  					
						  				}
						  			}

						  			timeline.find('.timeline-info:first-child').addClass('top');
						  			timeline.find('.timeline-info:not(:first-child)').removeClass('top');

						  			$('[data-toggle="tooltip"]').tooltip();
						  			$('html').getNiceScroll().resize();
							  	}
							  	else
							  	{
							  		$.notify({ message: 'Something went wrong.' }, globalVar.dangerNotify);
							  	}
							  }
			});
		}
	});

	$('.radio-factor').click(function()
	{
	    var appearClass = '.factor-' + $(this).find('input').val();

	    $(this).closest('.form-group').find('.factor').hide();
	    $(this).closest('.form-group').find(appearClass).show();
	});

	$(document).on('click', '.status-checkbox', function(e)
	{
		e.preventDefault();
		$(this).tooltip('hide');

		if(!$(this).hasClass('disabled'))
		{
			$.ajax({
				type 	: 'POST',
				url 	: $(this).data('url'),
				dataType: 'JSON',
				success	: function(data)
						  {
						  	if(data.status == true)
						  	{
						  		if(typeof globalVar.jqueryDataTable != 'undefined')
						  		{
						  			globalVar.jqueryDataTable.ajax.reload(null, false);
						  			if(typeof data.saveId != 'undefined' && data.saveId != null)
						  			{
						  				focusSavedRow(globalVar.jqueryDataTable, data.saveId);
						  			}		                    		
						  		}
						  	}
						  	else
						  	{
						  		$.notify({ message: 'Something went wrong.' }, globalVar.dangerNotify);
						  	}
						  }	  
			});
		}
	});	

	$('main').on('keypress', '.numeric', function(event)
	{
		var thisField = $(this);
		var charCode = event.which;
		var input = String.fromCharCode(event.which);
		var outcome = keypressNumberFilter(thisField, charCode, input)

		if(outcome == false)
		{
			event.preventDefault();
		}
	});

	$('main').on('keypress', '.positive-integer', function(event)
	{
		var thisField = $(this);
		var charCode = event.which;
		var input = String.fromCharCode(event.which);
		var outcome = keypressPositiveIntegerFilter(thisField, charCode, input)

		if(outcome == false)
		{
			event.preventDefault();
		}
	});

	$('main').on('focusout', '.numeric', function()
	{
		var field = $(this);
		var value = $(this).val();
		dontLeftNumberAwkward(field, value);
	});

	$('.numeric').keydown(function(event)
	{
		var thisField = $(this);
		var charCode = event.which;

		keydownIncrementDecrement(thisField, charCode);			
	});	

	$('.modal table').on('click.dt', '.close', function(event)
	{
		var tr = $(this).closest('tr');
		var tbody = tr.parent('tbody');
		var serial = tbody.attr('data-serial');

		tr.remove();
		if(typeof serial !== 'undefined')
		{
			tbody.find('tr').each(function(index, obj)
			{
				$(obj).find('td:first-child').html(index+1);
			});
		}		
	});

	$(document).on('mouseleave', 'tr.saved', function(event)
	{
		$(this).removeClass('saved');
	});	

	$(document).on('click.dt', '#datatable .dropdown-toggle', function(event)
	{
		var dropdownHeight = $(this).next('.dropdown-menu').height() + 2;
		var tr = $(this).closest('tr');
		var bottomHeight = 0;
		var topHeight = 0;

		tr.nextAll('tr').each(function(index, value)
		{
			bottomHeight += $(value).height();
		}); 

		tr.prevAll('tr').each(function(index, value)
		{
			topHeight += $(value).height();
		}); 

		if((bottomHeight + 150) <= dropdownHeight)
		{
			$('html').getNiceScroll().resize();

			if((topHeight + 100) >= (dropdownHeight))
			{
				$(this).parent('.dropdown').addClass('dropup');
			}
		}
	});

	$(document).on('mouseenter', 'nav.compress ul li', function()
	{
		var parentArray = $(this).parents().map(function()
											{
												return this.tagName;
											}).get();

		var navIndex = jQuery.inArray('NAV', parentArray);
		var navArray = parentArray.splice(0, navIndex);

		if(navArray.length == 1 && navArray[0] == 'UL')
		{
			var logoWidth = Math.floor($('#logo').width());
			$(this).find('i.fa').first().css('width', logoWidth + 'px');
		}
	});

	$(document).on('mouseleave', 'nav.compress ul li', function()
	{
		var parentArray = $(this).parents().map(function()
											{
												return this.tagName;
											}).get();

		var navIndex = jQuery.inArray('NAV', parentArray);
		var navArray = parentArray.splice(0, navIndex);

		if(navArray.length == 1 && navArray[0] == 'UL')
		{
			$(this).find('.collapse').css('display', 'none');
			$(this).find('span.fa-angle-left').removeClass('down');
			$(this).find('.tree').removeClass('active');
			$(this).find('i.fa').first().removeAttr('style');
		}
	});

	$(document).on('change', '.foot-bold-stat .value', function()
	{
		$(this).parent().find('.stat').html($(this).val());

		var containerWidth = $(this).parent().width();
		var statTitleWidth = $(this).parent().find('.stat-title').width();
		var permittedWidth = containerWidth - statTitleWidth;
		var statBoxWidth = $(this).parent().find('.stat-box').width();

		if(statBoxWidth > permittedWidth)
		{
			$(this).parent().find('.stat-box').removeClass('right-justify');
			$(this).parent().find('.stat-box').addClass('left-justify');
		}
		else
		{
			$(this).parent().find('.stat-box').removeClass('left-justify');
			$(this).parent().find('.stat-box').addClass('right-justify');
		}		
	});

	$(document).on('change', '*[data-option-related]', function(e)
	{
		var thisVal = $(this).val();
		var option = $(this).find("option[value='"+ thisVal +"']");
		var form = $(this).closest('form');
		var related = $(this).data('option-related');
		var relatedField = form.find("*[name='"+ related +"']");
		var relatedHidden = relatedField.next("input[type='hidden']");

		if(typeof option.attr('relatedval') != 'undefined')
		{
			var newVal = option.attr('relatedval');
			if(typeof relatedHidden != 'undefined' && relatedHidden.length > 0 && relatedHidden.val() != 0)
			{
				newVal = relatedHidden.val();
				relatedHidden.val(0);
			}

			relatedField.val(newVal).trigger('change');

			if(typeof option.attr('freeze') != 'undefined' && option.attr('freeze') == 'true')
			{
				if(relatedField.is('select'))
				{
					relatedField.attr('disabled', true);
				}
				else
				{
					relatedField.attr('readonly', true);
				}
			}
			else
			{
				if(relatedField.is('select'))
				{
					relatedField.attr('disabled', false);
				}
				else
				{
					relatedField.attr('readonly', false);
				}	
			}
		}
		else
		{
			if(relatedField.is('select'))
			{
				relatedField.attr('disabled', false);
			}
			else
			{
				relatedField.attr('readonly', false);
				relatedField.val('');
			}			
		}
	});

	$(document).on('change', 'select[data-child-option]', function()
	{
		var form = $(this).closest('form');
		var child = $(this).data('child-option');
		var childSelect = form.find("select[name='"+ child +"']");
		var dataUrl = $(this).data('url') + '/' + $(this).val();

		$.ajax(
		{
			type 	: 'GET',
			url 	: dataUrl,
			dataType: 'JSON',
			success	: function(data)
					  {
					  	if(data.status == true)
					  	{
					  		childSelect.html(data.optionHtml);
					  		var hiddenInput = childSelect.closest('.form-group').find('input');
					  		var newVal = hiddenInput.val() == 0 ? data.topval : hiddenInput.val();
				  			childSelect.val(newVal);
				  			childSelect.closest('.form-group').find('.select2-hidden-accessible').trigger('change');
					  		hiddenInput.val(0);
					  	}
					  }
		});
	});

	$(document).on('click', '.btn-remove', function()
	{
		var tr = $(this).closest('tr');

		$.ajax(
		{
			type 	: 'POST',
			url     : $(this).attr('action'),
			data    : { remove : true },
			dataType: 'JSON',
			success : function(data)
			          {
			          	if(data.status == true)
			          	{			          		
			          		tr.remove();

			          		if(typeof globalVar.jqueryDataTable != 'undefined')
			          		{
			          		    globalVar.jqueryDataTable.ajax.reload(null, false);
			          		}

			          		$.each(data.realtime, function(index, value)
			          		{
			          			$("span[realtime='"+index+"']").html(value);
			          			$("input[realtime='"+index+"']").val(value).trigger('change');
			          		}); 
			          	}
			          }
		})
	});

	$(document).on('click', '.edit', function(event)
	{
		var id = $(this).attr('editid');
		var data = {'id' : id};
		var tr = $($(this).closest('.has-currency-info'));

		if(typeof $(this).data('url') != 'undefined')
		{
			var url = $(this).data('url') + '/' + id + '/edit';
			var updateUrl = $(this).data('url') + '/' + id;		

			getEditData(id, data, url, updateUrl);
			modalCurrency(tr, '#edit-form');
		}
	});

	$(document).on('click', '.editable .edit-btn', function()
	{
		$(this).parent('.editable').hide();
		$(this).parent('.editable').next('.edit-field').show();
	});

	$(document).on('click', '.edit-field .save-btn', function()
	{
		var container = $(this).parent('.edit-field');
		var formData = container.find('input').serialize();
		
		displayTableFieldUpdate(container, formData);
	});

	$(document).on('keydown', '.edit-field input', function(e)
	{
		if(e.keyCode == 13 && e.shiftKey == false)
		{
			var container = $(this).parent('.edit-field');
			var formData = container.find('input').serialize();
			
			displayTableFieldUpdate(container, formData);
		}	
	});

	$(document).on('click', '.edit-field .cancel-btn', function()
	{
		if(typeof globalVar.jqueryDataTable != 'undefined')
		{
		    globalVar.jqueryDataTable.ajax.reload(null, false);
		}

		$(this).parent('.edit-field').hide();
		$(this).parent('.edit-field').prev('.editable').show();
	});

	$(document).on('click', '.modal .paginate_button', function()
	{
		if(!$(this).hasClass('current') && !$(this).hasClass('disabled') && typeof $(this).closest('.modal-body').data('paginate-top') != 'undefined')
		{
			$(this).closest('.modal-body').animate({ scrollTop: 0 });
		}
	});

	$(document).on('click', '.dropzone-attach', function()	
	{
		$($(this).closest('.form-group')).find('.modalfree-dropzone').trigger('click');
	});	

	$(document).on('click', '.browse', function(e)
	{
	    e.preventDefault();
	    var uploadzone = $($(this).closest('.uploadzone'));
	    uploadzone.find("input[type='file']").click();
	}); 

	$(document).on('change', ".uploadzone input[type='file']", function(e)
	{
	    var modal = $($(this).closest('.modal'));
	    var inputName = $(this).attr('name');
	    var uploadzone = $($(this).closest('.uploadzone'));
	    var fromGroup = $(uploadzone.closest('.form-group'));
	    var cropper = fromGroup.find('.cropper');

	    if($(this).val() != '')
	    {
	    	var file = this.files[0];
	    	var validExtension = extensionValidation(file, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
	    	var validSize = filesizeValidation(file, 3000000)

	    	fromGroup.find(".validation-error[field='"+ inputName +"']").text('');

	    	if(!validExtension)
	    	{
	    		fromGroup.find(".validation-error[field='"+ inputName +"']").append('The '+ inputName +' is invalid. ');
	    	}

	    	if(!validSize && validExtension)
	    	{
	    		fromGroup.find(".validation-error[field='"+ inputName +"']").append('The '+ inputName +' may not be greater than 3MB.');
	    	}

	    	if(validExtension && validSize)
	    	{
	    		fromGroup.find(".validation-error[field='"+ inputName +"']").text('');
	    		previewImg(this, cropper);
	    		uploadzone.hide();
	    		cropperInit(cropper);
	    		fromGroup.find('.cropper-wrap').fadeIn(500);
	    		modal.find('.modal-footer').fadeIn(500);
	    	}
	    }
	});

	// Plugins Initialize
	$('html').niceScroll({
	    cursorcolor: 'rgba(0, 0, 0, 0.5)',
	    cursoropacitymin: 0,
	    cursoropacitymax: 1,
	    zindex: 999,
	    cursorwidth: '5px',
	    cursorborder: '0px solid rgba(0, 0, 0, 0.25)',
	    cursorborderradius: '0px',
	    scrollspeed: 60,
	    mousescrollstep: 40,
	    hwacceleration: true,
	    grabcursorenabled: true,
	    autohidemode: true,   
	    background: 'rgba(0, 0, 0, 0.15)',
	    smoothscroll: true,
	    enablekeyboard: false,
	    enablemousewheel: true,
	    sensitiverail: true,
	    hidecursordelay: 500,
	    spacebarenabled: true,
	    railpadding: { top: 0, right: 0, left: 0, bottom: 0 }
	});

	if($('nav').get(0))
	{
		$('nav').niceScroll({
		    cursorcolor: 'rgba(0, 0, 0, 0.25)',
		    cursoropacitymin: 0,
		    cursoropacitymax: 0,
		    zindex: 999,
		    cursorwidth: '0px',
		    cursorborder: '0px solid rgba(0, 0, 0, 0.25)',
		    cursorborderradius: '10px',
		    scrollspeed: 60,
		    mousescrollstep: 40,
		    hwacceleration: true,
		    grabcursorenabled: true,
		    autohidemode: true,   
		    background: 'rgba(0, 0, 0, 0.15)',
		    smoothscroll: true,
		    enablekeyboard: true,
		    enablemousewheel: true,
		    sensitiverail: true,
		    hidecursordelay: 500,
		    spacebarenabled: true
		});
	}	

	if($('.perfectscroll').get(0))
	{
		$('.perfectscroll').each(function(index)
		{
			var ps = new PerfectScrollbar($('.perfectscroll').get(index));
		});
	}

	if($('.scroll-dropdown').get(0))
	{
		$('.scroll-dropdown').each(function(index)
		{
			var psDropdown = new PerfectScrollbar($('.scroll-dropdown').get(index));
		});
	}

	if($('.scroll-box-x').get(0))
	{
		$('.scroll-box-x').each(function(index)
		{
			var psXBox = new PerfectScrollbar($('.scroll-box-x').get(index));
		});
	}

	if($('.scroll-box').get(0))
	{
		$('.scroll-box').each(function(index)
		{
			var psBox = new PerfectScrollbar($('.scroll-box').get(index), {minScrollbarLength: 50});
		});
	}

	sortableInit();

	if($('.modal').get(0))
	{
		var modalPs = new PerfectScrollbar('.modal');
	}

	if($('.datepicker').get(0))
	{
		$('.datepicker').not('.only-view').datepicker({
		    format: 'yyyy-mm-dd'
		});
	}

	if($('.datetimepicker').get(0))
	{
		$('.datetimepicker').datetimepicker({			
		    format: 'Y-m-d h:i A',
		    formatTime: 'h:i A',
		    validateOnBlur: false
		});
	}

	if($('.editor').get(0))
	{
		$('.editor').summernote({
			toolbar: [
			    ['style', ['style']],
			    ['font', ['bold', 'underline', 'clear']],
			    ['fontname', ['fontname']],
			    ['color', ['color']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['view', ['help']]
			]
		});
	}

	if($('.plain-editor').get(0))
	{
		$('.plain-editor').summernote({
			toolbar: []
		});
	}

	$(document).on('click', '.select2-container', function(e)
	{
		setTimeout(function()
		{
			destroySelect2PerfectScroll();
			initOpenSelect2PerfectScroll();
		}, 100);
	});

	$(document).on('keyup', '.white-container.tags .select2-search__field', function(e)
	{
		var li = $(".select2-results__options li:contains('" + $(this).val() + "')");
		var charCode = e.which;

		if(charCode == 8)
		{
			$(this).val('');
			$(".select2-results__options li").remove();
		}
		else
		{
			if(li.get(0))
			{
				var liObj = $($(".select2-results__options li:contains('" + $(this).val() + "')").get(0));
				$(".select2-results__options li").not(liObj).removeClass('select2-results__option--highlighted');
				$(".select2-results__options li").not(liObj).remove();
				li.addClass('select2-results__option--highlighted');		
			}
		}
	});

	$('.modal .cancel, .modal .close').click(function()
	{
	    cleanDropzoneTempFiles($(this));
	});

	$(document).on('click', ".download[data-valid='0'], .filelink[data-valid='0']", function(e)
	{
		e.preventDefault();
		$.notify({ message: 'The file was not found.' }, globalVar.dangerNotify);
	});

	if($('.select-type-single').get(0))
	{
		$('.select-type-single').select2().on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.select-type-single-b').get(0))
	{
		$('.select-type-single-b').select2({
			'minimumResultsForSearch' : -1
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.select-type-multiple').get(0))
	{
		$('.select-type-multiple').select2({
			allowClear: true
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.breadcrumb-select').get(0))
	{
		$('.breadcrumb-select').select2({
			containerCssClass: 'breadcrumb-select-container', 
			dropdownCssClass: 'breadcrumb-select-dropdown',
			selectOnClose: true
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.white-select-type-single').get(0))
	{
		$('.white-select-type-single').select2({
			containerCssClass: 'white-container', 
			dropdownCssClass: 'white-dropdown',
			selectOnClose: true
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.white-select-single-clear').get(0))
	{
		$('.white-select-single-clear').select2({
			containerCssClass: 'white-container', 
			dropdownCssClass: 'white-dropdown',
			allowClear: true,
			placeholder: function()
			{
			    $(this).data('placeholder');
			}
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.white-select-type-single-b').get(0))
	{
		$('.white-select-type-single-b').select2({
			minimumResultsForSearch : -1,
			containerCssClass: 'white-container',
			dropdownCssClass: 'white-dropdown'
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.white-select-type-multiple').get(0))
	{
		$('.white-select-type-multiple').select2({
			containerCssClass: 'white-container', 
			dropdownCssClass: 'white-dropdown',
			allowClear: true,
			placeholder: function()
			{
			    $(this).data('placeholder');
			}
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.white-select-type-multiple-tags').get(0))
	{
		$('.white-select-type-multiple-tags').select2({
			containerCssClass: 'white-container tags', 
			dropdownCssClass: 'white-dropdown tags',
			tags: true,
			placeholder: function()
			{
			    $(this).data('placeholder');
			},
			language:
			{
			    noResults: function(params)
			    {
			    	return 'Type to search';
			    }
			}
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.d3-funnel').get(0))
	{
		$('.d3-funnel').each(function(index, ui)
		{
			var funnelDataArray = [];
			var pinched = $(ui).data('pinched');
			var funnelJsonData = $(ui).data('funnel');
			var funnelJsonArray = funnelJsonData.split('},');

			if(funnelJsonArray.length)
			{
				$(funnelJsonArray).each(function(key, jsonString)
				{
					if(jsonString != '' && jsonString != null)
					{
						var jsonFormat = jsonString + '}';
						var obj = jQuery.parseJSON(jsonFormat);
						funnelDataArray.push(obj);
					}					
				});
			}

			if(funnelDataArray.length)
			{
				initD3Funnel('#' + $(ui).attr('id'), funnelDataArray, pinched);
			}
		});
	}

	if($('.chart-js-pie').get(0))
	{
		$('.chart-js-pie').each(function(index, ui)
		{
			var pieDataArray = [];
			var pieDataStr = $(ui).data('pie').split(',');

			if(pieDataStr.length)
			{
				$(pieDataStr).each(function(key, value)
				{
					if(value != '' && value != null)
					{
						pieDataArray.push(value);
					}					
				});
			}

			var pieLabelArray = [];
			var pieLabelStr = $(ui).data('label').split(',');

			if(pieLabelStr.length)
			{
				$(pieLabelStr).each(function(key, label)
				{
					if(label != '' && label != null)
					{
						pieLabelArray.push(label);
					}					
				});
			}

			var pieBackgroundArray = [];
			var pieBackgroundStr = $(ui).data('background').split('),');

			if(pieBackgroundStr.length)
			{
				$(pieBackgroundStr).each(function(key, rgba)
				{
					if(rgba != '' && rgba != null)
					{
						var rgbaFormat = rgba.slice(-1) != ')' ? rgba + ')' : rgba;
						pieBackgroundArray.push(rgbaFormat);
					}					
				});
			}

			if(pieDataArray.length)
			{
				initChartJsPie('#' + $(ui).attr('id'), pieLabelArray, pieDataArray, pieBackgroundArray);
			}
		});
	}		

	if($('.chart-js-timeline').get(0))
	{
		$('.chart-js-timeline').each(function(index, ui)
		{
			var labelY = [];
			var dataY = [];
			var canvasId = '#' + $(ui).attr('id');
			var labelStrY = $(ui).data('labels-y').split(',');

			if(labelStrY.length)
			{
				$(labelStrY).each(function(key, label)
				{
					if(label != '' && label != null)
					{
						labelY.push(label);

						var nth = key + 1;
						var data = [];
						var dataStr = $(ui).data('y' + nth).split(',');

						if(dataStr.length)
						{
							$(dataStr).each(function(key, val)
							{
								if(val != '' && val != null)
								{
									data.push(val);
								}					
							});
						}

						dataY.push(data);
					}					
				});
			}

			var backgroundY = [];
			var backgroundStrY = $(ui).data('backgrounds-y').split('),');

			if(backgroundStrY.length)
			{
				$(backgroundStrY).each(function(key, rgba)
				{
					if(rgba != '' && rgba != null)
					{
						var rgbaFormat = rgba.slice(-1) != ')' ? rgba + ')' : rgba;
						backgroundY.push(rgbaFormat);
					}					
				});
			}

			var borderY = [];
			var borderStrY = $(ui).data('borders-y').split('),');

			if(borderStrY.length)
			{
				$(borderStrY).each(function(key, rgba)
				{
					if(rgba != '' && rgba != null)
					{
						var rgbaFormat = rgba.slice(-1) != ')' ? rgba + ')' : rgba;
						borderY.push(rgbaFormat);
					}					
				});
			}

			var days = [];
			var daysStr = $(ui).data('days').split(',');

			if(daysStr.length)
			{
				$(daysStr).each(function(key, day)
				{
					if(day != '' && day != null)
					{
						days.push(day);
					}					
				});
			}

			var years = [];
			var yearsStr = $(ui).data('years').split(',');

			if(yearsStr.length)
			{
				$(yearsStr).each(function(key, year)
				{
					if(year != '' && year != null)
					{
						years.push(year);
					}					
				});
			}

			initChartJsTimeline(canvasId, days, years, labelY, dataY, backgroundY, borderY);
		});
	}		

	if($('.slider-range').get(0))
	{
		var sliderRangeStart = $('.slider-range').data('start');
		var sliderRangeEnd = $('.slider-range').data('end');

		initSliderRange(sliderRangeStart, sliderRangeEnd);
	}

	atWhoInit();
	calendarInit();
	dropzoneInit();
	orgChartInit();

	$('#fullscreen').on('click', function()
	{
		if(screenfull.enabled)
		{
			screenfull.toggle();
			$(this).children('i').toggleClass('fa-compress');
		}
	});

	responsiveMediaQueryOnLoad();
});

function modalBodyScroll(modalBody)
{
	var modalImage = $($(modalBody).closest('form')).find('.modal-image');

	if($(modalBody).scrollTop() > 15)
	{
		modalImage.addClass('small');	
	}
	else
	{
		modalImage.removeClass('small');	
	}
}

function heightAdjustment()
{
	if($(window).height() > 700)
	{
		var fullHeight = $(window).height() - 210;
		$('.funnel-card-container').css('height', fullHeight + 'px');
		$('.full-height').css('height', fullHeight + 'px');
	}
	else
	{
		$('.funnel-card-container').css('height', '450px');
	}
}

function kanbanLeftRight(container)
{
	var containerArrowLeft = $(container.parent('.funnel-wrap').find('.funnel-container-arrow.left'));
	var containerArrowRight = $(container.parent('.funnel-wrap').find('.funnel-container-arrow.right'));
	var containerWidth = container.innerWidth();
	var containerLeftPos = container.scrollLeft();
	var containerRightPos = containerLeftPos + containerWidth;
	var totalStages = container.children('.funnel-stage').size();
	var totalStagesWidth = totalStages * 300;
	var containerMaxRightPos = totalStagesWidth > containerWidth ? totalStagesWidth : containerWidth;

	if(containerLeftPos < 3)
	{
		containerArrowLeft.css('left', '-70px');
	}
	else
	{
		containerArrowLeft.css('left', '-35px');
	}

	if(containerRightPos < (containerMaxRightPos - 3))
	{
		containerArrowRight.css('right', '-35px');
	}
	else
	{
		containerArrowRight.css('right', '-70px');
	}
}

function kanbanDragMove(ui, funnelStage)
{
	var container = $(ui['item'].closest('.funnel-container'));
	var containerLeftPos = container.scrollLeft();
	var containerRightPos = containerLeftPos + container.innerWidth();
	var totalStage = container.children('.funnel-stage').size();
	var prevStage = funnelStage.prevAll('.funnel-stage').size();
	var funnelStageLeftPos = prevStage * 300;
	var funnelStageRightPos = funnelStageLeftPos + 300;

	if(containerLeftPos > funnelStageLeftPos)
	{
		var goLeftVal = nonNegative(funnelStageLeftPos - 100);
		container.animate({scrollLeft : goLeftVal}, 1000);
	}
	else if(containerRightPos < funnelStageRightPos)
	{
		if(totalStage == (prevStage + 1))
		{
			var goRightVal = nonNegative(funnelStageRightPos - container.innerWidth());
			container.animate({scrollLeft : goRightVal}, 1000);
		}
		else
		{
			var goRightVal = containerLeftPos + 300;
			container.animate({scrollLeft : goRightVal}, 1000);
		}					
	}
}

function kanbanUpdate(ui)
{
	var card = $(ui['item']['context']);
	var cardId = card.find('input').val();
	var prevLi = card.prev('li');
	var prevLiSize = card.prev('li').size();
	var pickedCardId = prevLiSize ? prevLi.find('input').val() : 0;

	var container = $(ui['item'].closest('.funnel-container'));
	var source = container.data('source');
	var field = container.data('stage');
	var orderType = container.data('order');
	var stage = card.find('input').attr('data-stage');

	$.ajax(
	{
	    type    : 'GET',
	    url     : globalVar.baseAdminUrl + '/kanban-reorder',
	    data    : { source : source, id : cardId, picked : pickedCardId, field : field, stage : stage, ordertype : orderType },
	    dataType: 'JSON',
	    success : function(data)
	              {
	              	if(data.status == true)
	              	{
	              		kanbanCountResponse(data);

	              		$.each(data.realtime, function(index, value)
	              		{
	              		    $("*[data-realtime='"+index+"']").html(value);
	              		});
	              	}
	              	else
	              	{
	              		notifyErrors(data.errors, true);
	              	}
	              },
	    error 	: function(jqXHR, textStatus, errorThrown)
	    		  {
	    		  	location.reload();
	    		  }	  
	});
}

function kanbanUpdateResponse(data)
{
	$.each(data.kanban, function(stage, cards)
	{
		$.each(cards, function(cardId, card)
		{
			var cardExists = $(".funnel-stage[id='"+stage+"']").find('#' + cardId);
			
			if(cardExists.length == 0)
			{
				$('.funnel-stage #' + cardId).remove();
				$(".funnel-stage[id='"+stage+"'] .kanban-list").prepend(card);
			}
			else
			{
				cardExists.html(card);
			}    		                    			
		});
	});

	kanbanCountResponse(data);

	$('[data-toggle="tooltip"]').tooltip();
}

function kanbanCountResponse(data)
{
	$.each(data.kanbanCount, function(index, value)
	{
		$(".funnel-stage[id='"+index+"']").find('.funnel-stage-header .title .count').html(value);
	});

	if(typeof data.kanbanHeader != 'undefined' && data.kanbanHeader != null)
	{
		$.each(data.kanbanHeader.subinfo, function(index, value)
		{
			$(".funnel-stage[id='"+index+"']").find('.funnel-stage-header .title .sub-info').html(value);
		});

		$.each(data.kanbanHeader.tooltip, function(index, value)
		{
			$(".funnel-stage[id='"+index+"']").find(".funnel-stage-header [data-toggle='tooltip']").attr('data-original-title', value);
		});

		$("[data-toggle='tooltip']").tooltip();
	}
}

function ajaxKanbanCard(cardContainer)
{
    var scrollTop = cardContainer.scrollTop();
    var funnelStage = cardContainer.closest('.funnel-stage');
    var loadStatus = funnelStage.attr('data-load');
    var loadUrl = funnelStage.data('url');
    var reqData = kanbanCardReqData(cardContainer);

    if(loadStatus == 'true')
    {
        cardContainer.closest('.funnel-stage').addClass('loading');
        funnelStage.find('.kanban-list').css('height', (funnelStage.find('.kanban-list .li-container').height() + 50) + 'px');

        $.ajax(
        {
            type        : 'POST',
            url         : loadUrl,
            data        : reqData,
            dataType    : 'JSON',
            success     : function(data)
                          {
                            funnelStage.removeClass('loading');

                            if(data.status == true)
                            {
                            	$(data.html).each(function(index, card)
                            	{
                            		if(!funnelStage.find('li#' + $(card).attr('id')).size())
                            		{
                            			$(card).hide().appendTo('#' + funnelStage.find('.kanban-list .li-container').attr('id')).fadeIn(550);
                            		}
                            	});

                                $('[data-toggle="tooltip"]').tooltip();
                                funnelStage.find('.kanban-list').each(function(index, ui)
                                {
                                	if($(ui).hasClass('ui-sortable'))
                                	{
                                		$(ui).sortable('refresh');
                                	}                                	
                                });	
                                
                                if(!data.loadStatus)
                                {
                                    funnelStage.attr('data-load', 'false');
                                }
                            }
                            else
                            {
                                if(!$(".alert.alert-danger[role='alert']").get(0))
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
                                
                                cardContainer.animate({ scrollTop : scrollTop });   
                            }

                            funnelStage.find('.kanban-list').css('height', funnelStage.find('.kanban-list .li-container').height() + 'px');
                          }
        });
    }
    else
    {
    	funnelStage.find('.kanban-list').css('height', funnelStage.find('.kanban-list .li-container').height() + 'px');
    }  
}

function kanbanCardReqData(cardContainer)
{
	var type = cardContainer.data('card-type');
	var reqData = {};
	reqData.stageId = cardContainer.closest('.funnel-stage').data('stage');

	if(type == 'deal')
	{
		reqData.pipelineId = cardContainer.closest('.funnel-stage').data('pipeline');
	}

	reqData.ids = cardContainer.find("[data-init-stage='"+ reqData.stageId +"'] input[name='positions[]']").map(function()
	                    		{
	                        		return $(this).val();
	                    		}).get();

	return reqData;
}

function responsiveMediaQuery()
{
	if ($('.nav-link.expand').css('display') == 'block')
	{
		if($('nav').hasClass('compress'))
		{
			if($('logo').hasClass('compress') == false)
			{
				$('#logo').addClass('compress');
			}			
		}
	    $('.nicescroll-rails').eq(0).css('width', 5 + 'px');
	    $('.nicescroll-cursors').eq(0).css('width', 5 + 'px');
	}

    if ($('.nav-link.expand').css('display') == 'none')
    {
        $('.nicescroll-rails').css('width', 0 + 'px');
        $('.nicescroll-cursors').css('width', 0 + 'px');
    }
}

function responsiveMediaQueryOnLoad()
{
	if ($('.nav-link.expand').css('display') == 'block')
	{
	    $('.nicescroll-rails').eq(0).css('width', 5 + 'px');
	    $('.nicescroll-cursors').eq(0).css('width', 5 + 'px');
	}

    if ($('.nav-link.expand').css('display') == 'none')
    {
    	$('#logo').removeClass('compress');
    	$('nav').removeClass('compress');
    	$('#top-nav').removeClass('expand');
    	$('main').removeClass('expand');

    	$('#logo').removeAttr('style');

        $('.nicescroll-rails').css('width', 0 + 'px');
        $('.nicescroll-cursors').css('width', 0 + 'px');
    }
}

function delayModalHide(modalId, delayVal)
{
	setTimeout(function(e)
	{
	    $(modalId).modal('hide');
	}, 
	parseInt(delayVal * 1000));
}

function cleanDropzoneTempFiles(modalClose)
{
	var modal = $(modalClose.closest('.modal'));
	var dropzone = modal.find('.dropzone');
	
	if(dropzone.length == 1)
	{
		globalVar.dropzone[dropzone.attr('data-identifier')].removeAllFiles(true);
	}
}

function initSliderRange(start, end)
{
	if(typeof globalVar.sliderRange != 'undefined')
	{
		$('.slider-range').slider('destroy');
	}

	globalVar.sliderRange = $('.slider-range').slider(
							{
								range: true,
								min: 0,
								max: 99,
								values: [start, end],
								slide: function(event, ui)
								{
									if(ui.values[0] < 11)
									{
										return false;
									}

									if(ui.values[1] > 90)
									{
										return false;
									}

									if((ui.values[1] - ui.values[0]) < 10)
									{
										return false;
									}

									setSliderRangeHotArea(ui.values[1]);

									var hotRangeStart = ui.values[1] + 1;
									hotRangeStart = hotRangeStart > 99 ? 99 : hotRangeStart;
									var coldRange = '0-' + nonNegative(ui.values[0] - 1);					
									var warmRange = ui.values[0] + '-' + ui.values[1];
									var hotRange =  hotRangeStart + '-' + '99';									

									$('.cold-range').html(coldRange);
									$('.warm-range').html(warmRange);
									$('.hot-range').html(hotRange);
									$(".slider-range input[name='range_start']").val(ui.values[0]).trigger('change');
									$(".slider-range input[name='range_end']").val(ui.values[1]).trigger('change');
								}
							});

	setSliderRangeHotArea();
}

function initSelect2PerfectScroll()
{
	if($('.select2-results__options').get(0) && typeof $('.select2-results__options .ps__rail-x').get(0) === 'undefined')
	{
		globalVar.psSelect2 = new PerfectScrollbar($('.select2-results__options').get(0), {wheelSpeed: 2, wheelPropagation: true, minScrollbarLength: 50});
	}
}

function initOpenSelect2PerfectScroll()
{
	if($('.select2-results__options').get(0) && typeof $('.select2-results__options .ps__rail-x').get(0) === 'undefined')
	{
		var ul = $($('.select2-results__options').get(0));
		var liSelected = $(ul.find("li[aria-selected='true']").get(0));
		var prevLiSize = liSelected.prevAll('li').size();
		var top = (prevLiSize - 1) * 30.55;

		globalVar.psSelect2 = new PerfectScrollbar($('.select2-results__options').get(0), {wheelSpeed: 2, wheelPropagation: true, minScrollbarLength: 50});
		
		if(prevLiSize > 5 && ul.scrollTop() == 0)
		{
			ul.animate({scrollTop : top});
		}
	}
}

function destroySelect2PerfectScroll()
{
	if(typeof globalVar.psSelect2 !== 'undefined' && globalVar.psSelect2 !== null)
	{
		globalVar.psSelect2.destroy();
		globalVar.psSelect2 = null;
	}
}

function twoDecimalFormat(num)
{
	var outcome = parseFloat(Math.round(num * 100) / 100).toFixed(2);
	return outcome;
}

function keypressNumberFilter(thisField, charCode, input)
{
	if(charCode != 8 && charCode != 0 && charCode != 46)
	{
		if(!(thisField.val() == '' && input == '-'))
		{
			if(!$.isNumeric(input))
			{
				return false;
			}
		}
	}
	else
	{
		if(charCode == 46)
		{
			if(thisField.val().indexOf('.') > -1)
			{
				return false;
			}
		}
	}

	return true;
}

function keypressPositiveNumberFilter(thisField, charCode, input)
{
	if(charCode != 8 && charCode != 0 && charCode != 46)
	{
		if(!$.isNumeric(input))
		{
			return false;
		}
	}
	else
	{
		if(charCode == 46)
		{
			if(thisField.val().indexOf('.') > -1)
			{
				return false;
			}
		}
	}

	return true;
}

function keypressPositiveIntegerFilter(thisField, charCode, input)
{
	if(charCode != 8 && charCode != 0)
	{
		if(!$.isNumeric(input) || input == '-')
		{
			return false;
		}
	}

	return true;
}

function keydownIncrementDecrement(thisField, charCode)
{
	switch(charCode)
	{
		case 38 :
			var plus = twoDecimalFormat(Number(thisField.val()) + 1);
			thisField.val(plus);
		break;

		case 40 :
			var minus = twoDecimalFormat(Number(thisField.val()) - 1);
			thisField.val(minus);
		break;
			
		default : return;
	}
}

function calculateSingleItem(tr, discountType)
{
	// This Item Row - Quantity, Rate, Tax, Discount and Amount
	var quantity = tr.find('.quantity').val();
	var rate = tr.find('.rate').val();
	var tax = tr.find('.tax').val();
	var taxVal = tr.find('.tax-val');
	var discount = tr.find('.discount').val();
	var discountVal = tr.find('.discount-val');
	var amount = tr.find('.amount');
	var amountVal = tr.find('.amount-val');
	var itemTotal = tr.find('.item-total');

	// Calculate plain amount
	var plainAmount = quantity * rate;	

	var taxOnAmount = 0;
	var amountWithTax = 0;

	var discountOnAmount = 0;
	var amountWithDiscount = 0;

	var itemAmountTotal = 0;

	switch(discountType)
	{
		case 'pre' :
			discountOnAmount = plainAmount * (discount/100);
			amountWithDiscount = plainAmount - discountOnAmount;
			taxOnAmount = amountWithDiscount * (tax/100);
			amountWithTax =  amountWithDiscount + taxOnAmount;		
			itemAmountTotal = amountWithTax;
		break;

		case 'post' :
			taxOnAmount = plainAmount * (tax/100);
			amountWithTax =  plainAmount + taxOnAmount;
			discountOnAmount = amountWithTax * (discount/100);
			amountWithDiscount = amountWithTax - discountOnAmount;
			itemAmountTotal = amountWithDiscount;
		break;

		case 'flat' :
			taxOnAmount = plainAmount * (tax/100);
			amountWithTax =  plainAmount + taxOnAmount;
			discountOnAmount = discount;
			amountWithDiscount = amountWithTax - discountOnAmount;
			itemAmountTotal = amountWithDiscount;
		break;

		default : 
			discountOnAmount = plainAmount * (discount/100);
			amountWithDiscount = plainAmount - discountOnAmount;
			taxOnAmount = amountWithDiscount * (tax/100);
			amountWithTax =  amountWithDiscount + taxOnAmount;		
			itemAmountTotal = amountWithTax;
	}

	// Update This Item Tax, Discount and Amount
	taxVal.val(taxOnAmount);
	discountVal.val(discountOnAmount);
	amount.html(twoDecimalFormat(itemAmountTotal));
	amountVal.val(itemAmountTotal);
	itemTotal.val(plainAmount);
}

function itemFieldFormatter(tr, thisField)
{
	if(thisField.val() == '.')
	{
		thisField.val('0.');
	}

	if(!thisField.hasClass('tax') && tr.find('.tax').val() == '')
	{
		tr.find('.tax').val(0);
	}

	if(!thisField.hasClass('discount') && tr.find('.discount').val() == '')
	{
		tr.find('.discount').val(0);
	}
}

function numberFieldFormatter(thisField)
{
	if(thisField.val() == '.')
	{
		thisField.val('0.');
	}

	if(thisField.val() == '-.')
	{
		thisField.val('-0.');
	}
}

function calculateAllItems(tbody, discountType)
{
	tbody.find('tr').each(function()
	{
		var tr = $(this);
		calculateSingleItem(tr, discountType);
	});
}

function calculateItemSheetFooter(tbody, tfoot)
{
	// Item Sheet - Footer Element
	var subTotal = tfoot.find('.sub-total');
	var subTotalVal = tfoot.find('.sub-total-val');
	var totalTax = tfoot.find('.total-tax');
	var totalTaxVal = tfoot.find('.total-tax-val');
	var totalDiscount = tfoot.find('.total-discount');
	var totalDiscountVal = tfoot.find('.total-discount-val');
	var adjustment = Number(tfoot.find('.adjustment').val());
	var grandTotal = tfoot.find('.grand-total');
	var plainGrandTotal = tfoot.find('.plain-grand-total');

	// Calculate Sub Total
	var currentSubTotal = 0;
	tbody.find('.item-total').each(function()
	{
		currentSubTotal = currentSubTotal + Number($(this).val());
	});
	subTotal.html(twoDecimalFormat(currentSubTotal));
	subTotalVal.val(currentSubTotal);

	// Calculate Total Tax
	var currentTotalTax = 0;
	tbody.find('.tax-val').each(function()
	{
		currentTotalTax = currentTotalTax + Number($(this).val());
	});
	totalTax.html(twoDecimalFormat(currentTotalTax));
	totalTaxVal.val(currentTotalTax);

	// Calculate Total Discount
	var currentTotalDiscount = 0;
	tbody.find('.discount-val').each(function()
	{
		currentTotalDiscount = currentTotalDiscount + Number($(this).val());
	});
	totalDiscount.html(twoDecimalFormat(currentTotalDiscount));
	totalDiscountVal.val(currentTotalDiscount);

	// Calculate Grand Total
	var currentGrandTotal = 0;
	tbody.find('.amount-val').each(function()
	{
		currentGrandTotal = currentGrandTotal + Number($(this).val());
	});
	currentGrandTotalAfterAdjustment = currentGrandTotal + adjustment;
	grandTotal.val(twoDecimalFormat(currentGrandTotalAfterAdjustment));
	plainGrandTotal.val(currentGrandTotal);
}

function dontLeftNumberAwkward(field, value)
{
	if(value == '' || value == '-')
	{
		field.val(0);
	}
	else
	{
		var lastDigit = value.substr(value.length - 1);

		if(lastDigit == '.')
		{
			var complete = value + '0';
			field.val(complete);
		}
	}
}

function pluginInit()
{
	select2PluginInit();
	dropzoneInit();

	$('.datepicker').not('.only-view').datepicker({
	    format: 'yyyy-mm-dd'
	});

	$(document).on('click', '.datepicker', function(e)
	{
		$(this).datepicker('update', $(this).val());
	});

	$('.datetimepicker').datetimepicker({           
	    format: 'Y-m-d h:i A',
	    formatTime: 'h:i A',
	    validateOnBlur: false
	});

	$('[data-toggle="tooltip"]').tooltip();
}

function perfectScrollbarInit()
{
	if($('.perfectscroll').get(0))
	{
		$('.perfectscroll').each(function(index)
		{
			var ps = new PerfectScrollbar($('.perfectscroll').get(index));
		});
	}

	if($('.scroll-dropdown').get(0))
	{
		$('.scroll-dropdown').each(function(index)
		{
			var psDropdown = new PerfectScrollbar($('.scroll-dropdown').get(index));
		});
	}
	
	if($('.scroll-box-x').get(0))
	{
		$('.scroll-box-x').each(function(index)
		{
			var psXBox = new PerfectScrollbar($('.scroll-box-x').get(index));
		});
	}

	if($('.scroll-box').get(0))
	{
		$('.scroll-box').each(function(index)
		{
			var psBox = new PerfectScrollbar($('.scroll-box').get(index), {minScrollbarLength: 50});
		});
	}
}

function sortableInit()
{
	if($('.kanban-list').get(0))
	{
		$('.funnel-card-container').animate({ scrollTop: 0 });
		$('.funnel-container').animate({ scrollLeft: 0 });
		$('.breadcrumb select[data-kanban-select]').attr('disabled', true);

		$('.kanban-list').sortable(
		{
			start: function(event, ui)
			{
				initialStage = ui['item'].closest('.funnel-stage').attr('id');
				ajaxKanbanCard(ui['item'].closest('.funnel-card-container'));
			},
			change: function(event, ui)
			{
				var funnelContainer = ui['item'].closest('.funnel-container');
				var highlightPlaceholder = funnelContainer.find('.ui-state-highlight');
				var funnelCardContainer = $(highlightPlaceholder.closest('.funnel-card-container'));
				var funnelHeight = Math.floor(funnelCardContainer.height());
				var liContainerHeight = Math.floor(funnelCardContainer.find('.li-container').height());
				var animateTime = liContainerHeight * 4;
				
				funnelContainer.find('.funnel-card-container').stop(true);

				if(highlightPlaceholder.offset().top < 275)
				{
					funnelCardContainer.animate({scrollTop : 0}, animateTime);
				}

				if(highlightPlaceholder.offset().top > (funnelHeight - 150))
				{
					funnelCardContainer.animate({scrollTop : nonNegative(liContainerHeight - funnelHeight)}, animateTime);
				}
			},	
			over: function(event, ui)
			{
				uiArray = [];	
				overStage = $($(this).closest('.funnel-stage')).attr('id');

				if(initialStage != overStage)	
				{
					kanbanDragMove(ui, $($(this).closest('.funnel-stage')));
				}
			},
			receive: function(event, ui)
			{
				var funnelStage = $($(this).closest('.funnel-stage'));
				var funnelStageVal = funnelStage.data('stage');

				$(ui['item']['context']).find('input').attr('data-stage', funnelStageVal);	
				ajaxKanbanCard(ui['item'].closest('.funnel-card-container'));			
			},
			update: function(event, ui)
			{
				uiArray.push(ui);
				var uiArrayCount = uiArray.length;
				var currentStage = ui['item'].closest('.funnel-stage').attr('id');
				ui['item'].closest('.funnel-container').find('.funnel-card-container').stop(true);

				if(initialStage == currentStage)
				{
					kanbanUpdate(ui);
				}
				else if(uiArrayCount > 1)
				{	
					kanbanUpdate(uiArray.last());
				}	
			},
			connectWith: '.kanban-list',
			appendTo: '.funnel-container',
			helper: 'clone',
			placeholder: 'ui-state-highlight',
			revert: true,
			items: 'li:not(.disable)'
		}).disableSelection();
	}

	$('.funnel-card-container').each(function(index, ui)
	{
		this.addEventListener('ps-y-reach-end', function()
		{
		    ajaxKanbanCard($(this));
		});

	    this.addEventListener('ps-y-reach-start', function()
	    {
	        $(this).closest('.funnel-stage').removeClass('loading');
	    });

	    this.addEventListener('ps-scroll-up', function()
	    {
	        $(this).closest('.funnel-stage').removeClass('loading');
	    });

	    var cardContainer = $(this);
	    var delayTime = index < 3 ? 175 : 700;
	    setTimeout(function()
	    {
    		if(cardContainer.find('.kanban-list').sortable('instance') != 'undefined')
    		{
    			ajaxKanbanCard(cardContainer);
    		}

    		if($('.funnel-card-container').size() == (index + 1))
    		{
    			$('.breadcrumb select[data-kanban-select]').attr('disabled', false);
    		}
	    }, ((index + 1) * delayTime));
	});
}

function atWhoInit()
{
	if($('.atwho-inputor').get(0))
	{
		$('.atwho-inputor').each(function(index)
		{
			var thisAtWho = $($('.atwho-inputor').get(index));

			var atWhoData = thisAtWho.attr('at-who').split(',');

			thisAtWho.atwho({
				at: '@',
				data: atWhoData
			});
		});
	}
}

function progresslineInit(delay = 0)
{
	if($('.progress-line').get(0))
	{
		setTimeout(function()
		{
			$('.progress-line').each(function(index, ui)
			{
				var pgSize = $(ui).find('.pg').size();
				var pgWidth = 100 / pgSize;
				$(ui).find('.pg').css('width', pgWidth + '%');
			});		
		}, delay);
	}	
}

function createdNodeCallback($node, data)
{
	if(typeof data.image == 'object' && typeof data.image.encoded != 'undefined')
	{
		$node.find('.node-img img').attr('src', data.image.encoded);
	}
	else
	{
		$node.find('.node-img img').attr('src', data.image);
	}
}

function centerTopNode($chart, orgChartId)
{
	var chartViewWidth = $('#' + orgChartId).width();
	var chartActualWidth = $chart.outerWidth(true);
	var topNode = $($('#' + orgChartId + ' .node').get(0));
	var topNodeLeft = parseInt(topNode.offset().left);
	var centerX = parseInt($('#' + orgChartId).width() / 2);
	var diffCenterX = topNodeLeft - centerX - parseInt(topNode.outerWidth(true) / 2);
																		  		
	if(chartActualWidth > chartViewWidth && diffCenterX > 0)
	{																				
		$chart.css('transform', 'matrix(1, 0, 0, 1, -' + diffCenterX + ', ' + 0 + ')');																				
	}
}

function calendarInit()
{
	if($('.calendar').get(0))
	{
		$('.none').hide();

		$('.calendar').each(function(index, ui)
		{
			var dataUrl = $(ui).data('url');
			var positionUrl = $(ui).data('position-url');
			var baseRouteUrl = $(ui).data('route');

			$(ui).fullCalendar(
			{
				header:
				{
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				selectable: true,
				selectHelper: true,
				editable: true,
				eventLimit: true,				
				views:
				{
				    timeGrid:
				    {
				    	eventLimit: 3
				    },
				    week:
				    {
                    	eventLimit: 10
					},
					day:
					{
						eventLimit: 10
					}
				},
				events:
				{
					url: dataUrl,
					type: 'POST',
					data: { start_date: null, end_date: null },
  				},

				select: function(start, end)
				{
					addNewEvent();
					var startDate = moment(start).format('YYYY-MM-DD');
					var endDate = moment(end).add('-1', 'days').format('YYYY-MM-DD');

					$("input[name='start_date']").val(startDate);

					if(startDate != endDate)
					{
						$("input[name='due_date']").val(endDate);
					}
				},

				eventDrop: function(event, delta, revertFunc)
				{					
					var startDate = event.start.format();
					var endDate = startDate;

					if(event.end != null)
					{
						endDate = moment(event.end).add('-1', 'days').format('YYYY-MM-DD');
					}

					$.ajax(
					{
						type 	: 'POST',
						url		: positionUrl,
						data 	: { id : event.id, start : startDate, end : endDate },
					});
				},

				eventClick: function(event, jsEvent, view)
				{
					if(event.auth_can_edit)
					{
						var id = event.id;
						var data = {'id' : id};
						var url = baseRouteUrl + '/' + id + '/edit';
						var updateUrl = baseRouteUrl + '/' + id;

						getEditData(id, data, url, updateUrl);
					}
					else
					{
						window.location.href = event.show_route;
					}
				},

				eventAfterRender: function(event, element, view)
				{
					var requireWidth = event.title.length * 5;

					if($(element).width() > 0 && $(element).width() < requireWidth)
					{
						$(element).attr('data-original-title', event.title);
						$(element).attr('data-html', 'true');
						$(element).tooltip({ container: 'body'});
					}

					$(element).attr('data-url', event.show_route);
					$(element).attr('data-modal', event.auth_can_edit);
				},

				eventAfterAllRender: function(view)
				{

				},
			});
		});
	}
}			

function orgChartInit()
{
	if($('.view-hierarchy').get(0))
	{
		$('.view-hierarchy').each(function(index, ui)
		{
			var orgChart = $(ui);

			if(typeof orgChart.data('url') != 'undefined')
			{
				var orgChartId = orgChart.attr('id');
				var totalNode = orgChart.data('total-node');
				$('#' + orgChartId).hide();

				globalVar.orgChart[orgChartId] = $('#' + orgChartId).orgchart({
													'id'			: orgChartId,
													'data'			: orgChart.data('url'),
													'zoom'			: true,
													'zoominLimit'	: 2.5,
													'zoomoutLimit'	: 0.5,
													'pan'			: true,
													'nodeTemplate'	: function(data) { return data.template },
													'createNode'	: function($node, data) { createdNodeCallback($node, data); },
													'initCompleted'	: function($chart)
																	  {
																	  	var delayTime = limitMinMax(totalNode * 50, 450, 1850); 
																	  	$('#' + orgChartId).fadeIn(delayTime);

																	  	setTimeout(function()
																	  	{
																	  		if($('#' + orgChartId + ' .node').get(0))
																	  		{
															  			  		$('html').getNiceScroll().resize();
															  			  		$('[data-toggle="tooltip"]').tooltip();
															  			  		centerTopNode($chart, orgChartId);															  			  		
																	  		}
																	  	}, delayTime);																	  	
																	  }
												  });
			}
		});
	}
}

function orgChartRefresh(orgChartId, totalNode = null, centertopNode = true, prevChartPosition = null)
{
	var orgChart = $($('#' + orgChartId).get(0));
	totalNode = totalNode != null ? totalNode : parseInt(orgChart.attr('data-total-node'));
	orgChart.attr('data-total-node', totalNode);

	if(typeof orgChart.data('url') != 'undefined')
	{
		$('#' + orgChartId).hide();

		globalVar.orgChart[orgChartId].init({
			'id'			: orgChartId,
			'data'			: orgChart.data('url'),
			'zoom'			: true,
			'zoominLimit'	: 2.5,
			'zoomoutLimit'	: 0.5,
			'pan'			: true,
			'nodeTemplate'	: function(data) { return data.template },
			'createNode'	: function($node, data) { createdNodeCallback($node, data); },
			'initCompleted'	: function($chart)
							  {
							  	var delayTime = limitMinMax(totalNode * 50, 450, 1850);
							  	$('#' + orgChartId).fadeIn(delayTime);

							  	setTimeout(function()
							  	{
							  		if($('#' + orgChartId + ' .node').get(0))
							  		{
					  			  		$('html').getNiceScroll().resize();
					  			  		$('[data-toggle="tooltip"]').tooltip();
					  			  		orgChart.attr('data-total-node', parseInt($('#' + orgChartId).attr('data-total-node')));

					  			  		if(centertopNode)
					  			  		{
			  			  			  		centerTopNode($chart, orgChartId);
					  			  		}
					  			  		else if(prevChartPosition != null)
					  			  		{
					  			  			$chart.css('transform', prevChartPosition);
					  			  		}
							  		}
							  	}, delayTime);																	  	
							  }
		});
	}
}

function dropzoneInit()
{
	if($('.dropzone').get(0))
	{
		$('.dropzone').each(function(index)
		{
			var thisDropzone = $($('.dropzone').get(index));

			if(!thisDropzone.hasClass('dz-clickable'))
			{
				var dropzoneId = '#' + thisDropzone.attr('id');
				var dropzoneContainer = $(thisDropzone.closest('.dropzone-container'));
				var previewContainer = '#' + thisDropzone.attr('data-preview');
				var url = thisDropzone.data('url');
				var removeUrl = thisDropzone.data('removeurl');
				var linked = thisDropzone.data('linked');
				var maxFiles = typeof thisDropzone.attr('max-files') != 'undefined' ? parseInt(thisDropzone.attr('max-files')) : 10;
				var dzError = dropzoneContainer.find(".validation-error[field='dropzone-error']");
				var identifier = 'dropzone-' + index + Math.floor((Math.random()*10000000)+1);
				thisDropzone.attr('data-identifier', identifier);

				$(dropzoneId).dropzone({ 
					url: url,
					dictDefaultMessage: 'Drop max ' + maxFiles + ' files here or click to upload.',
					addRemoveLinks: true,
					timeout: 300000,
					maxFiles: maxFiles,
					previewsContainer: previewContainer,
					init: function() 
					{
						globalVar.dropzone[identifier] = this;

						this.on('addedfile', function(file)
						{
							var icon = getFileIconHtml(file.name);
							$($(file.previewElement).find('.dz-details')).prepend(icon);
						});

						this.on('removedfile', function(file) 
				        {
				            var fileName = $($(file.previewElement).find('.dz-filename')).attr('data-original');
				            dropzoneContainer.find("input[name='uploaded_files[]'][value='"+fileName+"']").remove();

				            $.ajax({
				                type : 'POST',
				                data : {linked : linked, uploaded_files : fileName},
				                url  : removeUrl
				            });
				        });

		        		this.on('maxfilesreached', function(files) 
		                {
							if(files.length == (maxFiles + 1))
							{
								var errorMsg = 'The uploaded files may not have more than ' + maxFiles + ' items.';
								if(dzError.css('display') == 'none')
								{
									dzError.text(errorMsg);
									dzError.css('display', 'block');
									setTimeout(function() { dzError.fadeOut('1500'); }, 1500);
								}	
							}
		                });

                		this.on('maxfilesexceeded', function(file) 
                        {      					        		
        					this.removeFile(file);
                        });

				        this.on('error', function(file, errormessage, xhr)
				        {
				            if(typeof xhr != 'undefined')
				            {
				            	var thisDz = this;

				                $(file.previewElement).addClass('dz-error');
				                $($(file.previewElement).find('.dz-error-message span')).html('Upload failed');		                             
				            	$(file.previewElement).fadeOut(1750, function()
				            	{
				            		thisDz.removeFile(file);
				            	});
				            }
				        });
					},
					sending: function(file, xhr, formData) 
					{
				        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
				        formData.append('linked', linked);
					},
					success: function(data, response)
					{
						textOverflowTitle('.dz-filename span');
						$($(data.previewElement).find('.dz-filename')).attr('data-original', response.fileName);
						dropzoneContainer.append($('<input>', { type: 'hidden', name: 'uploaded_files[]', val: response.fileName }));
					}
				});
			}	
		});	
	}

	if($('.modalfree-dropzone').get(0))
	{
		$('.modalfree-dropzone').each(function(index)
		{
			var thisDropzone = $($('.modalfree-dropzone').get(index));			

			if(!thisDropzone.hasClass('dz-clickable'))
			{
				var dropzoneId = 'modalfree-dropzone-' + index + Math.floor((Math.random()*10000000)+1);
				thisDropzone.attr('id', dropzoneId);
				var dropzoneContainer = $(thisDropzone.closest('.dropzone-container'));
				var fromGroup = $(dropzoneContainer.closest('.form-group'));
				var previewContainer = $(dropzoneContainer.find('.dz-preview-container'));
				var previewContainerId = 'modalfree-dropzone-preview-' + index + Math.floor((Math.random()*10000000)+1);
				previewContainer.attr('id', previewContainerId);
				var url = thisDropzone.data('url');
				var removeUrl = thisDropzone.data('removeurl');
				var linked = thisDropzone.data('linked');
				var maxFiles = typeof thisDropzone.attr('max-files') != 'undefined' ? parseInt(thisDropzone.attr('max-files')) : 10;

				thisDropzone.dropzone({ 
					url: url,
					dictDefaultMessage: 'Drop max ' + maxFiles + ' files here or click to upload.',
					addRemoveLinks: true,
					timeout: 300000,
					maxFiles: maxFiles,
					previewsContainer: '#' + previewContainerId,
					init: function() 
					{
						globalVar.dropzone[dropzoneId] = this;

						this.on('addedfile', function(file)
						{							
							var addStatus = true;

							if(dropzoneContainer.hasClass('update-dz'))
							{
								var existedUploaded = dropzoneContainer.find("input[name='uploaded_files[]']").size();

								if(existedUploaded >= maxFiles)
								{
									addStatus = false;
									this.removeFile(file);		                			
									var errorMsg = 'The uploaded files may not have more than ' + maxFiles + ' items.';
									if(!$(".alert.alert-danger[role='alert']").get(0))
									{
										$.notify({ message: errorMsg }, globalVar.dangerNotify);
									}		                			
								}
							}
							
							if(addStatus)
							{
								fromGroup.find('.btn').not('.cancel').attr('disabled', true);
								var icon = getFileIconHtml(file.name);
								$($(file.previewElement).find('.dz-details')).prepend(icon);
							}							
						});

						this.on('removedfile', function(file) 
				        {
				            var fileName = $($(file.previewElement).find('.dz-filename')).attr('data-original');
				            dropzoneContainer.find("input[name='uploaded_files[]'][value='"+fileName+"']").remove();

				            $.ajax({
				                type : 'POST',
				                data : {linked : linked, uploaded_files : fileName},
				                url  : removeUrl
				            });				            
				        });

		        		this.on('maxfilesreached', function(files) 
		                {
							if(files.length == (maxFiles + 1))
							{
								var errorMsg = 'The uploaded files may not have more than ' + maxFiles + ' items.';
								if(!$(".alert.alert-danger[role='alert']").get(0))
								{
									$.notify({ message: errorMsg }, globalVar.dangerNotify);
								}	
							}
		                });

                		this.on('maxfilesexceeded', function(file) 
                        {      					        		
        					this.removeFile(file);
                        });

				        this.on('error', function(file, errormessage, xhr)
				        {  	   	
				            if(typeof xhr != 'undefined')
				            {
				            	var thisDz = this;

				                $(file.previewElement).addClass('dz-error');
				                $($(file.previewElement).find('.dz-error-message span')).html('Upload failed');
				            	$(file.previewElement).fadeOut(1750, function()
				            	{
				            		thisDz.removeFile(file);
				            	});
				            }
				        });

				        this.on('success', function(file)
		                {
		                	if(dropzoneContainer.hasClass('update-dz'))
		                	{
		                		var existedUploaded = dropzoneContainer.find("input[name='uploaded_files[]']").size();
		                		
		                		if(existedUploaded > maxFiles)
		                		{
		                			this.removeFile(file);		                			
		                			var errorMsg = 'You can not upload more than ' + maxFiles + ' files.';
		                			if(!$(".alert.alert-danger[role='alert']").get(0))
		                			{
		                				$.notify({ message: errorMsg }, globalVar.dangerNotify);
		                			}		                			
		                		}
		                	}
		                });

				        this.on('complete', function(file)
				        {
							if(this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0)
							{
								fromGroup.find('.btn').not('.cancel').attr('disabled', false);
							}						
				        });
					},
					sending: function(file, xhr, formData) 
					{
				        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
				        formData.append('linked', linked);
					},
					success: function(data, response)
					{
						$('html').getNiceScroll().resize();
						textOverflowTitle('.dz-filename span');
						$($(data.previewElement).find('.dz-filename')).attr('data-original', response.fileName);
						dropzoneContainer.append($('<input>', { type: 'hidden', name: 'uploaded_files[]', val: response.fileName }));
					}
				});
			}
		});	
	}
}

function cropperInit(cropper)
{
	var cropWrap = $(cropper.closest('.cropper-wrap'));

	setTimeout(function() 
	{
		cropper.cropper({
			viewMode: 1,
			aspectRatio: 1 / 1,
			background: false,
			movable: false,
			zoomable: false,
			zoomOnTouch: false,
			zoomOnWheel: false,
			minCropBoxWidth: 150,
			minCropBoxHeight: 150,
			guides: false,
			center: false,
			rotatable: false,
			autoCropArea: 0.7,
			ready: function(event)
			{
				var containerWidth = cropWrap.find('.cropper-container').width();
				var containerHeight = cropWrap.find('.cropper-container').height();
				var canvasWidth = cropWrap.find('.cropper-canvas').width();
				var canvasHeight = cropWrap.find('.cropper-canvas').height();
				var left = (containerWidth - canvasWidth) / 2;
				var top = (containerHeight - canvasHeight) / 2;

				cropWrap.find('.cropper-modal').css('width', Math.round(canvasWidth) + 'px');
				cropWrap.find('.cropper-modal').css('height', Math.round(canvasHeight) + 'px');
				cropWrap.find('.cropper-modal').css('left', Math.floor(left) + 'px');
				cropWrap.find('.cropper-modal').css('top', Math.floor(top) + 'px');
			},
			crop: function(event)
			{
				cropWrap.find("input[name='x']").val(parseInt(event.detail.x));
				cropWrap.find("input[name='y']").val(parseInt(event.detail.y));
				cropWrap.find("input[name='width']").val(parseInt(event.detail.width));
				cropWrap.find("input[name='height']").val(parseInt(event.detail.height));
			},
		});
	}, 
	500);
}

function extensionValidation(file, extensions = [])
{
	var filename = file.name;
	var filetypeInfo = file.type.split('/');
	var filetype = filetypeInfo[filetypeInfo.length - 1];
	var extension = filename.substr((filename.lastIndexOf('.') +1)).toLowerCase();
	var validExtension = !(extensions.indexOf(extension) == -1);
	var validType = !(extensions.indexOf(filetype) == -1);
	return (validExtension && validType);
}

function filesizeValidation(file, size = 2000000)
{
	return (file.size <= size);
}

function getFileIconHtml(filename)
{
	var extension = filename.substr((filename.lastIndexOf('.') +1)).toLowerCase();

	switch(extension)
	{
		case 'webp':
		case 'jpeg':
		case 'jpg' :
		case 'png' :
		case 'gif' :
            return "<span class='icon image fa fa-file-image-o'></span>";
        break;

        case 'zip':
        case 'rar':
        case 'iso':
        case 'tar':
        case 'tgz':
        case '7z' :
        case 'apk':
        case 'dmg':
            return "<span class='icon zip fa fa-file-zip-o'></span>";
        break;

        case 'docx':
        case 'doc' :
            return "<span class='icon word fa fa-file-word-o'></span>";
        break;

        case 'xlsx':
        case 'xls' :
        case 'csv' :
        case 'ods' :
            return "<span class='icon excel fa fa-file-excel-o'></span>";
        break;

        case 'pptx':
        case 'pptm':
        case 'ppt' :
            return "<span class='icon powerpoint fa fa-file-powerpoint-o'></span>";
        break;

        case 'pdf':
            return "<span class='icon pdf fa fa-file-pdf-o'></span>";
        break;

        case 'wav' :
        case 'wma' :
        case 'mpc' :
        case 'msv' :
            return "<span class='icon audio fa fa-file-audio-o'></span>";
        break;

        case 'mp3' :
        case 'm4a' :
        case 'm4b' :
        case 'm4p' :
        	return "<span class='icon audio fa fa-music'></span>";
        break;

        case 'mov':
        case 'mp4':
        case 'avi':
        case 'flv':
        case 'wmv':
        case 'swf':
        case 'mkv':
        case 'mpg':
            return "<span class='icon video fa fa-file-video-o'></span>";
        break;

        case 'txt':
            return "<span class='icon text fa fa-file-text-o'></span>";
        break;

        case 'html':
        case 'php' :
            return "<span class='icon code fa fa-file-code-o'></span>";
        break;

        default: return "<span class='icon file fa fa-file-o'></span>";
	}
}

function textOverflowTitle(className)
{
	$(className + ':not([data-checked="true"])').each(function(index, ui)
	{
		$(ui).attr('data-checked', 'true');
		if($(ui).width() > $($(ui).parent()).width())
		{
			$(ui).attr('title', $(ui).text());
			$(ui).attr('data-toggle', 'tooltip');	
			$(ui).attr('data-placement', 'top');
			$(ui).tooltip();
			$(ui).attr('title', '');							  
		}
	});
}

function resetCheckboxRadio()
{
	$('input:checkbox, input:radio').each(function(index, ui)
	{
		var checked = $(ui).attr('checked');

		if(checked)
		{
			$(ui).prop('checked', true)
		}
		else
		{
			$(ui).prop('checked', false);
		}
	});
}

function select2PluginInit()
{
	if($('.select-type-single').get(0))
	{
		$('.select-type-single').select2().on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.select-type-single-b').get(0))
	{
		$('.select-type-single-b').select2({
			'minimumResultsForSearch' : -1
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.select-type-multiple').get(0))
	{
		$('.select-type-multiple').select2({
			allowClear: true
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.breadcrumb-select').get(0))
	{
		$('.breadcrumb-select').select2({
			containerCssClass: 'breadcrumb-select-container', 
			dropdownCssClass: 'breadcrumb-select-dropdown',
			selectOnClose: true
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.white-select-type-single').get(0))
	{
		$('.white-select-type-single').select2({
			containerCssClass: 'white-container', 
			dropdownCssClass: 'white-dropdown'
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.white-select-single-clear').get(0))
	{
		$('.white-select-single-clear').select2({
			containerCssClass: 'white-container', 
			dropdownCssClass: 'white-dropdown',
			allowClear: true,
			placeholder: function()
			{
			    $(this).data('placeholder');
			}
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.white-select-type-single-b').get(0))
	{
		$('.white-select-type-single-b').select2({
			minimumResultsForSearch : -1,
			containerCssClass: 'white-container',
			dropdownCssClass: 'white-dropdown'
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.white-select-type-multiple').get(0))
	{
		$('.white-select-type-multiple').select2({
			containerCssClass: 'white-container', 
			dropdownCssClass: 'white-dropdown',
			allowClear: true,
			placeholder: function()
			{
			    $(this).data('placeholder');
			}
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}

	if($('.white-select-type-multiple-tags').get(0))
	{
		$('.white-select-type-multiple-tags').select2({
			containerCssClass: 'white-container tags', 
			dropdownCssClass: 'white-dropdown tags',
			tags: true,
			placeholder: function()
			{
			    $(this).data('placeholder');
			},
			language:
			{
			    noResults: function(params)
			    {
			    	return 'Type to search';
			    }
			}
		}).on('select2:open', function(e)
		{
			initSelect2PerfectScroll();
		}).on('select2:close', function(e)
		{
			destroySelect2PerfectScroll();
		});
	}
}

function setSliderRangeHotArea(slideRightVal = null)
{
	var left = slideRightVal != null ? slideRightVal : parseFloat($('.slider-range span:last').css('left').slice(0, -1));
	var restWidth = 100 - left;
	$('.slider-range .hot-area').css('width', restWidth + '%');
}

function resetCurrency(currencyList, currencyId, currencyIdVal, tagIcon, icon, alterIcon)
{
	if(icon == '' || icon == null)
	{
		tagIcon.removeAttr('class');
		tagIcon.html(alterIcon);			
	}
	else
	{
		tagIcon.attr('class', icon);		
		tagIcon.html('');
	}

	tagIcon.addClass('dropdown-toggle');

	currencyId.val(currencyIdVal);
	currencyList.find('li').removeClass('selected');
	currencyList.find("li[value='" + currencyIdVal + "']").addClass('selected');
}

function modalCurrency(tr, modalId)
{
	var div = $(modalId).find('div.amount');
	var currencyList = div.find('.currency-list');
	var tagIcon = div.find('i');

	if(typeof currencyList !== 'undefined' || typeof tagIcon !== 'undefined')
	{					
		var event = tagIcon.attr('event', 'edit');				
		var symbol = $(tr.find('span.symbol').get(0));
		var icon = symbol.attr('icon');
		var alterIcon = symbol.html();
		var currencyVal = symbol.attr('value');

		if(icon == '')
		{
			tagIcon.removeAttr('class');
			tagIcon.html(alterIcon);			
		}
		else
		{
			tagIcon.attr('class', icon);
			tagIcon.html('');
		}

		tagIcon.addClass('dropdown-toggle');
		currencyList.find('li').removeClass('selected');
		currencyList.find("li[value='" + currencyVal + "']").addClass('selected');
	}
}

function limitMinMax(value, min = null, max = null)
{
	if(min != null && value < min)
	{
		return min;
	}

	if(max != null && value > max)
	{
		return max;
	}

	return value;
}

function arrayMax(array)
{
	return Math.max.apply(Math, array);
}

function arrayMin(array)
{
	return Math.min.apply(Math, array);
}

Array.prototype.last = function()
{
	return this[this.length-1];
}

function nonNegative(value)
{
	return Math.max(0, value);
}

function ucword(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function resetCommentForm(form, emptyfiles = true)
{
	var dropzoneId = form.find('.modalfree-dropzone').attr('id');
	if(emptyfiles)
	{
		globalVar.dropzone[dropzoneId].files = [];
	}
	else
	{
		globalVar.dropzone[dropzoneId].removeAllFiles(true);
	}

	form.find('textarea').css('height', '34px');
	form.find('textarea').val('');
	form.find('.form-group.bottom').slideUp('fast');
	form.find('.dz-preview-container').html('');
	form.find("input[name='uploaded_files[]']").remove();
}

function previewImg(browse, preview)
{
    if(browse.files && browse.files[0])
    {
        var reader = new FileReader();
        reader.onload = function(e)
        {
            preview.attr('src', e.target.result);
        }

        reader.readAsDataURL(browse.files[0]);
    }
}

function notifyErrors(errors, reload = false)
{
	if(errors.length)
	{
		$.each(errors, function(index, value)
		{
			$.notify({ message: value }, globalVar.dangerNotify);
		});
	}
	else
	{
		$.notify({ message: 'Something went wrong.' }, globalVar.dangerNotify);
	}	

	if(reload)
	{
		setTimeout(location.reload.bind(location), 1000);
	}
}

function ajaxValidation(form, formUrl, formData)
{
	$.ajax(
	{
	    type    : 'POST',
	    url     : formUrl,
	    data    : formData,
	    dataType: 'JSON',
	    success : function(data)
	              {
	              	switch(data.status)
	              	{
	              		case true :
	              			form.submit();
	              		break;

	              		case false :
	              			$('span.validation-error').html('');
	              			var positions = [];

	              			$.each(data.errors, function(index, value)
	              			{
	              				$("span[error-field='"+index+"']").html(value);
	              				positions.push(parseInt($("span[error-field='"+index+"']").offset().top));           				
	              			});

	              			var scrollTopVal = nonNegative(arrayMin(positions) - 200);
	              			$('html, body').animate({ scrollTop: scrollTopVal }, 'fast');
	              		break;

	              		default : location.reload();
	              	}
	              }
	});
}

function displayTableFieldUpdate(container, formData)
{
	$.ajax(
	{
		type 	: 'POST',
		url 	: container.attr('action'),
		data 	: formData,
		dataType: 'JSON',
		success : function(data)
		          {
		          	if(typeof globalVar.jqueryDataTable != 'undefined')
		          	{
		          	    globalVar.jqueryDataTable.ajax.reload(null, false);
		          	}

		          	$.each(data.realtime, function(index, value)
		          	{
		          		$("span[realtime='"+index+"']").html(value);
		          		$("input[realtime='"+index+"']").val(value).trigger('change');
		          	}); 

		          	container.hide();
		          	container.prev('.editable').show();

		          	if(data.status == false)
		          	{
		          		notifyErrors(data.errors);		          		
		          	}
		          },
		error 	: function(jqXHR, textStatus, errorThrown)
				  {
				  	location.reload();
				  }	            
	});
}

function modalDataStore(modalId, form, listOrder = true, saveAndNew = false, tableDraw = true)
{
	$(modalId + ' .processing').html("<div class='loader-ring-sm'></div>");
	$(modalId + ' .processing').show();			    

	var table = globalVar.jqueryDataTable;			
	var formUrl = form.prop('action');
	var enctype = (typeof form.attr('enctype') != 'undefined');
	var formData = enctype ? new FormData($(modalId + ' form').get(0)) : form.serialize();

	var ajaxArg = {
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
	                    if(saveAndNew == true)
	                    {
	                    	// reset to default values
	                    	form.trigger('reset');
	                    	form.find('.select2-hidden-accessible').trigger('change');
	                    }   
	                    else
	                    {
	                    	delayModalHide(modalId, 1);
	                    }

	                	if(typeof data.innerHtml != 'undefined')
	                	{
	                		$(data.innerHtml).each(function(index, value)
	                		{
    	           				$(value[0]).html(value[1]);
	                		});
	                	}

	                    if(typeof data.tabTable != 'undefined' && typeof globalVar.dataTable[data.tabTable] != 'undefined')
	                    {
	                    	table = globalVar.dataTable[data.tabTable];
	                    }

	                    if(typeof table != 'undefined' && tableDraw == true)
	                    {
	                    	if(listOrder)
	                    	{
	                    		table.page('first').draw('page');
	                    		focusSavedRow(table);                    		
	                    	}
	                    	else
	                    	{
	                    		table.page('last').draw('page');
	                    		focusSavedRow(table, false);
	                    	}
	                    }	

	                    if(typeof data.modalImage != 'undefined' && data.modalImage != null)
	                    {
	                    	$(".modal-image[data-avt='"+ data.modalImageType +"'] img").attr('src', data.modalImage);		                    	
	                    	$('.modal .modal-image img').attr('src', data.modalImage);
	                    	$('.modal .modal-image input').val(data.fileName);
	                    }

	                    if($('.calendar').get(0))
	                    {
                    		var event = $.parseJSON(data.renderEvent);
	                    	$('.calendar').fullCalendar('renderEvent', event);
	                    }	 

	                    if($('.orgchart').get(0))
	                    {
	                    	var $orgChartContainer = $($(".view-hierarchy[data-module='"+ data.module +"']").get(0));
	                    	var $orgChart = $($orgChartContainer.find('.orgchart'));
	                    	var refreshStatus = false;

	                    	if(typeof data.node != 'undefined')
	                    	{
	                    		var dataNode = [data.node];
	                    		$orgChartContainer.attr('data-total-node', parseInt($orgChartContainer.attr('data-total-node')) + 1);

	                    		if(data.node.siblingId == null)
	                    		{
	                    			var $parentNode = $($orgChartContainer.find(".node[id='"+ data.node.parentId +"']"));
	                    			globalVar.orgChart[$orgChartContainer.attr('id')].addChildren($parentNode, dataNode);
	                    		}
	                    		else
	                    		{
	                    			var $siblingNode = $($orgChartContainer.find(".node[id='"+ data.node.siblingId +"']"));
	                    			// Org Chart Js Bug found
	                    			// globalVar.orgChart[$orgChartContainer.attr('id')].addSiblings($siblingNode, dataNode);
                    				refreshStatus = true;
	                    		}
	                    	}

	                    	if(typeof data.orgChartRefresh != 'undefined' && data.orgChartRefresh == true)
	                    	{
	                    		refreshStatus = true;
	                    	}

	                    	if(refreshStatus == true)
	                    	{
	                    		var chartPosition = $orgChart.css('transform') != 'none' ? $orgChart.css('transform') : null;
	                    		var centerTopNode = chartPosition == null ? true : false;
	                    		orgChartRefresh($orgChartContainer.attr('id'), null, centerTopNode, chartPosition);
	                    	}
	                    }	

	                    if(typeof data.realtime != 'undefined')
	                    {
		                    $.each(data.realtime, function(index, value)
		                    {
		                    	$("span[realtime='"+index+"']").html(value);
		                    	$("*[data-realtime='"+index+"']").html(value);
		                    	$("input[realtime='"+index+"']").val(value).trigger('change');
		                    });
		                }    

	                    if($('.funnel-container').get(0))
	                    {
	                    	if(typeof data.kanbanAddStatus == 'undefined' || data.kanbanAddStatus == true)
	                    	{
	                    		$.each(data.kanban, function(stage, cards)
	                    		{
	                    			$.each(cards, function(position, card)
	                    			{
	                    				$(".funnel-stage[id='"+stage+"'] .kanban-list").prepend(card);
	                    			});
	                    		});
	                    	}

	                    	kanbanCountResponse(data);
	                    }     

	                    $('[data-toggle="tooltip"]').tooltip();
	                    $('html').getNiceScroll().resize();       
	                }
	                else
	                {
	                	$(modalId + ' span.validation-error').html('');
	                	$.each(data.errors, function(index, value)
	                	{
	                		$(modalId + " span[field='"+index+"']").html(value);
	                	});

	                	$(modalId + ' .processing').html("<span class='fa fa-exclamation-circle error'></span>");
	                

	                	if(typeof data.errorsClose != 'undefined' && data.errorsClose == true)
	                	{		                    	
	                		delayModalHide(modalId, 3);
	                	}
	                }
	              }
	};

	if(enctype)
	{
		ajaxArg.processData = false;
		ajaxArg.contentType = false;
	}

	$.ajax(ajaxArg);
}

function focusSavedRow(table, row = true)
{
	var tbody = $(table.table().body());

	if(row == true)
	{
		var tr = 'tr:first-child';
	}
	else if(row == false)
	{
		var tr = 'tr:last-child';
	}
	else
	{
		var nth = $($(tbody.find("a[editid='"+row+"']")).closest('tr')).prevAll('tr').size() + 1;
		var tr = 'tr:nth-child(' + nth + ')'; 
	}

	setTimeout( function() { 
	    tbody.find(tr).addClass('saved');
	}, 2350);

	setTimeout( function() { 
	    tbody.find(tr).removeClass('saved');
	}, 5850);
}

function smoothSave(formUrl, formData)
{
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
	                	$('span.validation-error').html('');  

	                	$.each(data.realtime, function(index, info)
	                	{	                		
	                		if(info.tag == 'img' && $("img[realtime='"+index+"']").get(0))
	                		{
	                			var validSrc = info.value.replace(globalVar.baseUrl + '/', '');

	                			if(validSrc)
	                			{
	                				$("img[realtime='"+index+"']").prop('src', info.value);
	                				$("input[name='"+index+"']").val('');
	                				$("img[realtime='"+index+"']").show();
	                			}	                			
	                		}
	                	});

	                	$.notify({ message: 'Update was successful' }, globalVar.successNotify);
	                }
	                else
	                {
	                	$('span.validation-error').html('');
	                	var positions = [];

	                	$.each(data.errors, function(index, value)
	                	{
	                		$("span[field='"+index+"']").html(value);
	                		positions.push(parseInt($("span[field='"+index+"']").offset().top)); 
	                	});

	                	if(data.errors == null)
	                	{
	                		$.notify({ message: 'Something went wrong.' }, globalVar.dangerNotify);
	                	}
	                	else
	                	{
	                		var scrollTopVal = nonNegative(arrayMin(positions) - 200);
	                		$('html, body').animate({ scrollTop: scrollTopVal }, 'fast');
	                	}
	                }
	              }
	});
}

function globalChangeStatus(thisSwitch, input, postUrl, inactiveStatusTxt = 'Inactive', activeStatusTxt = 'Active')
{
	if(!thisSwitch.hasClass('disabled'))
	{
		var id = input.val();
		var checked = input.prop('checked') ? 1 : 0;
		
		$.ajax({
			type 	: 'POST',
			url 	: postUrl,
			data 	: { id : id, checked :  checked },
			dataType: 'JSON',
			success	: function(data)
					  {
					  	if(data.status == true)
					  	{
					  		var updateStatus = inactiveStatusTxt;
					  		if(data.checked)
					  		{
					  			updateStatus = activeStatusTxt;
					  		}
					  		thisSwitch.attr('data-original-title', updateStatus);
					  		thisSwitch.parent().find('.tooltip-inner').html(updateStatus);
					  	}
					  	else
					  	{
					  		input.prop('checked', !checked);
					  	}
					  },
			error 	: function(xhr, status, error)
					  {
					  	input.prop('checked', !checked);
					  }		  
		});
	}
}

function ajaxDropdownList(requestUrl, data, selectIdentifier, topItem = null, bottomItem = null, bottomDefault = false, prefix = '', postfix = '', defaultType = true)
{
  	$.ajax(
  	{
  	    type    : 'GET',
  	    url     : requestUrl,
  	    data    : data,
  	    dataType: 'JSON',
  	    success : function(data)
  	              {
  	                if(data.status == true)
  	                {
  	                	var select = $(selectIdentifier).empty();
  	                	var defaultVal = null;

  	                	if(topItem !== null)
  	                	{
  	                		$('<option/>', topItem).appendTo(select);
  	                	}  	                	

  	                    $.each(data.items, function(order, item)
  	                    {
	                    	$('<option/>', { value : item.id, text : prefix + item.name + postfix}).appendTo(select);
  	                    	
  	                    	if(defaultType == false)
  	                    	{
  	                    		defaultVal = item.id;
  	                    	}  	                    	
  	                    });

  	                    if(data.items.length > 0 && defaultVal !== null && bottomItem !== null)
  	                    {
  	                    	$('<option/>', bottomItem).appendTo(select);

  	                    	if(bottomDefault == true)
  	                    	{
  	                    		defaultVal = bottomItem.value;
  	                    	}                   	
  	                    }

  	                    globalVar.defaultDropdown.push({ identifier : selectIdentifier, default : defaultVal });

  	                    select.val(defaultVal).trigger('change');
  	                }
  	              }
  	});
}	

function disabledCurrentItem(selectIdentifier, currentItem, select2 = false, select2Element = null,select2Arg = {})
{
	$(selectIdentifier + ' option').prop('disabled', false);
	$(selectIdentifier + " option[value='"+currentItem+"']").prop("disabled", true);
	if(select2 == true)
	{
		$(select2Element).select2('destroy').select2(select2Arg);
	}	
}

function initD3Funnel(funnelId, data, pinched = 2)
{
    var options = {
    	chart: 
    	{
    		width: '100%',
    		height: '100%',
    		bottomPinch: pinched,
    		animate: 100,
    	},
        block: 
        {
            dynamicHeight: true,
            minHeight: 20,
        },
        label: 
        {
        	enabled: true,
        	format: '{l} : {f}',
        },
    };

    d3.select(funnelId).selectAll('svg').remove();

    if(data.length > 0)
    {
    	var chart = new D3Funnel(funnelId);
    	chart.draw(data, options);
    }	 
}

function initChartJsPie(canvasId, pieLabelArray, pieDataArray, pieBackgroundArray)
{
	var pieData = {
	    datasets: 
	    [{
	        data: pieDataArray,
	        backgroundColor: pieBackgroundArray,
			borderWidth: 1,		         
	    }],
	    labels: pieLabelArray,
	};

	var options = {
		responsive: true,
		legend:
		{
            display: true,
            position: 'right',
            labels: 
            {
                fontColor: 'rgba(255, 255, 255, 0.85)',
                boxWidth: 11,
                fontSize: 11,
                fontFamily: "'Open Sans', 'Helvetica Neue'",
                padding: 9
            }
		},
		tooltips:
		{
			callbacks: 
			{
			    label: function(tooltipItem, data) 
			    {
			    	var chart = this._chartInstance;
			    	var total = chart.getDatasetMeta(0).total;
					var thisVal = parseInt(data.datasets[0].data[tooltipItem.index]);
					var percentage = ((thisVal * 100) / total).toFixed(2);
					var label = data.labels[tooltipItem.index] + ': ' + thisVal + ' (' + percentage + '%)'; 
					return label;
			    }
			}
		}
	};

	if(typeof $(canvasId).get(0).$chartjs != 'undefined')
	{
		globalVar.pieChart[canvasId].destroy();
	}

	var areAllZero = pieDataArray.every(function(val) { return val == 0; });

	if(!areAllZero)
	{
		var thisPieChart = new Chart($(canvasId).get(0), {
		    type: 'pie',
		    data: pieData,
		    options: options
		});

		thisPieChart.update();

		globalVar.pieChart[canvasId] = thisPieChart;
	}
}

function initChartJsTimeline(canvasId, days, years, labelY, dataY, backgroundY, borderY)
{
	var datasets = [];

	$(labelY).each(function(index, label)
	{
		var set = {
			label: label,
			data: dataY[index],				
			backgroundColor: backgroundY[index],
			borderColor: borderY[index],	
			borderWidth: 1.5,
			pointBorderWidth: 1,
			pointHoverBorderWidth: 5,
			fill: false								
		};

		datasets.push(set);
	});

	var maxDataSet = dataY.map(function(set) { return Math.max.apply(Math, set); });
	var max = Math.max.apply(null, maxDataSet);	

	var valSuffix = $(canvasId).data('suffix');
	var yLabelMin = $(canvasId).data('min');
	var yLabelMax = $(canvasId).data('max');
	var yLabelStep = $(canvasId).data('step');
	var stepSize = $(canvasId).data('step-size');
	var stepSizeVal = 0;	

	if(max > yLabelMin && max < yLabelMax)
	{
		max = max + Math.round((max - yLabelMin) / yLabelStep);
		max = Math.round(max);
		yLabelMax = max;
	}
	else if(max == yLabelMax)
	{
		stepSizeVal = stepSize;		
		yLabelStep = yLabelStep + 1;
		yLabelMax = yLabelMax + stepSize;
	}
	else if(max > yLabelMax)
	{
		var addLabelStep = Math.round((max - yLabelMax) / stepSize);
		stepSizeVal = stepSize;
		yLabelStep = yLabelStep + addLabelStep;
		yLabelMax = yLabelMax + (addLabelStep * stepSize);
	}

	var data = {
		labels: days,
		year: years,
		datasets: datasets
	};

	var options = {
		responsive: true,
		maintainAspectRatio: false,
		title: { display: false },
		legend:
		{
            display: true,
            position: 'bottom',
            labels: 
            {
                fontColor: 'rgba(255, 255, 255, 0.85)',
                boxWidth: 11,
                fontSize: 11,
                fontStyle: 600,
                fontFamily: "'Open Sans', 'Helvetica Neue'",
                padding: 20
            }
		},
		tooltips: 
		{
			mode: 'index',
			intersect: false,
			xPadding: 5,
			yPadding: 5,
			bodySpacing: 5,
			backgroundColor: 'rgba(0, 0, 0, 0.65)',
			titleFontSize: 11,
			titleFontFamily: "'Open Sans', 'Helvetica Neue'",
			footerFontFamily: "'Open Sans', 'Helvetica Neue'",
			multiKeyBackground: 'rgba(0, 0, 0, 0.5)',
			callbacks:
			{
				label: function(tooltipItem, data) 
				{
					var label = data.datasets[tooltipItem.datasetIndex].label;
					return label + ': ' + tooltipItem.yLabel + valSuffix;
				},
				title: function(tooltipItem, data) 
				{
					var title = tooltipItem[0].xLabel + ', ' + data.year[tooltipItem[0].index];
					return title;
				}
			}
		},
		hover: 
		{
			mode: 'index',
			intersect: false
		},
		scales: 
		{
			xAxes: 
			[{
				display: true,
				scaleLabel: { display: false },
				gridLines: 
				{
				    display: true,
				    color: 'rgba(255, 255, 255, 0.1)',
				    zeroLineColor: 'rgba(255, 255, 255, 0.05)'
				},
				ticks:
				{
	                fontColor: 'rgba(255, 255, 255, 0.5)',
	                fontFamily: "'Open Sans', 'Helvetica Neue'",
	                fontSize: 11,
	                fontStyle: 550,
    				maxTicksLimit: 31
	            }
			}],
			yAxes: 
			[{
				display: true,
				scaleLabel: { display: false },
				gridLines: 
				{
				    display: true,
				    color: 'rgba(255, 255, 255, 0.1)',
				    zeroLineColor: 'rgba(255, 255, 255, 0.05)'
				},
				ticks:
				{
	                fontColor: 'rgba(255, 255, 255, 0.5)',
	                fontFamily: "'Open Sans', 'Helvetica Neue'",
	                fontSize: 11,
	                fontStyle: 550,
	                suggestedMin: yLabelMin,
					suggestedMax: yLabelMax,
					maxTicksLimit: yLabelStep,
					stepSize: stepSizeVal,
					beginAtZero: true
	            }
			}]
		}
	};

	if(typeof $(canvasId).get(0).$chartjs != 'undefined')
	{
		globalVar.timelineChart[canvasId].destroy();
	}

	if(days.length)
	{
		var thisTimelineChart = new Chart($(canvasId).get(0), {
			type: 'line',
			data: data,
			options: options
		});

		thisTimelineChart.update();

		globalVar.timelineChart[canvasId] = thisTimelineChart;
	}
}

function chartRemoveData(chart) 
{
    chart.data.labels.pop();
    chart.data.datasets.forEach((dataset) => 
    {
        dataset.data.pop();
    });

    chart.update();
}

function attrJsonStringToArray(jsonString)
{
	var outcomeArray = [];

	var jsonStringArray = jsonString.split('}');

	$(jsonStringArray).each(function(index, value)
	{
		if(value.indexOf('{') >= 0)
		{
			value = value.replace(',{', '{');
			value = value + '}';
			value = JSON.parse(value);
			outcomeArray.push(value);
		}		
	});

	return outcomeArray;
}

function tabDatatableInit(itemName = null, itemNameCarrier = null)
{
	var item = itemName != null ? itemName : $('#' + itemNameCarrier).attr('item').toLowerCase();

	if($('.table.display').get(0))
	{
		$('.table.display').each(function(index, ui)
		{
			var tableId = '#' + $(ui).attr('id');
			var dataUrl = $(ui).attr('dataurl');
			var tableColumns = $(ui).attr('datacolumn');
			var columnButtons = $(ui).attr('databtn');	
			var perPage = $(ui).attr('perpage');	
			var pagination = typeof $(ui).attr('pagination') == 'undefined' ? true : false;
			var processing = typeof $(ui).attr('processing') == 'undefined' ? true : false;
			var bulk = typeof $(ui).attr('bulk') == 'undefined' ? true : false;

			var table = datatableInit(item, tableId, dataUrl, tableColumns, columnButtons, perPage, pagination, processing, bulk);
			
			globalVar.dataTable[tableId] = table;

			if(index == 0 && tableId == '#datatable')
			{
				globalVar.jqueryDataTable = table;
			}			
		});
	}
}

function datatableInit(item, tableId, dataUrl, tableColumns, columnButtons, perPage, pagination = true, processing = true, bulk = true)
{
	tableColumns = attrJsonStringToArray(tableColumns);
	columnButtons = columnButtons != '' ? attrJsonStringToArray(columnButtons) : [];

	var paginationHtml = pagination ? 'ip' : '';
	var bulkHtml = bulk ? "<'bulk'>" : '';
	var upperHtml = pagination ? bulkHtml + "lBf<'custom-filter'>" : '';
	upperHtml = columnButtons != '' ? upperHtml : "lf<'custom-filter'>";

	var table =
	$(tableId).on('init.dt', function()
	{

		$('[data-toggle="tooltip"]').tooltip();
		$('html').getNiceScroll().resize();
		$(tableId + ' .pretty').find('input').prop('checked', false);
		$('.select-type-single').select2();
		$('.select-type-single-b').select2({ 'minimumResultsForSearch' : -1 });

	}).DataTable({
			'dom'           : "<"+upperHtml+"r<'table-responsive't>"+paginationHtml+">",
			'buttons'		: [
								{
				            		'extend'	: 'collection',
				            		'text'		: 'EXPORT',
				            		'buttons'	: ['excel', 'csv', 'pdf', 'print']
			            	 	},
	            	 	        {
	            	 	            'extend'	: 'collection',
	            	 	            'text' 		: "<i class='fa fa-eye-slash'></i>",
	            	 	            'buttons'	: columnButtons,
	            	 	            'fade' 		: true
	            	 	        },
			            	 	{
	            	 	            'text'		: "<i class='fa fa-refresh'></i>",
	            	 	            'action'	: function(e, dt, node, config)
					            	 	          {
					            	 	          	dt.ajax.reload();
					            	 	          }
	            	 	        }
			            	  ],
			'paging'		: pagination,            	  
			'pageLength'    : perPage,
			'lengthMenu'    : [10, 25, 50, 75, 100],
			'language'      : {
			                    'paginate'  : {
			                                    'previous'  : "<i class='fa fa-angle-double-left'></i>",
			                                    'next'      : "<i class='fa fa-angle-double-right'></i>"
			                                  },
			                    'info'      : '_START_ - _END_ / _TOTAL_',
			                    'lengthMenu': '_MENU_',
			                    'search'    : '_INPUT_',
			                    'searchPlaceholder' : 'Search',
			                    'infoFiltered': '',
			                    'sProcessing' : ''    
			                  },
			'order'			: [],
			'processing'	: processing,
			'serverSide'	: true,
			'ajax'			: { 
								'url'	: globalVar.baseAdminUrl + '/' + dataUrl, 
								'type'	: 'POST',
								'data'	: function(d)
										  {
										  	$(tableColumns).each(function(index, value)
										  	{
										  		var datatableWrapper = $(tableId).closest('.dataTables_wrapper');
										  		d.globalSearch = (datatableWrapper.length && datatableWrapper.find('.dataTables_filter').get(0)) ? datatableWrapper.find(".dataTables_filter input[type='search']").val() : '';											  		
										  		var filterInput = value.data;
										  		var filterInputId = '#' + item + '-' + filterInput;

										  		console.log(filterInputId);

									  			if(filterInput != 'checkbox' && filterInput != 'action')
									  			{
									  				d[filterInput] = $(filterInputId).get(0) ? $(filterInputId).val() : '';
									  			}											  			
										  	});												  	
										  }
							  },
			'columns'		: tableColumns,
			'fnDrawCallback': function(oSettings)
							  {
							  	$('[data-toggle="tooltip"]').tooltip();
							  	$('html').getNiceScroll().resize();
							  	$(tableId + ' .pretty').find('input').prop('checked', false);
							  	$('div.bulk').hide();
							  }			  
	});

	var customFilter = $(tableId).closest('.dataTables_wrapper').parent().find('.table-filter');

	if(customFilter.length)
	{
		$(tableId).closest('.dataTables_wrapper').find('.custom-filter').html('');

		customFilter.find('select').each(function(index, ui)
		{
			$(tableId).closest('.dataTables_wrapper').find('.custom-filter').append($(ui));
		});
		
		select2PluginInit();

		$(tableId).closest('.dataTables_wrapper').find('.custom-filter select').change(function()
		{
			table.draw();
		});
	}

	return table;
}

function posionableDatatableInit(tableId, dataUrl, tableColumns, unchecked = null)
{
	if(typeof globalVar.dataTable[tableId] != 'undefined')
	{
		globalVar.dataTable[tableId].destroy();
	}

	tableColumns = attrJsonStringToArray(tableColumns);
	
	var table =
	$(tableId).DataTable(
	{
		'dom'           : "<'full paging-false'r<'table-responsive zero-distance't>>",
		'paging'		: false,
		'order'			: [],
		'processing'	: true,
		'oLanguage'		: {sProcessing: ''},
		'serverSide'	: true,
		'ajax'			: { 'url' : dataUrl, 'type' : 'POST' },
		'columns'		: tableColumns,
		'rowReorder'	: { 'update' : false },				  
		'fnDrawCallback': function(oSettings)
						  {
						  	$(tableId).closest('.form-group').css('min-height', 'auto');
						  	$(tableId + ' tbody').fadeIn(550);
						  	$('[data-toggle="tooltip"]').tooltip();

						  	if(unchecked != null)
						  	{
						  		var uncheckedIds = unchecked[0];
						  		var uncheckedSelector = unchecked[1];

						  		$.each(uncheckedIds, function(key, id)
						  		{
						  			$(tableId).find(uncheckedSelector + "[value='"+ id +"']").prop('checked', false);
						  		});		
						  	}					  	
						  }			  
	});

	globalVar.dataTable[tableId] = table;
}

function bulkChecked(tBody, selectAllId, inputSingleRow, itemSingular, itemPlural)
{
    $(tBody).on('click.dt', inputSingleRow, function(event)
    {
    	event.stopPropagation();
        var checked = $(this).prop('checked');
        var tbody = $(this).closest('tbody');
        var bulk = $(this).closest('.dataTables_wrapper').find('.bulk');
        var rowsCount = 0;
        var selectionText = ' ' + itemSingular + ' Selected';
        var totalRows = tbody.find(inputSingleRow).size();

        if(checked == true)
        {                   
            rowsCount = tbody.find(inputSingleRow+':checked').size();
            
            if(rowsCount > 1)
            {
                selectionText = ' ' + itemPlural + ' Selected';
            }
            selectionText = rowsCount + selectionText;
            $('div.bulk .selection').html(selectionText);

            if(rowsCount == totalRows)
            {
                $(selectAllId).find('input').prop('checked', true);
            }

            bulk.fadeIn();
        }
        else
        {
            rowsCount = tbody.find(inputSingleRow+':checked').size();

            $(selectAllId).find('input').prop('checked', false);

            if(rowsCount == 0)
            {                       
                bulk.hide();
            }

            if(rowsCount > 1)
            {
                selectionText = ' ' + itemPlural + ' Selected';
            }
            selectionText = rowsCount + selectionText;
            $('div.bulk .selection').html(selectionText);
        }
    });

    $(selectAllId).click(function()
    {
        $('[data-toggle="tooltip"]').tooltip('hide');
        var checked = $(this).find('input').prop('checked');
        var table = $(this).closest('table');
        var bulk = $(this).closest('.dataTables_wrapper').find('.bulk');
        var rowsCount = 0;
        var selectionText = ' ' + itemSingular + ' Selected';

        if(checked == true)
        {
            table.find(inputSingleRow).prop('checked', true);

            rowsCount = table.find(inputSingleRow+':checked').size();
            if(rowsCount > 1)
            {
                selectionText = ' ' + itemPlural + ' Selected';
            }
            selectionText = rowsCount + selectionText;
            $('div.bulk .selection').html(selectionText);
            bulk.fadeIn();
        }
        else
        {
            table.find(inputSingleRow).prop('checked', false);
            bulk.hide();

            rowsCount = table.find(inputSingleRow+':checked').size();
            if(rowsCount > 1)
            {
                selectionText = ' ' + itemPlural + ' Selected';
            }
            selectionText = rowsCount + selectionText;
            $('div.bulk .selection').html(selectionText);
        }
    });

    $(selectAllId).mouseleave(function()
    {
        $('[data-toggle="tooltip"]').tooltip('hide');
    });
}

function openCommonCreateModal(modalId, contentTag, thisBtn)
{
	var action = thisBtn.attr('data-action');
	var content = thisBtn.attr('data-content');
	var defaultData = typeof thisBtn.attr('data-default') !== 'undefined' ? thisBtn.attr('data-default') : null;
	var showData = typeof thisBtn.attr('data-show') !== 'undefined' ? thisBtn.attr('data-show') : null;
	var hideData = typeof thisBtn.attr('data-hide') !== 'undefined' ? thisBtn.attr('data-hide') : null;
	var freezeData = typeof thisBtn.attr('data-freeze') !== 'undefined' ? thisBtn.attr('data-freeze') : null;
	var formData = { formAction : action, viewContent : content, viewType : 'create', default : defaultData, showField : showData, hideField : hideData, freezeField : freezeData };
	var modalDataTable = typeof thisBtn.attr('modal-datatable') !== 'undefined' ? true : false;
	var dataTableUrl = typeof thisBtn.attr('datatable-url') !== 'undefined' ? thisBtn.attr('datatable-url') : null;
	var modalSize = typeof thisBtn.attr('data-modalsize') !== 'undefined' ? thisBtn.attr('data-modalsize') : null;
	var title = typeof thisBtn.attr('modal-title') === 'undefined' ? 'Add New ' + thisBtn.attr('data-item') : thisBtn.attr('modal-title');
	title += typeof thisBtn.attr('modal-sub-title') === 'undefined' ? '' : " <span class='shadow bracket'>" + thisBtn.attr('modal-sub-title') + "</span>";

	$(modalId + ' .save').show();
	$(modalId + ' .save-new').show();
	if(typeof thisBtn.attr('save-new') !== 'undefined')
	{
	    if(thisBtn.attr('save-new') == 'false-all')
	    {
	    	$(modalId + ' .save-new').hide();
	    	$(modalId + ' .save').hide();
	    }
	    else if(thisBtn.attr('save-new') == 'false')
	    {
	    	$(modalId + ' .save-new').hide();
	    }
	}

	$(modalId + ' .save').html('Save');
	if(typeof thisBtn.attr('save-txt') !== 'undefined')
	{
	    $(modalId + ' .save').html(thisBtn.attr('save-txt'));
	}

	$(modalId + ' .cancel').html('Cancel');
	if(typeof thisBtn.attr('cancel-txt') !== 'undefined')
	{
	    $(modalId + ' .cancel').html(thisBtn.attr('cancel-txt'));
	}

	$(modalId + ' .modal-footer').show(); 
	if(typeof thisBtn.attr('modal-footer') !== 'undefined')
	{
	    $(modalId + ' .modal-footer').hide();
	}

	if(modalSize == null)
	{
	    $(modalId).removeClass('medium');
	    $(modalId).removeClass('sub');

	    if(!$(modalId).hasClass('large'))
	    {                        
	        $(modalId).addClass('large');
	    }                    
	}
	else
	{
	    $(modalId).removeClass('large');
	    $(modalId).addClass(modalSize);
	}

	if(typeof thisBtn.attr('modal-files') !== 'undefined')
	{
	    $(modalId + ' form').attr('enctype', 'multipart/form-data');
	}

	$(modalId + ' form').trigger('reset');
	$(modalId + ' form').find('.select2-hidden-accessible').trigger('change'); 
	$(modalId + ' .processing').html('');
	$(modalId + ' .processing').hide();
	$(modalId + ' span.validation-error').html('');
	$(modalId + ' ' + contentTag).hide();
	$(modalId + ' .modal-loader').show();
	$(modalId + ' .modal-title').html(title);

	$(modalId).modal({
	    show : true,
	    backdrop: false,
	    keyboard: false
	});

	$.ajax(
	{
	    type    : 'GET',
	    url     : globalVar.baseAdminUrl + '/view-content',
	    data    : formData,
	    success : function(data)
	              {
	                $dataObj = $(data.html);

	                if(data.status == true && $dataObj.length)
	                {
	                    $(modalId + ' form').attr('action', action);                                    
	                    $(modalId + ' ' + contentTag).html($dataObj); 

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

	                    var imageLeft = $(modalId + ' .modal-title').width() + 40;
	                    $(modalId + ' .modal-image').css('left', imageLeft + 'px');

	                    modalCurrency($(thisBtn.closest('.has-currency-info')), modalId);

	                    var ps = new PerfectScrollbar(modalId + ' .modal-body');
	                    var hide = '';
	                    var show = '';
	                    
	                    pluginInit();

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
	                                hide += modalId + " label[for='"+val+"'],";   
	                            });

	                            hide = hide.slice(0,-1);
	                        }             
	                    });

	                    $(modalId + ' .modal-body').animate({ scrollTop: 1 });
	                    $(show).closest('.none').show();
	                    $(show).closest('.form-group').show();
	                    $(hide).closest('.form-group').hide();                                    
	                    $(modalId + ' ' + contentTag).slideDown();
	                    $(modalId + ' .modal-body').animate({ scrollTop: 0 });
	                    $(modalId + ' .modal-loader').fadeOut(1000);
	                    $(modalId + ' input[data-focus="true"]').focus();

	                    if(modalDataTable)
	                    {
	                        var item = $('#modal-datatable').attr('data-item');
	                        var dataUrl = dataTableUrl != null ? dataTableUrl : $('#modal-datatable').attr('data-url');
	                        var tableColumns = $('#modal-datatable').attr('data-column');
	                        var modalTable = datatableInit(item, '#modal-datatable', dataUrl, tableColumns, '', 10);
	                        
	                        globalVar.jqueryModalDataTable = modalTable;

	                        var selectAllId = '#' + $('#modal-datatable').find('.select-all').attr('id');
	                        var itemSingular = $('#modal-datatable').attr('data-item');
	                        var itemPlural = itemSingular + 's';
	                        
	                        bulkChecked('#modal-datatable tbody', selectAllId, 'input.single-row', itemSingular, itemPlural);
	                    }
	                }   
	              },
	    error   : function(jqXHR, textStatus, errorThrown)
	              {
	                location.reload();
	              }              
	});
}

$.fn.setCursorPosition = function(position)
{
	this.each(function(index, input)
	{
		if (input.setSelectionRange)
		{
			input.setSelectionRange(position, position);
		} 
		else if(input.createTextRange)
		{
			var range = input.createTextRange();
			range.collapse(true);
			range.moveEnd('character', position);
			range.moveStart('character', position);
			range.select();
		}
	});

	return this;
};