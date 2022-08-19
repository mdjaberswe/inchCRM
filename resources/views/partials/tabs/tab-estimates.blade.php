<div class='full'>
	<h4 class='title-type-a'>Estimates</h4>

    <div class='right-top'>
   		<a class='btn btn-type-a' href='{!! route('admin.sale-estimate.create', ['module' => $module_name, 'account' => $account_id, 'contact' => $contact_id, 'deal' => $deal_id, 'project' => $project_id]) !!}'>
   			<i class='fa fa-plus-circle'></i> Add Estimate
   		</a>
    </div>

    <table id='datatable' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'connected-estimate/' . $module_name . '/' . $module_id !!}' datacolumn='{!! $estimates_table['json_columns'] !!}' databtn='{!! table_showhide_columns($estimates_table) !!}' perpage='10'>
		<thead>
			<tr>
				<th data-priority='1' data-class-name='all'>estimate&nbsp;#</th>
				<th data-priority='3'>account</th>	
				<th data-priority='5'>status</th>	
				<th data-priority='4' data-class-name='align-r'>total</th> 
				<th data-priority='7'>estimate&nbsp;date</th>
				<th data-priority='6'>expiry&nbsp;date</th>		
				<th data-priority='8'>sales&nbsp;agent</th>				
				<th data-priority='2' data-orderable='false' data-class-name='align-r all' class='action-2'></th>       			      			        			
			</tr>
		</thead>
	</table>
</div> <!-- end full -->