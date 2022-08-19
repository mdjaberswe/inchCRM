<script>
	$(document).ready(function()
	{
		$('#datatable tbody').on('click.dt', '.role-users', function(event)
		{
			var id = $(this).attr('rowid');
			var roleName = $(this).closest('tr').find('.role-name').html();
			if(typeof roleName != 'undefined')
			{
				$('#role-users .modal-title').html('Users in ' + roleName + ' Role');
			}

			$('#role-users .modal-body').animate({ scrollTop: 0 });
			$('#role-users .modal-loader').show();
			$('#role-users .modal-body').hide();
			$('#role-users').modal();

			$.ajax(
			{
				type 	: 'POST',
				url 	: globalVar.baseAdminUrl + '/role-users/' + id,
				data 	: { id : id },
				success	: function(data)
						  {
						  	if(data.status == true)
						  	{						  		
						  		$('#role-users .modal-body').html(data.users);
						  		$('#role-users .modal-body').slideDown(200);
						  		$('#role-users .modal-loader').fadeOut(700);
						  	}
						  	else
						  	{
						  		$('#role-users .modal-loader').fadeOut(500);
						  		$('#role-users .modal-body').hide().html("<p class='center-lg'>Something went wrong.</p>").fadeIn(500);
						  		delayModalHide('#role-users', 2);
						  	}
						  }
			});			  	
		});

		$('.switch').not('.all').click(function()
		{
			var checked = $(this).find('input').prop('checked');

			if(checked == true)
			{
				$(this).parent().parent().find('.div-type-d').addClass('block');
				$(this).parent().parent().find('.div-type-d .para-type-b:first span').show();
				$(this).parent().parent().find('.div-type-e').find('input').prop('checked', true);

				// if all checked
				var currentChecked = $(this).closest('.permissions-group').find('input:checked').not('.all').size() + 1;
				var totalSwitchInput = $(this).closest('.permissions-group').find('input').not('.all').size();

				if(currentChecked >= totalSwitchInput)
				{
				    $(this).closest('.permissions-group').find('.all').find('input').prop('checked', true);
				}				
			}
			else
			{
				$(this).parent().parent().find('.div-type-d').removeClass('block');
				$(this).parent().parent().find('.div-type-e').slideUp(100);
				$(this).parent().parent().find('.div-type-e').find('input').prop('checked', false);
				$(this).closest('.permissions-group').find('.all').find('input').prop('checked', false);
			}
		});

		$('.all').click(function()
		{
			var checked = $(this).find('input').prop('checked');
			var allPermissionsContainer = $(this).closest('.permissions-group');

			if(checked == true)
			{
				allPermissionsContainer.find('.switch input').prop('checked', true);
				allPermissionsContainer.find('.div-type-d').addClass('block');
				allPermissionsContainer.find('.div-type-d .para-type-b span').show();
				allPermissionsContainer.find('.div-type-e').find('input').prop('checked', true);
			}
			else
			{
				allPermissionsContainer.find('.switch input').prop('checked', false);
				allPermissionsContainer.find('.div-type-d').removeClass('block');
				allPermissionsContainer.find('.div-type-e').slideUp(100);
				allPermissionsContainer.find('.div-type-e').find('input').prop('checked', false);
			}
		});

		$('main').click(function()
		{
			$('.div-type-e').slideUp(100);
		});

		$('main .div-type-d').click(function(e)
		{
			var thisDivPosition = parseInt($(this).offset().top) - parseInt($(this).closest('.div-type-a').offset().top);
			var containerDivHeight = $(this).closest('.div-type-a').height();
			var lowerGap = containerDivHeight - thisDivPosition;
			var comingDivHeight = $(this).find('.div-type-e').height() + 40;

			if(comingDivHeight > lowerGap)
			{
				$(this).find('.div-type-e').css('top', 'auto');
				$(this).find('.div-type-e').css('bottom', '100%');
			}

			e.stopPropagation();
			$('.div-type-e').not($(this).children('.div-type-e')).slideUp(100);
			$(this).find('.div-type-e').slideToggle(100);
		});

		$('.div-type-d .div-type-e').click(function(e)
		{
			e.stopPropagation();
		});

		$('.para-type-c input').change(function()
		{
			var permissionVal = $(this).val();
			var checked = $(this).prop('checked');
			var text = $(this).parent().find('span').html();
			var parent = $(this).attr('parent');
			var divTypeF = $(this).parent().parent();
			var divTypeE = divTypeF.parent();
			var divTypeD = divTypeE.parent();
			var divTypeB = divTypeD.parent();
			var permissionSummary = divTypeD.find("span[name='"+parent+"']");

			changePermissionEvent(permissionVal, permissionSummary, checked, text, divTypeF, divTypeE, divTypeD, divTypeB);
		});

		function changePermissionEvent(permissionVal, permissionSummary, checked, text, divTypeF, divTypeE, divTypeD, divTypeB)
		{
			if(checked == true)
			{
				if(text == 'View')
				{
					if(permissionVal == permissionSummary.attr('status'))
					{
						permissionSummary.show();
					}
					else
					{
						permissionSummary.show();
					}
				}
				else
				{
					permissionSummary.show();

					if(divTypeF.find('span').html() == 'View')
					{
						divTypeF.find('input:first').prop('checked', true);
					}
				}
			}
			else
			{
				if(text == 'View')
				{
					divTypeF.find('input').prop('checked', false);
					
					if(permissionVal == permissionSummary.attr('status'))
					{
						divTypeE.removeClass('block');
						divTypeD.removeClass('block');
						divTypeB.find('.switch input').prop('checked', false);
					}
					else
					{						
						permissionSummary.hide();

						if(divTypeD.find('.para-type-b:first').children(':visible').length == 0)
						{
							divTypeE.removeClass('block');
							divTypeD.removeClass('block');
							divTypeB.find('.switch input').prop('checked', false);
						}
					}
				}
				else
				{
					if(permissionVal == permissionSummary.attr('status'))
					{
						permissionSummary.hide();

						if(divTypeD.find('.para-type-b:first').children(':visible').length == 0)
						{
							divTypeE.removeClass('block');
							divTypeD.removeClass('block');
							divTypeB.find('.switch input').prop('checked', false);
						}
					}
					else
					{
						if(divTypeF.find('input:checked').length == 0)
						{
							permissionSummary.hide();
						}

						if(divTypeD.find('.para-type-b:first').children(':visible').length == 0)
						{
							divTypeE.removeClass('block');
							divTypeD.removeClass('block');
							divTypeB.find('.switch input').prop('checked', false);
						}
					}
				}
			}
		}
	});
</script>	