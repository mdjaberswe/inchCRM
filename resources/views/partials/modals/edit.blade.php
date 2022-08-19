<div class='modal fade {!! $page['modal_size'] or 'large' !!}' id='edit-form'>
    <div class='modal-dialog'>
    	<div class='modal-loader'>
    		<div class='spinner'></div>
    	</div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
    	        @if(isset($page['modal_title_link']) && $page['modal_title_link'] == true)
    	        	<h4 id='modal-title' class='modal-title'><a href=''></a></h4>
    	        @else    	        
    	        	<h4 class='modal-title'>Edit {!! $page['item'] !!}</h4>
    	        @endif	
    	    </div> <!-- end modal-header -->

    	    @if(!isset($yield) || (isset($yield) && $yield == true))
    	    	@yield('modaledit')
    	    @else
	    		{!! Form::open(['route' => [$page['route'] . '.update', null], 'method' => 'put', 'class' => 'form-type-a']) !!}
	    		    @include($page['view'] . '.partials.form', ['form' => 'edit'])
	    		{!! Form::close() !!}
	    	@endif	

	 		<div class='modal-footer space btn-container'>
	 		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	 		    @if(isset($page['modal_footer_delete']) && $page['modal_footer_delete'] == true)
		 		    {!! Form::open(['route' => null, 'method' => 'delete', 'class' => 'inline-block-left', 'id' => 'modal-footer-delete', 'data-item' => $page['item']]) !!}
		 		    	{!! Form::hidden('id', null) !!}
		 		    	<button type='submit' class='modal-delete btn btn-danger'>Delete</button>
		 		    {!! Form::close() !!}
	 		    @endif
	 		    <button type='button' class='save btn btn-info'>Save</button>
	 		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end edit-form -->

