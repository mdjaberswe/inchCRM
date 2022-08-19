<div class='col-xs-12 col-md-4'>
	<div class='full'>
		<div class='timeline-sidebox float-sm-auto-md-right'>
			<div class='strong uppercase'>Probability</div>

			<div id='deal-probability' class='circlebox sm'>
				{!! $deal->classified_probability !!}
			</div>	

			<div class='full'>
				<h4>Last contacted:</h4>
				<p data-toggle='tooltip' data-placement='bottom' title='02:14 PM, Feb 13, 2019'>a month ago</p>
			</div>

			<div class='full'>
				<h4>Last modified:</h4>
				<div class='full' data-realtime='last_modified'>
					<p data-toggle='tooltip' data-placement='bottom' title='{!! $deal->readableDateAmPm('modified_at') !!}'>{!! time_short_form($deal->modified_at->diffForHumans()) !!}</p>
				</div>
			</div>

			<div class='full'>
				<h4>Next Activity:</h4>
				@if(isset($deal->next_activity_date))
					<p data-toggle='tooltip' data-placement='bottom' title='{!! ucfirst($deal->next_activity_type) . ':&nbsp;' . $deal->readableDateAmPm('next_activity_date') !!}'>{!! time_short_form($deal->next_activity_date->diffForHumans()) !!}</p>
				@else
					<p class='c-shadow l-space-1'>--</p>	
				@endif
			</div>
		</div> <!-- end timeline-sidebox -->
	</div>	
</div>