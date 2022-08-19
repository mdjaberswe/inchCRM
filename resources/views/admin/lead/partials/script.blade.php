<script>
	$(document).ready(function()
	{
		$('#datatable tbody').on('click.dt', '.convert', function(event)
		{
			var id = $(this).attr('editid');
			var tr = $(this).closest('tr');
			convertLeadModal(id, tr);
		});

		$('.convert').click(function()
		{
			var id = $(this).attr('editid');
			var tr = $(this).closest('.div-panel-header');
			convertLeadModal(id, tr);
		});

		$(document).on('click', '.convert-kanban', function(e)	
		{
			var id = $(this).attr('editid');
			var tr = $(this).closest('.funnel-card');
			convertLeadModal(id, tr);
		});
	});

	function convertLeadModal(id, tr)
	{		
		var data = {'id' : id};			
		var trObj = $(tr);
		var url = globalVar.baseAdminUrl + '/lead-data/' + id + '/convert';
		var convertUrl = globalVar.baseAdminUrl + '/convert-lead/' + id;
		var leadName = tr.find('.lead-name').html();
		var companyName = tr.find('.company-name').html();
		if(typeof leadName != 'undefined' && typeof companyName != 'undefined')
		{
			companyName = companyName == '' ? companyName : ' - ' + companyName;
			var leadCompany = leadName + companyName;
			$('#convert-lead-form .modal-title .shadow').html(leadCompany);
		}

		// reset to default values
		$('#convert-lead-form form').trigger('reset');
		$('#convert-lead-form form').find('.select2-hidden-accessible').trigger('change');

		$('#convert-lead-form .processing').html('');
		$('#convert-lead-form .processing').hide();
		$('#convert-lead-form span.validation-error').html('');
		$('#convert-lead-form .convert-btn').hide();
		$('#convert-lead-form .form-group').hide();
		$('#convert-lead-form .none').hide();
		$('#convert-lead-form .block').show();
		$('#convert-lead-form .modal-loader').show();
		$('#convert-lead-form').modal({
            show : true,
            backdrop: false,
            keyboard: false
    	});

		$.ajax(
		{
			type 	: 'GET',
			url 	: url,
			data 	: data,
			dataType: 'JSON',
			success	: function(data)
					  {
						if(data.status == true)
						{
							$('#convert-lead-form form').prop('action', convertUrl);
							$("#convert-lead-form input[name='_method']").val('POST');

							var hide = '';

							$.each(data.info, function(index, value)
							{
								if($("#convert-lead-form *[name='"+index+"']").get(0))
								{
									$("#convert-lead-form *[name='"+index+"']").not(':radio').val(value).trigger('change');
								}		

								if(index == 'hide')
								{
									$.each(value, function(key, val)
									{
										$("#convert-lead-form ."+val+"-input").hide();
										hide += '.'+val+'-input'+',';
									});

									hide = hide.slice(0,-1);
								}				
							});

							modalCurrency(trObj, '#convert-lead-form');

							$('#convert-lead-form .modal-loader').fadeOut(1000);
							$('#convert-lead-form .form-group').not(hide).css('opacity', 0).slideDown('slow').animate({opacity: 1});
							$('#convert-lead-form .convert-btn').show();
						}
						else
						{
							$('#convert-lead-form .modal-loader').fadeOut(1000);
							$('#convert-lead-form .form-group').css('opacity', 0).slideDown('slow').animate({opacity: 1});
							$('#convert-lead-form .processing').show();
							$('#convert-lead-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
							delayModalHide('#convert-lead-form', 2);
						}
					  }
		});
	}
</script>	