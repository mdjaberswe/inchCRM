@extends('layouts.master')

@section('content')

	<div class='row'>
		@include('partials.subnav.setting')
			
	    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-10 div-type-a'>
	        <h4 class='title-type-a near'>Notification Settings</h4>

	        @include('partials.tabs.tab-index')
	    </div> <!-- end div-type-a -->
	</div> <!-- end row -->

@endsection

@push('scripts')
	{!! HTML::script('js/tabs.js') !!}

	<script>
		$(document).ready(function()
		{
			$('#item-tab-details').on('click', '.notifycase', function()
			{
				var web = $(".switch-notify[type='web'] input:checked").size();
				var email = $(".switch-notify[type='email'] input:checked").size();
				var sms = $(".switch-notify[type='sms'] input:checked").size();
				
				if(web)
				{
					$(".switch-all[child='web']").find('input').prop('checked', true);
				}

				if(email)
				{
					$(".switch-all[child='email']").find('input').prop('checked', true);
				}

				if(sms)
				{
					$(".switch-all[child='sms']").find('input').prop('checked', true);
				}
			});

			$('#item-tab-details').on('click', '.switch-notify', function()
			{
				var thisSwitch = $(this);
				var input = $(this).find('input');
				var postUrl = globalVar.baseAdminUrl + '/update-notification-case';
				var type = $(this).attr('type');
				var typeTxt = type == 'sms' ? type.toUpperCase() : ucword(type); 
				var inactiveStatusTxt = typeTxt + ' disabled';
				var activeStatusTxt = typeTxt + ' enabled';
				globalChangeStatus(thisSwitch, input, postUrl, inactiveStatusTxt, activeStatusTxt);
			});

			$('#item-tab-details').on('click', '.switch-all', function()
			{
				var checked = $(this).find('input').prop('checked') ? 1 : 0;
				var ids = [];
				var childType = $(this).attr('child');
				var typeTxt = childType == 'sms' ? childType.toUpperCase() : ucword(childType); 

				$('#item-tab-details .notifycase').find('.switch-notify').each(function(index, obj)
				{
					var type = $(obj).attr('type');

					if(childType == type)
					{
						var id = $(obj).find('input').val().replace(childType + '-', '');			
						ids.push(id);

						if(checked)
						{
							$(obj).find('input').prop('checked', true);
							$(obj).attr('data-original-title', typeTxt + ' enabled');
							$(obj).parent().find('.tooltip-inner').html(typeTxt + ' enabled');
						}
						else
						{
							$(obj).find('input').prop('checked', false);
							$(obj).attr('data-original-title', typeTxt + ' disabled');
							$(obj).parent().find('.tooltip-inner').html(typeTxt + ' disabled');
						}						
					}
				});

				$.ajax({
					type 	: 'POST',
					url 	: globalVar.baseAdminUrl + '/bulk-update-notification-case',
					data 	: { ids : ids, checked : checked, type : childType },
					dataType: 'JSON'
				});
			});	
		});
	</script>
@endpush