<div class='col-xs-12 col-md-4'>
	<div class='full'>
		<div class='timeline-sidebox float-sm-auto-md-right'>
			<div class='strong uppercase'>Completion</div>

			<div id='completion-percentage' class='circlebox sm'>
				{!! $task->classified_completion !!}
			</div>	

			<div class='full'>
				<h4>Last modified:</h4>
				<div class='full' data-realtime='last_modified'>
					<p data-toggle='tooltip' data-placement='bottom' title='{!! $task->readableDateAmPm('modified_at') !!}'>{!! time_short_form($task->modified_at->diffForHumans()) !!}</p>
				</div>
			</div>

			<div class='full'>
				<h4>Duration:</h4>
				@if(!is_null($task->duration))
					<p>{!! $task->duration_html !!}</p>
				@else
					<p class='c-shadow l-space-1'>--</p>
				@endif
			</div>

			<div class='full'>
				<h4>Reminder:</h4>
				<p class='c-shadow l-space-1'>--</p>	
			</div>
		</div> <!-- end timeline-sidebox -->
	</div>	
</div>