<div class='full'>
	<h4 class='title-type-a'>Calls</h4>

	<div class='right-top'>
		<button type='button' class='btn btn-type-a add-multiple' data-item='call' modal-title='Add Call Log' data-modalsize='medium' data-action='{!! route('admin.call.store') !!}' data-content='call.partials.form' data-default='{!! "client_type:$client_type|client_id:$client_id|related_type:$related_type|related_id:$related_id" !!}' save-new='false'>
			<i class='mdi mdi-phone-plus'></i> Add Call Log
		</button>

		<button type='button' class='btn btn-type-a'>
			<i class='mdi mdi-phone-in-talk'></i> Make a Phone Call
		</button>
	</div>

    <table id='datatable' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'related-call/' . $module_name . '/' . $module_id !!}' datacolumn='{!! $calls_table['json_columns'] !!}' databtn='{!! table_showhide_columns($calls_table) !!}' perpage='10'>
		<thead>
			<tr>
				<th data-priority='1' data-class-name='all' style='max-width: 125px'>call&nbsp;type</th>
				<th data-priority='3' data-class-name='all' style='max-width: 170px'>conversation&nbsp;with</th>
				<th data-priority='4'>subject</th>								
				<th data-priority='5'>related&nbsp;to</th> 
				<th data-priority='6'>owner</th>					
				<th data-priority='2' data-class-name='all' data-orderable='false' data-class-name='align-r' class='action-2'></th>       			      			        			
			</tr>
		</thead>
	</table>
</div> <!-- end full -->