<div class='modal-body perfectscroll'>
	<div class='form-group m-bottom-force-0'>
		<div class='col-xs-12'>
			<span field='contacts' class='validation-error block'></span>
			<span field='module_id' class='validation-error block'></span>
			<span field='module_name' class='validation-error block'></span>
			<div class='table-filter none'>
				{!! table_filter_html($contacts_table['filter_input'], 'contact', true) !!}
			</div>
		    <table id='modal-datatable' class='table middle' cellspacing='0' width='100%' data-item='contact' data-url='{!! 'participant-select' !!}' data-column='{!! $contacts_table['json_columns'] !!}'>
				<thead>
					<tr>
	        			<th data-orderable='false' data-class-name='center' style='width: 40px; min-width: 40px; max-width: 40px;'>
							<div id='item-select-all' class='pretty margin-0 info smooth select-all' data-toggle='tooltip' data-placement='top' title='Select All'>
								<input type='checkbox'> 
								<label><i class='mdi mdi-check'></i></label>
							</div>
	        			</th>
						<th>CONTACT&nbsp;NAME</th>				
						<th>PHONE</th>
						<th>EMAIL</th>	
						<th>TYPE</th>
						<th>ACCOUNT</th>    			        			
					</tr>
				</thead>
			</table>
		</div>	
	</div> <!-- end form-group -->
</div> <!-- end modal-body -->	

{!! Form::hidden('module_id', null) !!}
{!! Form::hidden('module_name', null) !!}