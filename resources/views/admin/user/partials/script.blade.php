<script>
	$(document).ready(function()
	{
		$('#datatable tbody').on('click.dt', '.switch', function(event)
		{
			var thisSwitch = $(this);
			var input = $(this).find('input');
			changeStatus(thisSwitch, input);
		});

		$('.div-type-k .switch').click(function()
		{
			var thisSwitch = $(this);
			var input = $(this).find('input');
			changeStatus(thisSwitch, input);
		});

		$('#datatable tbody').on('click.dt', '.change-password', function(event)
		{
			var id = $(this).attr('editid');
			var updateUrl = globalVar.baseAdminUrl + '/user-password/' + id;
			var userName = $(this).closest('tr').find('.user-name').html();
			if(typeof userName != 'undefined')
			{
				$('#change-password-form .modal-title .shadow').html(userName);
			}

			$('#change-password-form form').trigger('reset');
			$('#change-password-form form').prop('action', updateUrl);
			$("#change-password-form input[name='id']").val(id);
			$('#change-password-form .processing').html('');
			$('#change-password-form .processing').hide();
			$('#change-password-form span.validation-error').html('');
			$('#change-password-form .modal-body').animate({ scrollTop: 0 });
			$('#change-password-form').modal();
		});

		function changeStatus(thisSwitch, input)
		{
			if(!thisSwitch.hasClass('disabled'))
			{
				var id = input.val();
				var checked = input.prop('checked') ? 1 : 0;
				
				$.ajax({
					type 	: 'POST',
					url 	: globalVar.baseAdminUrl + '/user-status/' + id,
					data 	: { id : id, checked : checked },
					dataType: 'JSON',
					success	: function(data)
							  {
							  	if(data.status == true)
							  	{
							  		var updateStatus = 'Inactive';
							  		if(data.checked)
							  		{
							  			updateStatus = 'Active';
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
	});
</script>	