@push('scripts')
	<script>
		$(document).ready(function()
		{
			$('#edit-form .save').click(function()
			{				
				var form = $(this).parent().parent().find('form');
				modalDataUpdate(form);
			});
		});

		function modalDataUpdate(form)
		{
			$('#edit-form .processing').html("<div class='loader-ring-sm'></div>");
			$('#edit-form .processing').show();			    

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
			                	$('#edit-form span.validation-error').html('');
			                    $('#edit-form .processing').html("<span class='fa fa-check-circle success'></span>");				                    
		                    	delayModalHide('#edit-form', 1);

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

    		                    if($('.funnel-container').get(0))
    		                    {
    		                    	if(typeof data.kanbanCardRemove != 'undefined' && data.kanbanCardRemove != false)
    		                    	{
    		                    		$.each(data.kanbanCardRemove, function(index, cardId)
    		                    		{
    		                    		    $('.funnel-stage #' + cardId).remove();
    		                    		});

    		                    		kanbanCountResponse(data);
    		                    	}
    		                    	else
    		                    	{
    		                    		kanbanUpdateResponse(data);
    		                    	}    		                    	
    		                    }

    		                    if(typeof data.realtime != 'undefined')
    		                    {
    		                        $.each(data.realtime, function(index, value)
    		                        {
    		                            $("*[data-realtime='"+index+"']").html(value);
    		                        });
    		                    }    		                       
			                }
			                else
			                {
			                	$('#edit-form span.validation-error').html('');
			                	$.each(data.errors, function(index, value)
			                	{
			                		$("#edit-form span[field='"+index+"']").html(value);
			                	});
			                	$('#edit-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
			                }
			              }
			});
		}

		function getEditData(id, data, url, updateUrl)
		{	
			// reset to default values
			$('#edit-form form').trigger('reset');
			$('#edit-form form').find('.select2-hidden-accessible').trigger('change');

			$('#edit-form .processing').html('');
			$('#edit-form .processing').hide();
			$('#edit-form span.validation-error').html('');
			$('#edit-form .save').hide();
			$('#edit-form .form-group').hide();
			$('#edit-form .modal-loader').show();

			if($('#edit-form .toggle-permission').get(0))
			{
				$('#edit-form .child-permission').css('opacity', 1);
				$('#edit-form .child-permission').find('input').attr('disabled', false);
				$('#edit-form .child-permission').find("input[data-default='true']").prop('checked', true);
			}	

			if($('#edit-form .modal-image').get(0))
			{
				var defaultImage = $('#edit-form .modal-image').data('image');
				$('#edit-form .modal-image img').hide();
				$('#edit-form .modal-image img').attr('src', defaultImage);
			}

			if($('#edit-form .posionable-datatable').get(0))
			{
				var posionableDataUrl = $('#edit-form .posionable-datatable').data('url') + '/' + id;
				$('#edit-form .posionable-datatable').attr('data-url', posionableDataUrl);
				var tableId = '#' + $('#edit-form .posionable-datatable').attr('id');
				var dataUrl = $('#edit-form .posionable-datatable').attr('data-url');
				var tableColumns = $('#edit-form .posionable-datatable').attr('data-column');
				posionableDatatableInit(tableId, dataUrl, tableColumns);
			}

			$('#edit-form').modal({
                show : true,
                backdrop: false,
                keyboard: false
            });

            var imageLeft = $('#edit-form .modal-title').width() + 50;
            $('#edit-form .modal-image').css('left', imageLeft + 'px');

			$.ajax(
			{
				type 	: 'GET',
				url 	: url,
				data 	: data,
				success	: function(data)
						  {
							if(data.status == true)
							{
								$('#edit-form form').prop('action', updateUrl);
								$("#edit-form input[name='_method']").val('PUT');
								$('#edit-form form').find('select').prop('disabled', false);
								$('#edit-form form').find('input').prop('readOnly', false);

								var hide = '';
								var show = '';

								if(typeof data.info.selectlist != 'undefined')
								{
									$.each(data.info.selectlist, function(fieldName, options)
									{
										var selectlist = $("#edit-form select[name='"+fieldName+"']").empty();

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
									if($("#edit-form *[name='"+index+"']").get(0))
									{
										if($("#edit-form *[name='"+index+"']").is(':checkbox'))
										{
											if($("#edit-form *[name='"+index+"']").val() == value)
											{
												$("#edit-form *[name='"+index+"']").prop('checked', true);
											}
											else
											{
												$("#edit-form *[name='"+index+"']").prop('checked', false);
											}
										}
										else
										{
											if($("#edit-form *[name='"+index+"']").hasClass('white-select-type-multiple-tags'))
											{
												var tagSelect = $("#edit-form select[name='"+index+"']").empty();

												$(value).each(function(index, optVal)
												{
													$('<option/>', {
											            value: optVal,
											            text: optVal
											        }).appendTo(tagSelect); 
												});
											}

											$("#edit-form *[name='"+index+"']").not(':radio').val(value).trigger('change');
										}

										if($("#edit-form *[name='"+index+"']").is(':radio'))
										{
											$("#edit-form *[name='"+index+"']").each(function(index, obj)
											{
												if($(obj).val() == value)
												{
													$(obj).prop('checked', true);
												}
												else
												{
													$(obj).prop('checked', false);
												}
											});
										}
									}

									if(index == 'freeze')
									{
										$.each(value, function(key, val)
										{
											if($("#edit-form *[name='"+val+"']").is('select') || $("#edit-form *[name='"+val+"']").is(':radio'))
											{
											    $("#edit-form *[name='"+val+"']").prop('disabled', true);
											    $("#edit-form *[name='"+val+"']").closest('.child-permission').css('opacity', 0.5);
											}
											else
											{
												$("#edit-form *[name='"+val+"']").prop('readOnly', true);
											}
										});	
									}

									if(index == 'show')
									{
										$.each(value, function(key, val)
										{	
											show += "#edit-form *[name='"+val+"'],";
										});

										show = show.slice(0,-1);
									}

									if(index == 'hide')
									{
										$.each(value, function(key, val)
										{
											$("#edit-form ."+val+"-input").hide();
											hide += '.'+val+'-input'+',';
										});

										hide = hide.slice(0,-1);
									}	

									if(index == 'modal_title_link')
									{
										if($('#modal-title').get(0))
										{
											$('#modal-title a').html(value.title);
											$('#modal-title a').attr('href', value.href);
										}
									}	

									if(index == 'modal_image')
									{
										$('#edit-form .modal-image img').attr('src', value);
										$('#edit-form .modal-image img').fadeIn(1500);
									}									

									if(index == 'modal_footer_delete')
									{
										if($('#modal-footer-delete').get(0))
										{											
											$('#modal-footer-delete').attr('action', value.action);
											$('#modal-footer-delete input[name="id"]').val(value.id);
										}
									}							
								});

								$('#edit-form .datepicker').each(function(index, value)
								{
									$(this).datepicker('update', $(this).val());
								});

								$('#edit-form .modal-loader').fadeOut(1000);
								$('#edit-form .modal-body').animate({ scrollTop: 1 });

								if(typeof data.specShow != 'undefined')
								{
									$('#edit-form .parent-show').show();
									$(show).closest('.none').show().css('opacity', 0.5).animate({opacity: 1});
									$($(show).closest('.form-group')).slideDown('slow');	
									$($(show).closest('.none')).find('.form-group').not('.none').slideDown('slow');																			
								}
								else
								{
									$(show).closest('.none').css('opacity', 0.5).slideDown('slow').animate({opacity: 1});
									$('#edit-form .form-group').not(hide).css('opacity', 0).slideDown('slow').animate({opacity: 1});
								}

								$('#edit-form .modal-body').animate({ scrollTop: 0 });	
								$('#edit-form .save').show();
							}
							else
							{
								$('#edit-form .modal-loader').fadeOut(1000);
								$('#edit-form .form-group').css('opacity', 0).slideDown('slow').animate({opacity: 1});
								$('#edit-form .processing').show();
								$('#edit-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
								delayModalHide('#edit-form', 2);
							}
						  }
			});
		}
	</script>
@endpush