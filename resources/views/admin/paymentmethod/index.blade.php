@extends('templates.listing-posionable')

@push('scripts')
	<script>
		$(document).ready(function()
		{
			$('#datatable').on('click', '.switch-paymethod', function()
			{
				var thisSwitch = $(this);
				var input = $(this).find('input');
				var postUrl = globalVar.baseAdminUrl + '/update-paymentmethod-status';
				globalChangeStatus(thisSwitch, input, postUrl);
			});
		});
	</script>
@endpush			