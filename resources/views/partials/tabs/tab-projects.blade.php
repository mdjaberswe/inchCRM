<div class='full'>
	<h4 class='title-type-a'>Projects</h4>

    <div class='right-top'>
   		<button type='button' class='btn btn-type-a add-multiple' data-item='project' data-action='{!! route('admin.project.store') !!}' data-content='project.partials.form' data-default='{!! "account_id:$account_id|contact_id[]:$contact_id|deal_id:$deal_id" !!}' data-show='{!! $module_name . '_id' !!}' save-new='false'>
   			<i class='fa fa-plus-circle'></i> Add Project
   		</button>
    </div>

	<table id='datatable' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'connected-project/' . $module_name . '/' . $module_id !!}' datacolumn='{!! $projects_table['json_columns'] !!}' databtn='{!! table_showhide_columns($projects_table) !!}' perpage='10'>
		<thead>
			<tr>
				<th data-priority='1' data-class-name='all' style='min-width: 170px; max-width: 180px'>project&nbsp;name</th>				
				<th data-priority='3' style='max-width: 67px' data-class-name='center narrow'>progress</th>
				<th data-priority='7' style='max-width: 80px' data-class-name='center'>tasks</th>
				<th data-priority='8' style='max-width: 80px' data-class-name='center'>milestones</th>
				<th data-priority='9' style='max-width: 80px' data-class-name='center'>issues</th>
				<th data-priority='5' style='min-width: 80px; max-width: 80px'>start&nbsp;date</th>	
				<th data-priority='6' style='min-width: 80px; max-width: 80px'>end&nbsp;date</th>	
				<th data-priority='4' style='max-width: 130px'>owner</th> 
				<th data-priority='2' data-orderable='false' data-class-name='align-r all' class='action-2'></th>       			      			        			
			</tr>
		</thead>
	</table>
</div> <!-- end full -->