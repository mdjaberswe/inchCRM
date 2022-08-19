<div class='modal-body perfectscroll'>
	<div class='form-group m-bottom-force-0'>
		<div class='col-xs-12'>
			<div class='full'>
				<span field='contacts' class='validation-error block'></span>
				<span field='accounts' class='validation-error block'></span>
				<span field='module_id' class='validation-error block'></span>
				<span field='module_name' class='validation-error block'></span>
			</div>

		    <table id='modal-datatable' class='table' cellspacing='0' width='100%' data-item='item' data-url='{!! 'hierarchy-add-child' !!}' data-column='{!! $hierarchy_childs_table['json_columns'] !!}'>
				<thead>
					<tr>
	        			<th data-orderable='false' data-class-name='center' style='width: 40px; min-width: 40px; max-width: 40px;'>
							<div id='item-select-all' class='pretty margin-0 info smooth select-all' data-toggle='tooltip' data-placement='top' title='Select All'>
								<input type='checkbox'> 
								<label><i class='mdi mdi-check'></i></label>
							</div>
	        			</th>
						<th style='min-width: 150px;'>NAME</th>				
						<th>PHONE</th>
						<th data-class-name='align-r padding-r-30'>
							<span data-toggle='tooltip' data-placement='top' title='Open&nbsp;deals&nbsp;amount'>OPEN&nbsp;DEALS&nbsp;AMT</span>
						</th>
						<th style='min-width: 120px;'>PARENT</th>		      			        			
					</tr>
				</thead>
			</table>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
	    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
	        <label for='add_method' class='padding-0'>Choose how to add</label>

	        <div class='inline-input'>
	            <p class='pretty top-space info smooth'>
	                <input type='radio' name='add_method' value='one'>
	                <label><i class='mdi mdi-check'></i></label>  Add Sub-Accounts only 
	            </p> 
	            <br>
	            <p class='pretty top-space info smooth'>
	                <input type='radio' name='add_method' value='all' checked>
	                <label><i class='mdi mdi-check'></i></label> Add Sub-Accounts along with all its childs node hierarchy
	            </p>
	        </div>  

	        <div class='full'>
	            <span field='add_method' class='validation-error block'></span>
	        </div>    
	    </div>
	</div> <!-- end form-group -->
</div> <!-- end modal-body -->	

{!! Form::hidden('module_id', null) !!}
{!! Form::hidden('module_name', null) !!}