<h4 class='title-type-a'>Projects</h4>

<table id='datatable' class='table display' cellspacing='0' width='100%' dataurl='{!! 'user-project/' . $staff->id !!}' datacolumn='{!! $projects_table['json_columns'] !!}' databtn='{!! table_showhide_columns($projects_table) !!}' perpage='10'>
	<thead>
		<tr>
			<th style='min-width: 195px'>PROJECT NAME</th>
			<th data-class-name='center'>PROGRESS</th>
			<th style='min-width: 160px'>OWNER</th>
			<th style='min-width: 160px'>MEMBERS</th>	 
			<th style='max-width: 90px'>TASKS</th>
			<th style='max-width: 90px'>MILESTONES</th>
			<th style='max-width: 90px'>ISSUES</th>
			<th>DATE</th>
		</tr>
	</thead>
</table>