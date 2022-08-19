<div class='full'>
	<h4 class='title-type-a'>Invoices</h4>

    <div class='right-top'>
   		<a class='btn btn-type-a' href='{!! route('admin.sale-invoice.create', ['module' => $module_name, 'account' => $account_id, 'contact' => $contact_id, 'deal' => $deal_id, 'project' => $project_id]) !!}'>
   			<i class='fa fa-plus-circle'></i> Add Invoice
   		</a>
    </div>

    <table id='datatable' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'connected-invoice/' . $module_name . '/' . $module_id !!}' datacolumn='{!! $invoices_table['json_columns'] !!}' databtn='{!! table_showhide_columns($invoices_table) !!}' perpage='10'>
		<thead>
			<tr>
				<th data-priority='1' data-class-name='all'>invoice&nbsp;#</th>
				<th data-priority='3'>account</th>	
				<th data-priority='8'>status</th>	
				<th data-priority='5' data-class-name='align-r'>total</th> 
				<th data-priority='6'>invoice&nbsp;date</th>
				<th data-priority='7'>due&nbsp;date</th>		
				<th data-priority='4'>sales&nbsp;agent</th>				
				<th data-priority='2' data-orderable='false' data-class-name='align-r all' class='action-2'></th>       			      			        			
			</tr>
		</thead>
	</table>
</div> <!-- end full -->