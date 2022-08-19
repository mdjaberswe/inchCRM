<div class='full'>
	<h4 class='title-type-a'>Campaigns</h4>

    <div class='right-top'>
   		<button type='button' class='btn btn-type-a add-multiple' modal-title='Add Campaigns to {!! ucfirst($member_type) !!}' modal-sub-title='{!! $member->complete_name !!}' modal-datatable='true' datatable-url='{!! 'campaign-data-select/' . $member_type . '/' . $member_id !!}' data-action='{!! route('admin.member.campaign.add', [$member_type, $member_id]) !!}' data-content='campaign.partials.modal-add-campaign' data-default='{!! 'member_id:' . $member_id . '|member_type:' . $member_type !!}' save-new='false' save-txt='Add to {!! ucfirst($member_type) !!}'>
   			<i class='fa fa-plus-circle'></i> Add Campaigns
   		</button>
    </div>

    <table id='datatable' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'member-campaign/' . $member_type . '/' . $member_id !!}' datacolumn='{!! $campaigns_table['json_columns'] !!}' databtn='{!! table_showhide_columns($campaigns_table) !!}' bulk='false' perpage='10'>
		<thead>
			<tr>
				<th data-priority='1' data-class-name='all'>campaign&nbsp;name</th>		
				<th data-priority='3'>type</th>		
				<th data-priority='4'>status</th>					
				<th data-priority='5'>start&nbsp;date</th>
				<th data-priority='6'>end&nbsp;date</th>	
				<th data-priority='7' data-class-name='align-r narrow' style='max-width: 134px'>expected&nbsp;revenue</th>	
				<th data-priority='8' data-class-name='align-r narrow' style='max-width: 113px'>budgeted&nbsp;cost</th>	
				<th data-priority='9' data-class-name='narrow' style='max-width: 107px'>member&nbsp;status</th> 
				<th data-priority='2' data-orderable='false' data-class-name='align-r all' class='action-2'></th>     			      			        			
			</tr>
		</thead>
	</table>
</div> <!-- end full -->	