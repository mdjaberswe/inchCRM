<div class='full'>
	<h4 class='title-type-a'>Tasks</h4>

    <div class='right-top'>
   		<button type='button' class='btn btn-type-a add-multiple' data-item='task' data-action='{!! route('admin.task.store') !!}' data-content='task.partials.form' data-default='{!! 'related_type:' . $module_name . '|related_id:' . $module_id !!}' data-show='{!! $module_name . '_id' !!}' save-new='false'>
   			<i class='fa fa-plus-circle'></i> Add Task
   		</button>
    </div>

    <div class='table-filter none'>
    	{!! table_filter_html($tasks_table['filter_input'], $module_name) !!}
    </div>

	<table id='datatable' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'connected-task/' . $module_name . '/' . $module_id !!}' datacolumn='{!! $tasks_table['json_columns'] !!}' databtn='{!! table_showhide_columns($tasks_table) !!}' perpage='10'>
		<thead>
			<tr>
				<th data-priority='1' data-class-name='all' style='max-width: 330px'>task&nbsp;name</th>				
				<th data-priority='3' style='min-width: 80px; max-width: 80px'>due&nbsp;date</th>
				<th data-priority='4' style='min-width: 80px'>status</th>
				<th data-priority='5' style='max-width: 80px'>progress</th>
				<th data-priority='6'>priority</th>
				<th data-priority='7' data-class-name='all'>owner</th> 
				<th data-priority='2' data-orderable='false' data-class-name='align-r all' class='action-2'></th>       			      			        			
			</tr>
		</thead>
	</table>
</div> <!-- end full -->	