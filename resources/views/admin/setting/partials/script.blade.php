<script>
	$(document).ready(function()
	{
		$('.save').click(function(e)
		{
			e.preventDefault();
			var form = $(this).closest('form');	
			var formUrl = form.prop('action');
			var formData = new FormData($('.smooth-save').get(0));	
			smoothSave(formUrl, formData);
		});
	});
</script>