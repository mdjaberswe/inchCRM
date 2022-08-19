<div class='col-xs-12 col-sm-3 col-md-3 col-lg-2 panel-nav-container'>	
	<h4 class='panel-nav-title'><i class='fa fa-cogs'></i> App Settings</h4>

	<ul class='panel-nav'>
	    <li class='{!! active_menu('administration-setting.general') !!}'><a href='{!! route('admin.administration-setting.general') !!}'>General</a></li>
		<li class='{!! active_menu('administration-setting.company') !!}'><a href='{!! route('admin.administration-setting.company') !!}'>Company</a></li>
		<li class='{!! active_menu('administration-setting.email') !!}'><a href='{!! route('admin.administration-setting.email') !!}'>Email</a></li>
		<li class='{!! active_menu('administration-setting.sms') !!}'><a href='{!! route('admin.administration-setting.sms') !!}'>SMS</a></li>
		<li class='{!! active_menu('administration-setting-currency.') !!}'><a href='{!! route('admin.administration-setting-currency.index') !!}'>Currencies</a></li>
		<li class='{!! active_menu('administration-setting.payment') !!}'><a href='{!! route('admin.administration-setting.payment') !!}'>Payment Gateways</a></li>
		<li class='{!! active_menu('administration-setting-offline-payment.') !!}'><a href='{!! route('admin.administration-setting-offline-payment.index') !!}'>Payment&nbsp;Methods&nbsp;<span class='para-hint-sm'>(offline)</span></a></li>
		<li class='{!! active_menu('administration-setting-lead-scoring-rule.') !!}'><a href='{!! route('admin.administration-setting-lead-scoring-rule.index') !!}'>Lead&nbsp;Scoring&nbsp;Rules</a></li>
		<li class='{!! active_menu('administration-setting.notification') !!}'><a href='{!! route('admin.administration-setting.notification') !!}'>Notification</a></li>
		<li class='{!! active_menu('administration-setting.cronjob') !!}'><a href='{!! route('admin.administration-setting.cronjob') !!}'>Cron Job</a></li>
	</ul>
</div>	