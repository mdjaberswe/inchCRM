<div class='modal-body perfectscroll'>
	<div class='form-group m-bottom-force-0'>
		<div class='col-xs-12'>
			<span field='items' class='validation-error'></span>
			<span field='linked_id' class='validation-error'></span>
			<span field='linked_type' class='validation-error'></span>
		    <table id='modal-datatable' class='table middle' cellspacing='0' width='100%' data-item='item' data-url='{!! 'item-data' !!}' data-column='{!! $items_table['json_columns'] !!}'>
				<thead>
					<tr>
	        			<th data-orderable='false' data-class-name='center' style='width: 40px; min-width: 40px; max-width: 40px;'>
							<div id='item-select-all' class='pretty margin-0 info smooth select-all' data-toggle='tooltip' data-placement='top' title='Select All'>
								<input type='checkbox'> 
								<label><i class='mdi mdi-check'></i></label>
							</div>
	        			</th>
						<th data-class-name='align-l'>ITEM NAME</th>				
						<th data-class-name='align-r padding-r-30'>UNIT PRICE</th>  			      			        			
					</tr>
				</thead>
			</table>
		</div>
	</div> <!-- end form-group -->
</div> <!-- end modal-body -->	

{!! Form::hidden('linked_id', null) !!}
{!! Form::hidden('linked_type', null) !!}