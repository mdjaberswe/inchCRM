<script>
	$(document).ready(function()
	{
		$(document).on('click', '.switch-contact', function()
		{
			var thisSwitch = $(this);
			var input = $(this).find('input');
			var postUrl = globalVar.baseAdminUrl + '/update-contact-status';
			globalChangeStatus(thisSwitch, input, postUrl);
		});
	});
</script>