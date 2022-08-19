<div class='full'>
	<h4 class='title-type-a'>Deals</h4>

     <div class='right-top'>
		<button type='button' class='btn btn-type-a add-multiple' data-item='deal' data-action='{!! route('admin.deal.store') !!}' data-content='deal.partials.form' data-default='{!! 'account_id:' . $account_id . '|contact_id:' . $contact_id . '|primary_contact:' . $contact_id . '|campaign_id:' . $campaign_id !!}' data-show='{!! $module_name . '_id' !!}' save-new='false'>
			<i class='fa fa-plus-circle'></i> Add Deal
		</button>
     </div>

    <table id='datatable' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'connected-deal/' . $module_name . '/' . $module_id !!}' datacolumn='{!! $deals_table['json_columns'] !!}' databtn='{!! table_showhide_columns($deals_table) !!}' perpage='10'>
		<thead>
			<tr>
				<th data-priority='1' data-class-name='all' style='min-width: 150px;'>deal&nbsp;name</th>
				<th data-priority='3' data-class-name='align-r'>amount</th>
				<th data-priority='4'>closing&nbsp;date</th>
				<th data-priority='5'>pipeline</th>
				<th data-priority='6'>stage</th>	
				<th data-priority='7' style='max-width: 170px;'>owner</th>					
				<th data-priority='2' data-orderable='false' data-class-name='align-r all' class='action-2'></th>       			      			        			
			</tr>
		</thead>
	</table>
</div> <!-- end full -->	