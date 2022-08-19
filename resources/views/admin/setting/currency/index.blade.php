@extends('templates.listing-posionable')

@push('scripts')
	<script>
		$(document).ready(function()
		{
			$('#add-new-btn').click(function()
			{
				var baseCode = $('#datatable').find('.status').parent().next('td').text();
				$('.base-code').html(baseCode);
				$('.face-code').html('');
			});	

			$('#datatable tbody').on('click.dt', '.edit', function(event)
			{
				var baseCode = $('#datatable').find('.status').parent().next('td').text();
				$('.base-code').html(baseCode);
			});	

			$('#datatable tbody').on('click.dt', '.make-base', function(event)
			{
				var id = $(this).attr('editid');
				var data = {'id' : id};
				var updateUrl = globalVar.baseAdminUrl + '/update-base-currency/' + id;

				$.ajax(
				{
				    type    : 'POST',
				    url     : updateUrl,
				    data    : data,
				    dataType: 'JSON',
				    success : function(data)
				              {
				                if(data.status == true)
				                {
				                	globalVar.jqueryDataTable.ajax.reload(null, false);
				                	$.notify({ message: 'Base currency has been changed' }, globalVar.successNotify);
				                }
				                else
				                {
			                		$.notify({ message: 'Something went wrong.' }, globalVar.dangerNotify);
				                }
				              }
				});
			});	

			$('.currency-code').on('keyup keydown blur change', function()
			{
				var code = $(this).val();
				var codeSize = code.length;
				var baseVal = $($(this).closest('form')).find("input[name='base']").val();

				$(this).val(code.toUpperCase());
				if(codeSize <= 3)
				{
					$('.face-code').html(code.toUpperCase());	

					if(baseVal == '1')
					{
						$('.base-code').html(code.toUpperCase());
					}				
				}
				else
				{
					$(this).val(code.slice(0, 3));
				}
			});
		});
	</script>
@endpush		