<h4 class='title-type-a'>Tasks</h4>

<table id='datatable' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'user-task/' . $staff->id !!}' datacolumn='{!! $tasks_table['json_columns'] !!}' databtn='{!! table_showhide_columns($tasks_table) !!}' perpage='10'>
	<thead>
		<tr>
			<th style='min-width: 300px; max-width: 330px'>NAME</th>
			<th style='min-width: 160px'>TASK OWNER</th>
			<th style='max-width: 90px'>PROGRESS</th>
			<th style='min-width: 110px; max-width: 120px'>DATE</th>	        			      			        			
		</tr>
	</thead>
</table>