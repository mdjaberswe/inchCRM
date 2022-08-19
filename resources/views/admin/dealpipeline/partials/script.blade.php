<script>
	$(document).ready(function()
	{
		$('.add-pipeline-stage').click(function(e)
		{
			var select = $(this).closest('.form-group').find('select');
			var table = $(this).closest('form').find('.posionable-datatable');
			var tableId = '#' + table.attr('id');
			var newStages = [];
			var openStages = [];
			var closedStages = [];
			var trStages = table.find('input[name="positions[]"]').map(function()
								 {
								 	return $(this).val();
								 }).get();

			var notCheckedForecast = table.find('input[name="forecast[]"]:not(:checked)').map(function()
										   {
											return $(this).val();
										   }).get();

			$.each(select.val(), function(key, id)
			{
				if($.inArray(id, trStages) == -1)
				{
					var category = select.find("option[value='"+ id +"']").attr('category');
					if(category == 'open')
					{
						openStages.push(id);
					}
					else
					{
						closedStages.push(id);
					}	

					newStages.push(id);
				}
			});

			if(newStages.length)
			{
				trStages = $.merge(openStages, trStages);
				trStages = $.merge(trStages, closedStages);

				$(tableId).closest('.form-group').css('min-height', parseInt($(tableId).outerHeight(false)) + 'px');
				$(tableId + ' tbody').hide();
				var idStr = trStages.join('_');
				var dataUrl = table.data('url') + '/0/' + idStr;
				var tableColumns = table.attr('data-column');
				var unchecked = [notCheckedForecast, "input[name='forecast[]']"];
				posionableDatatableInit(tableId, dataUrl, tableColumns, unchecked);
			}

			select.val('');
			$(this).closest('.form-group').find('.select2-hidden-accessible').trigger('change');
		});
	});
</script>		