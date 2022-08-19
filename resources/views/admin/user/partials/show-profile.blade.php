<div class='full'>
	<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 user-info'>
		<div class='full min-h-190'>
			<div class='col-xs-12 col-sm-6 col-md-4 col-lg-4 avatar padding-0 m-20-0 center'>
				<img src='{!! $staff->avatar !!}' alt='{!! $staff->last_name !!}' class='img-type-a'>
				@if($staff->follow_command_rule)
					<a class='profile-picture-btn btn-type-b left' editid='{!! $staff->id !!}' data-toggle='tooltip' data-placement='bottom' title='Change&nbsp;Photo'><i class='fa fa-camera'></i></a>
				@endif
				<div class='div-type-k bottom'>
					{!! $staff->getStatusHtmlAttribute('bottom') !!}
				</div>
			</div>

			<div class='col-xs-12 col-sm-6 col-md-8 col-lg-8 div-type-i space left'>
				<p class='para-type-g'>
					{!! "<span class='user-name' realtime='name' >" . str_limit($staff->name, 40, ".") . "</span> <span realtime='admin_status'>" . $staff->admin_html . "</span>" !!}
					@if(!is_null($staff->deleted_at))
						<span class='c-danger'>(Deleted)</span>
					@endif
				</p>
				<p class='para-type-h'><span realtime='title'>{!! str_limit($staff->title, 30, '.') !!}</span> at <a href=''>inchCRM</a></p>
				<p class='para-type-i'><span class='fa fa-envelope-o'></span> <span realtime='email'>{!! str_limit($staff->email, 50) !!}</span></p>
				<p class='para-type-i'><span class='fa fa-phone'></span> <span realtime='phone'>{!! str_limit($staff->phone, 50) !!}</span></p>
				<p class='center'>
					@if(!$staff->logged_in)
						<a id='new-msg' class='btn-circle-inline'><i class='mdi mdi-comment-account'></i></a>
					@endif
					<a href='{!! $staff->getSocialLinkAttribute('facebook') !!}' class='btn-circle-inline' target='_blank'><i class='mdi mdi-facebook'></i></a>
					<a href='{!! $staff->getSocialLinkAttribute('twitter') !!}' class='btn-circle-inline' target='_blank'><i class='mdi mdi-twitter'></i></a>
					<a href='{!! $staff->getSocialLinkAttribute('linkedin') !!}' class='btn-circle-inline' target='_blank'><i class='mdi mdi-linkedin'></i></a>	
				</p>
			</div>
		</div>	
	</div>

	<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
		<table class='table stat-table sm col-4'>
			<tr>
				<td>
					<p data-toggle='tooltip' data-placement='bottom' title='Converted&nbsp;/&nbsp;Total'>
						<span class='shadow'>Leads</span><br>
						{!! $staff->leads->where('converted', 1)->count() !!} 
						<span class='shadow'> / {!! $staff->leads->count() !!}</span>
					</p>
				</td>
				<td><span class='shadow'>Accounts</span><br>{!! $staff->ownaccounts->count() !!}</td>
				<td>
					<p data-toggle='tooltip' data-placement='bottom' title='Completed&nbsp;/&nbsp;Total'>
						<span class='shadow'>Projects</span><br>
						{!! $staff->relate_projects->where('status', 'completed')->count() !!} 
						<span class='shadow'> / {!! $staff->relate_projects->count() !!}</span>
					</p>
				</td>
				<td>
					<p data-toggle='tooltip' data-placement='bottom' title='Completed&nbsp;/&nbsp;Total'>
						<span class='shadow'>Tasks</span><br>
						{!! $staff->relate_tasks->where('completion_percentage', 100)->count() !!} 
						<span class='shadow'> / {!! $staff->relate_tasks->count() !!}</span>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p data-toggle='tooltip' data-placement='bottom' title='Completed&nbsp;/&nbsp;Total'>
						<span class='shadow'>Campaigns</span><br>
						{!! $staff->owncampaigns->where('status', 'completed')->count() !!} 
						<span class='shadow'> / {!! $staff->owncampaigns->count() !!}</span>
					</p>
				</td>
				<td>
					<p data-toggle='tooltip' data-placement='bottom' title='Won&nbsp;/&nbsp;Total'>
						<span class='shadow'>Deals</span><br>
						{!! $staff->owndeals->where('won', 1)->count() !!} 
						<span class='shadow'> / {!! $staff->owndeals->count() !!}</span>
					</p>
				</td>
				<td><span class='shadow'>Sales</span><br>{!! $staff->sales_html !!}</td>
				<td>
					<p data-toggle='tooltip' data-placement='bottom' title='Completed&nbsp;/&nbsp;Total'>
						<span class='shadow'>Goals</span><br>
						{!! $staff->goals->where('overall_achievement', '100.00')->count() !!} 
						<span class='shadow'> / {!! $staff->goals->count() !!}</span>
					</p>
				</td>
			</tr>
		</table>
	</div>
</div> <!-- end full -->