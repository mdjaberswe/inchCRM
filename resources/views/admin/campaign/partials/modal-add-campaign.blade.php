<div class='modal-body perfectscroll'>
	<div class='form-group m-bottom-force-0'>
		<div class='col-xs-12'>
			<span field='campaigns' class='validation-error'></span>
		    <table id='modal-datatable' class='table middle' cellspacing='0' width='100%' data-item='item' data-url='{!! 'campaign-data-select' !!}' data-column='{!! $campaigns_table['json_columns'] !!}'>
				<thead>
					<tr>
	        			<th data-orderable='false' data-class-name='center' style='width: 40px; min-width: 40px; max-width: 40px;'>
							<div id='item-select-all' class='pretty margin-0 info smooth select-all' data-toggle='tooltip' data-placement='top' title='Select All'>
								<input type='checkbox'> 
								<label><i class='mdi mdi-check'></i></label>
							</div>
	        			</th>
						<th>CAMPAIGN&nbsp;NAME</th>				
						<th>STATUS</th>
						<th>TYPE</th>	
						<th>START&nbsp;DATE</th>
						<th>END&nbsp;DATE</th>	
						<th data-class-name='align-r padding-r-30'>EXPECTED&nbsp;REVENUE</th>			      			        			
					</tr>
				</thead>
			</table>
		</div>	
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='member_status' class='col-xs-12 col-sm-1 col-md-1 col-lg-1 align-l-force min-w-90'>Status <span class='c-danger'>*</span></label>
		
		<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>
			{!! Form::select('member_status', $member_status_list, null, ['class' => 'form-control white-select-type-single-b']) !!}
			<span field='member_status' class='validation-error'></span>
			<span field='member_id' class='validation-error'></span>
			<span field='member_type' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->
</div> <!-- end modal-body -->	

{!! Form::hidden('member_id', null) !!}
{!! Form::hidden('member_type', null) !!}