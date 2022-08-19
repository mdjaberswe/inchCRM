<script>
	$(document).ready(function()
	{
		$('.lead-score-slider').click(function()
		{
			// reset to default values
			$('#classify-lead-score form').trigger('reset');
			$('#classify-lead-score form').find('.select2-hidden-accessible').trigger('change');

			$('#classify-lead-score .processing').html('');
			$('#classify-lead-score .processing').hide();
			$('#classify-lead-score span.validation-error').html('');
			$('#classify-lead-score .form-group').hide();
			$('#classify-lead-score .modal-loader').show();
			$('#classify-lead-score').modal({
                show : true,
                backdrop: false,
                keyboard: false
            });

			$.ajax(
			{
				type 	: 'GET',
				url 	: '{!! route('admin.classify.lead.score') !!}',
				data 	: {'classify' : true },
				dataType: 'JSON',
				success	: function(data)
						  {
							if(data.status == true)
							{
								$.each(data.info, function(index, value)
								{
									if($("#classify-lead-score *[name='"+index+"']").get(0))
									{
										$("#classify-lead-score *[name='"+index+"']").not(':radio').val(value).trigger('change');
									}	

									if(index == 'realtime')
									{
										$.each(value, function(key, text)
										{
											$("#classify-lead-score ." + key).html(text);
										});
									}		
								});

								var start = parseInt(data.info.range_start);
								var end = parseInt(data.info.range_end);
								initSliderRange(start, end);

								$('#classify-lead-score .modal-loader').fadeOut(1000);
								$('#classify-lead-score .form-group').css('opacity', 0).slideDown('slow').animate({opacity: 1});
							}
							else
							{
								$('#classify-lead-score .modal-loader').fadeOut(1000);
								$('#classify-lead-score .form-group').css('opacity', 0).slideDown('slow').animate({opacity: 1});
								$('#classify-lead-score .processing').show();
								$('#classify-lead-score .processing').html("<span class='fa fa-exclamation-circle error'></span>");
								delayModalHide('#classify-lead-score', 2);
							}
						  }
			});
		});
	});
</script>	