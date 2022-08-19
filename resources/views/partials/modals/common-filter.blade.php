<div class='modal fade large' id='common-filter'>
    <div class='modal-dialog'>
    	<div class='modal-loader'>
    		<div class='spinner'></div>
    	</div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
   	        	<h4 class='modal-title capitalize'>Filter Data</h4>
    	    </div> <!-- end modal-header -->

    		{!! Form::open(['route' => ['admin.filter.form.post', null], 'method' => 'post', 'class' => 'form-type-a']) !!}
    		    <div id='common-filter-content' class='full'></div>
    		{!! Form::close() !!}

	 		<div class='modal-footer space btn-container'>
	 		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	 		    <button type='button' class='save-as-view btn btn-info' data-item=''>Save as View</button>
	 		    <button type='button' class='submit btn btn-info'>Submit</button>
	 		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end common-filter-form -->

@push('scripts')
	<script>
		$(document).ready(function()
		{
			$(document).on('click', '.common-filter-btn', function(event)
			{
				var data = {'html' : true};
				var url = $(this).attr('data-url');
				var updateUrl = $(this).attr('data-posturl');
				var title = typeof $(this).attr('modal-title') === 'undefined' ? 'Filter ' + $(this).attr('data-item') + ' Data' : $(this).attr('modal-title');
				
				$('#common-filter #common-filter-content').hide();
				$('#common-filter .modal-title').html(title);
				$('#common-filter .save-as-view').attr('data-item', $(this).attr('data-item'));

				getCommonFilterData(data, url, updateUrl, '#common-filter');
			});

			$('#common-filter .submit').click(function()
			{				
				var form = $(this).parent().parent().find('form');
				modalCommonFilterUpdate(form, '#common-filter');
			});

			$(document).on('click', '.add-filter-field', function()
			{
				var modalBody = $(this).closest('.modal-body');
				var filterTable = modalBody.find('table');
				var filterFields = $(this).closest('.form-group').find('select');
				var filterFieldsVal = filterFields.val();

				if(filterFieldsVal.length)
				{
					$.each(filterFieldsVal, function(index, field)
					{
						if(filterTable.find("tr[data-field='"+ field +"']").hasClass('none'))
						{
							var defaultConditionVal = filterTable.find("tr[data-field='"+ field +"'] *[data-type='condition'] select option:first").val();
							filterTable.find("tr[data-field='"+ field +"'] *[data-type='condition'] select").val(defaultConditionVal);
							filterTable.find("tr[data-field='"+ field +"'] *[data-type='value'] input").val('');
							filterTable.find("tr[data-field='"+ field +"'] *[data-type='value'] select").val('');
							filterTable.find("tr[data-field='"+ field +"']").find('.select2-hidden-accessible').trigger('change');
							filterTable.find("tr[data-field='"+ field +"']").removeClass('none');
						}
					});

					filterFields.val('');
					$(this).closest('.form-group').find('.select2-hidden-accessible').trigger('change');
				}
			});

			$(document).on('click', '.remove-filter', function()
			{
				$(this).closest('tr').addClass('none');
			});

			$(document).on("change", "#common-filter table td[data-type='condition'] select", function()
			{
				var tr = $(this).closest('tr');
				var conditionVal = $(this).val();

				if(conditionVal == 'empty' || conditionVal == 'not_empty')
				{
					tr.find("td[data-type='value']").css('opacity', 0);
					tr.find("td[data-type='value'] input").attr('readOnly', true);
					tr.find("td[data-type='value'] select").attr('disabled', true);
				}
				else
				{
					tr.find("td[data-type='value']").css('opacity', 1);
					tr.find("td[data-type='value'] input").attr('readOnly', false);
					tr.find("td[data-type='value'] select").attr('disabled', false);
				}
			});
		});

		function modalCommonFilterUpdate(form, modalId)
		{
			$(modalId + ' .processing').html("<div class='loader-ring-sm'></div>");
			$(modalId + ' .processing').show();

			var table = globalVar.jqueryDataTable;
			var formUrl = form.prop('action');
			var selectedTr = form.find('table tbody tr').not('.none');

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

			var formData = { 'fields' : fields, 'conditions' : fieldConditions, 'values' : fieldValues };

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
		                    	}

		                    	if(typeof data.filterCount != 'undefined' && data.filterCount != null)
		                    	{
		                    		$(".common-filter-btn[data-item='"+data.module+"'] .num-notify").html(data.filterCount);
		                    	}

		                    	if(typeof data.customViewName != 'undefined' && data.customViewName == true)
		                    	{
		                    		var li = $(".breadcrumb-select[name='view']").closest('li');
		                    		li.addClass('prestar');
		                    		li.find('.breadcrumb-action').hide();
		                    		if(li.find('a.save-as-view').length == 0)
		                    		{
		                    			li.find('.view-btns').append("<a class='bread-link save-as-view' data-item='"+data.module+"'>Save as View</a>");
		                    		}		                    		            		
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

		function getCommonFilterData(data, url, updateUrl, modalId)
		{	
			// reset to default values
			$(modalId + ' form').trigger('reset');
			$(modalId + ' form').find('.select2-hidden-accessible').trigger('change');

			$(modalId + ' .processing').html('');
			$(modalId + ' .processing').hide();
			$(modalId + ' span.validation-error').html('');
			$(modalId + ' .save').attr('disabled', true);
			$(modalId + ' #common-filter-content').hide();
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
								    $(modalId + ' #common-filter-content').html($dataObj);
								}	

								pluginInit();
								perfectScrollbarInit();

								$(modalId + ' form').prop('action', updateUrl);
								$(modalId + ' form').trigger('reset');
								$(modalId + " form *[data-type='value'] input").val('');
								$(modalId + " form *[data-type='value'] select").val('');
								$(modalId + ' form').find('.select2-hidden-accessible').trigger('change');

								$.each(data.info, function(index, value)
								{
									if($(modalId + " *[name='"+index+"[]']").get(0))
									{
										index = index + '[]';
									}

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
											if($(modalId + " *[name='"+index+"']").hasClass('white-select-type-multiple-tags'))
											{
												var tagSelect = $(modalId + " select[name='"+index+"']").empty();

												$(value).each(function(index, optVal)
												{
													$('<option/>', {
											            value: optVal,
											            text: optVal
											        }).appendTo(tagSelect); 
												});
											}
											
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
								});

								$(modalId + ' .datepicker').each(function(index, value)
								{
									$(this).datepicker('update', $(this).val());
								});

								$(modalId + ' .modal-loader').fadeOut(1000);
								$(modalId + ' .modal-body').animate({ scrollTop: 1 }, 10);
								$(modalId + ' #common-filter-content').slideDown();
								$(modalId + ' .modal-body').animate({ scrollTop: 0 }, 10);							
								$(modalId + ' .save').attr('disabled', false);
							}
							else
							{
								$(modalId + ' .modal-loader').fadeOut(1000);
								$(modalId + ' .processing').show();
								$(modalId + ' .processing').html("<span class='fa fa-exclamation-circle error'></span>");
								delayModalHide(modalId, 2);
							}
						  }
			});
		}
	</script>
@endpush