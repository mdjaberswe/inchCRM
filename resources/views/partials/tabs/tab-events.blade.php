<div class='full'>
	<h4 class='title-type-a'>Events</h4>

    <div class='right-top'>
   		<button type='button' class='btn btn-type-a add-multiple' data-item='event' data-action='{!! route('admin.event.store') !!}' data-content='event.partials.form' data-default='{!! 'related:' . $module_name . '|' . $module_name . '_id:' . $module_id !!}' data-show='{!! $module_name . '_id' !!}' save-new='false'>
   			<i class='fa fa-plus-circle'></i> Add Event
   		</button>
    </div>

    <table id='datatable' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'connected-event/' . $module_name . '/' . $module_id !!}' datacolumn='{!! $events_table['json_columns'] !!}' databtn='{!! table_showhide_columns($events_table) !!}' perpage='10'>
		<thead>
			<tr>
				<th data-priority='1' data-class-name='all' style='min-width: 220px; max-width: 280px'>event&nbsp;name</th>
				<th data-priority='3' style='min-width: 80px; max-width: 80px'>start&nbsp;date</th>	
				<th data-priority='4' style='min-width: 80px; max-width: 80px'>end&nbsp;date</th>	
				<th data-priority='6'>location</th> 
				<th data-class-name='all' style='min-width: 120px; max-width: 120px'>attendees</th>
				<th data-class-name='all'>owner</th>						
				<th data-priority='2' data-orderable='false' data-class-name='align-r all' class='action-2'></th>       			      			        			
			</tr>
		</thead>
	</table>
</div> <!-- end full -->