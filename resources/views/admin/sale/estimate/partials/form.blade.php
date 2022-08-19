<div class='row'>
	<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 m-bottom-15'>
		<div class='form-group'>
			<label for='account_id'>Account Name<span class='c-danger'>*</span></label>
			{!! Form::select('account_id', $accounts_list, isset($estimate) ? $estimate->account_id : non_property_checker($default_estimate, 'account_id'), ['class' => 'form-control account select-type-single']) !!}
			<span error-field='account_id' class='validation-error'>{!! $errors->first('account_id', ':message') !!}</span>
		</div> <!-- end form-group -->

		{!! Form::hidden('contact_id', isset($estimate) ? $estimate->contact_id : non_property_checker($default_estimate, 'contact_id')) !!}
		{!! Form::hidden('deal_id', isset($estimate) ? $estimate->deal_id : non_property_checker($default_estimate, 'deal_id')) !!}
		{!! Form::hidden('project_id', isset($estimate) ? $estimate->project_id : non_property_checker($default_estimate, 'project_id')) !!}
		{!! Form::hidden('redirect', isset($default_estimate) ? $default_estimate->redirect : null) !!}

		<div class='form-group field-details field-account' @if(isset($estimate)) style='display: block;' @endif>
			<div class='div-type-l'>
				<h3>Account Details</h3>
				@if(isset($estimate))
					<p class='account-name'>{!! $estimate->account->account_name !!}</p>
					<p class='address-line-first'>{!! $estimate->account->street . ', ' . $estimate->account->city !!}</p>
					<p class='address-line-second'>{!! $estimate->account->state . ', ' . $estimate->account->country->ascii_name !!}</p>
					<p><span class='normal'>Phone:</span> <span class='account-phone'>{!! $estimate->account->account_phone !!}</span></p>
					<p class='last'><span class='normal'>Email:</span> <span class='account-email'>{!! $estimate->account->account_email !!}</span></p>
				@else
					<p class='account-name'>Account Name</p>
					<p class='address-line-first'>Address Line One</p>
					<p class='address-line-second'>Address Line Two</p>
					<p><span class='normal'>Phone:</span> <span class='account-phone'>Account Phone</span></p>
					<p class='last'><span class='normal'>Email:</span> <span class='account-email'>Account Email</span></p>
				@endif				
			</div>
		</div>

		<div class='form-group'>
			<label for='subject'>Subject</label>
			{!! Form::text('subject', null, ['class' => 'form-control']) !!}
			<span error-field='subject' class='validation-error'>{!! $errors->first('subject', ':message') !!}</span>
		</div> <!-- end form-group -->

		<div class='form-group'>
			<label for='status'>Status</label>
			{!! Form::select('status', $status_list, isset($estimate) ? $estimate->status : null, ['class' => 'form-control select-type-single']) !!}
			<span error-field='status' class='validation-error'>{!! $errors->first('status', ':message') !!}</span>
		</div> <!-- end form-group -->

		<div class='form-group'>
			<label for='sale_agent'>Sales Agent</label>
			{!! Form::select('sale_agent', $sale_agents_list, isset($estimate) ? $estimate->sale_agent : auth_staff()->id, ['class' => 'form-control select-type-single']) !!}
			<span error-field='sale_agent' class='validation-error'>{!! $errors->first('sale_agent', ':message') !!}</span>
		</div> <!-- end form-group -->
	</div>

	<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
		<div class='row'>
			<div class='full'>
				<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 div-type-m'>
					<div class='form-group left-icon-a'>
						<i class='fa fa-hashtag'></i>					
						<label for='number'>Estimate Number <span class='c-danger'>*</span></label>
						{!! Form::text('number', isset($estimate) ? $estimate->number_format : sprintf('%04d', $number), ['class' => 'form-control']) !!}
						<span error-field='number' class='validation-error'>{!! $errors->first('number', ':message') !!}</span>
					</div> <!-- end form-group -->
				</div>

				<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 div-type-m'>
					<div class='form-group left-icon-a'>
						<i class='fa fa-bookmark-o'></i>
						<label for='reference'>Reference</label>					
						{!! Form::text('reference', null, ['class' => 'form-control']) !!}
						<span error-field='reference' class='validation-error'>{!! $errors->first('reference', ':message') !!}</span>
					</div> <!-- end form-group -->
				</div>
			</div>	

			<div class='full'>
				<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 div-type-m'>
					<div class='form-group left-icon-a'>
						<i class='fa fa-calendar-check-o'></i>
						<label for='estimate_date'>Estimate Date <span class='c-danger'>*</span></label>
						{!! Form::text('estimate_date', isset($estimate) ? $estimate->estimate_date : date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
						<span error-field='estimate_date' class='validation-error'>{!! $errors->first('estimate_date', ':message') !!}</span>
					</div> <!-- end form-group -->
				</div>

				<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 div-type-m'>
					<div class='form-group left-icon-a'>
						<i class='fa fa-calendar-times-o'></i>
						<label for='expiry_date'>Expiry Date</label>
						{!! Form::text('expiry_date', isset($estimate) ? $estimate->expiry_date : $expiry_date, ['class' => 'form-control datepicker']) !!}
						<span error-field='expiry_date' class='validation-error'>{!! $errors->first('expiry_date', ':message') !!}</span>
					</div> <!-- end form-group -->
				</div>
			</div>	

			<div class='full'>
				<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 div-type-m'>
					<div class='form-group'>
						<label for='currency_id'>Currency</label>
						<select name='currency_id' class='form-control currency select-type-single'>
							@foreach($currencies as $currency)
								@if(isset($estimate))
									<option value='{!! $currency->id !!}' symbol='{!! $currency->symbol !!}' @if($estimate->currency_id == $currency->id) selected @endif>{!! $currency->code !!}</option>
								@else
									<option value='{!! $currency->id !!}' symbol='{!! $currency->symbol !!}' @if($base_currency->id == $currency->id) selected @endif>{!! $currency->code !!}</option>
								@endif
							@endforeach							
						</select>
						<span error-field='currency_id' class='validation-error'>{!! $errors->first('currency_id', ':message') !!}</span>
					</div> <!-- end form-group -->
				</div>

				<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 div-type-m'>
					<div class='form-group'>
						<label for='discount_type'>Discount Type</label>
						{!! Form::select('discount_type', $discount_types_list, isset($estimate) ? $estimate->discount_type : null, ['class' => 'form-control discount-type select-type-single-b']) !!}
						<span error-field='discount_type' class='validation-error'>{!! $errors->first('discount_type', ':message') !!}</span>
					</div> <!-- end form-group -->
				</div>
			</div>	

			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-m'>
				<div class='form-group editor-type-a h-180'>
					<label for='note'>Estimate Note</label>
					{!! Form::textarea('note', null, ['class' => 'form-control editor']) !!}
					<span error-field='note' class='validation-error'>{!! $errors->first('note', ':message') !!}</span>
				</div> <!-- end form-group -->
			</div>
		</div>
	</div>
</div> <!-- end row -->

<div class='row'>
	<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
		<div id='estimate-items' class='table-responsive item-sheet'>
		    <table class='table full'>
		        <thead>
		            <tr>
		                <th style='min-width: 200px'>Item</th>
		                <th style='min-width: 80px'>Quantity</th>
		                <th style='min-width: 80px'>Rate</th>
		                <th style='min-width: 80px'>Discount 
		                	@if(isset($estimate) && $estimate->discount_type !== 'flat')
		                		<span class='discount-percent'>(%)</span>
		                	@endif

		                	@if(!isset($estimate))
		                		<span class='discount-percent'>(%)</span>
		                	@endif	               	
		                </th>
		                <th style='min-width: 80px'>Tax <span class='tax-percent'>(%)</span></th>		                
		                <th style='min-width: 130px'>Amount (<span class='sheet-currency symbol'>{!! isset($estimate) ? $estimate->currency->symbol : $base_currency->symbol !!}</span>)</th>
		            </tr>
		        </thead>

		        <tbody class='item-sheet-body'>
		        	@if(isset($estimate) && $estimate->itemsheets->count() > 0)
		        		@foreach($estimate->itemsheets as $item_sheet)
		        			<tr>
		        			    <td><input type='text' name='item_name[]' value='{!! $item_sheet->item !!}' class='form-control item-name' placeholder='Enter Item' autocomplete='off'></td>
		        			    <td><input type='text' name='quantity[]' value='{!! $item_sheet->quantity !!}' class='form-control quantity' placeholder='Quantity' autocomplete='off'></td>
		        			    <td><input type='text' name='rate[]' value='{!! $item_sheet->rate !!}' class='form-control rate' placeholder='Rate' autocomplete='off'></td>
		        			    <td>
		        			    	<input type='text' name='discount[]' value='{!! $item_sheet->discount !!}' class='form-control discount' placeholder='Discount' autocomplete='off'>
		        			    	<input type='hidden' name='discount_val[]' value='{!! $item_sheet->discount_value !!}' class='discount-val'>
		        			    </td>
		        			    <td>
		        			    	<input type='text' name='tax[]' value='{!! $item_sheet->tax !!}' class='form-control tax' placeholder='Tax' autocomplete='off'>
		        			    	<input type='hidden' name='tax_val[]' value='{!! $item_sheet->tax_value !!}' class='tax-val'>
		        			    </td>
		        			    <td class='amount-column'>
		        			    	<span class='sheet-currency symbol'>{!! isset($estimate) ? $estimate->currency->symbol : $base_currency->symbol !!}</span> 
		        			    	<span class='amount'>{!! $item_sheet->amount_format !!}</span>
		        			    	<input type='hidden' name='amount[]' value='{!! $item_sheet->amount !!}' class='amount-val'>
		        			    	<input type='hidden' name='item_total[]' value='{!! $item_sheet->plain_amount !!}' class='item-total'>	                	
		        			    	@if($estimate->itemsheets()->first()->id !== $item_sheet->id)
		        			    		<button type='button' class='close'><span aria-hidden='true'>&times;</span></button>
		        			    	@endif
		        			    </td>
		        			</tr>
		        		@endforeach
		        	@else
		        		<tr>
		        		    <td><input type='text' name='item_name[]' class='form-control item-name' placeholder='Enter Item' autocomplete='off'></td>
		        		    <td><input type='text' name='quantity[]' value='1' class='form-control quantity' placeholder='Quantity' autocomplete='off'></td>
		        		    <td><input type='text' name='rate[]' class='form-control rate' placeholder='Rate' autocomplete='off'></td>
		        		    <td>
		        		    	<input type='text' name='discount[]' class='form-control discount' placeholder='Discount' autocomplete='off'>
		        		    	<input type='hidden' name='discount_val[]' value='0' class='discount-val'>
		        		    </td>
		        		    <td>
		        		    	<input type='text' name='tax[]' class='form-control tax' placeholder='Tax' autocomplete='off'>
		        		    	<input type='hidden' name='tax_val[]' value='0' class='tax-val'>
		        		    </td>
		        		    <td class='amount-column'>
		        		    	<span class='sheet-currency symbol'>{!! isset($estimate) ? $estimate->currency->symbol : $base_currency->symbol !!}</span> 
		        		    	<span class='amount'>0.00</span>
		        		    	<input type='hidden' name='amount[]' value='0' class='amount-val'>
		        		    	<input type='hidden' name='item_total[]' value='0' class='item-total'>	                	
		        		    </td>
		        		</tr>
		        	@endif
		        </tbody>

		        <tfoot>
		        	<tr class='item-sheet-bottom'>
		        		<td class='item-add'><button type='button' class='btn btn-primary'><i class='fa fa-plus-square'></i> Add Row</button></td>
		        		<td class='title' colspan='4' style='vertical-align: bottom;'><span data-toggle='tooltip' data-placement='left' title='without taxes and discounts'>Sub Total</span></td>
		        		<td class='value' style='vertical-align: bottom;'>
		        			<span class='sheet-currency symbol'>{!! isset($estimate) ? $estimate->currency->symbol : $base_currency->symbol !!}</span> 
		        			<span class='sub-total'>@if(isset($estimate)) {!! $estimate->sub_total_format !!} @else 0.00 @endif</span>
		        			{!! Form::hidden('sub_total', null, ['class' => 'sub-total-val']) !!}
		        		</td>
		        	</tr>

		        	<tr class='item-sheet-bottom'>
		        		<td class='title' colspan='5'>Total Discount</td>
		        		<td class='value'>
		        			<span class='sheet-currency symbol'>{!! isset($estimate) ? $estimate->currency->symbol : $base_currency->symbol !!}</span> 
		        			<span class='total-discount'>@if(isset($estimate)) {!! $estimate->total_discount_format !!} @else 0.00 @endif</span>
		        			{!! Form::hidden('total_discount', null, ['class' => 'total-discount-val']) !!}
		        		</td>
		        	</tr>

		        	<tr class='item-sheet-bottom'>
		        		<td class='title' colspan='5'>Total Tax</td>
		        		<td class='value'>
		        			<span class='sheet-currency symbol'>{!! isset($estimate) ? $estimate->currency->symbol : $base_currency->symbol !!}</span> 
		        			<span class='total-tax'>@if(isset($estimate)) {!! $estimate->total_tax_format !!} @else 0.00 @endif</span>
		        			{!! Form::hidden('total_tax', null, ['class' => 'total-tax-val']) !!}
		        		</td>
		        	</tr>

		        	<tr class='item-sheet-bottom'>
		        		<td class='title' colspan='5'>Adjustment</td>
		        		<td class='value'>{!! Form::text('adjustment', isset($estimate) ? $estimate->adjustment_input : null, ['class' => 'form-control adjustment', 'autocomplete' => 'off']) !!}</td>
		        	</tr>

		        	<tr class='item-sheet-bottom'>
		        		<td class='title' colspan='5'>Grand Total (<span class='sheet-currency symbol'>{!! isset($estimate) ? $estimate->currency->symbol : $base_currency->symbol !!}</span>)</td>
		        		<td class='value'>
		        			{!! Form::text('grand_total', isset($estimate) ? $estimate->grand_total_input : null, ['class' => 'form-control grand-total', 'readonly' => true]) !!}
		        			{!! Form::hidden('plain_grand_total', isset($estimate) ? ($estimate->grand_total_input - $estimate->adjustment_input) : null, ['class' => 'plain-grand-total']) !!}
		        		</td>
		        	</tr>
		        </tfoot>
		    </table>
		</div> <!-- end table-responsive -->  
	</div>

	<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
		<div class='form-group editor-type-a'>
			<label for='term_condition'>Terms &#38; Conditions</label>
			{!! Form::textarea('term_condition', null, ['class' => 'form-control editor']) !!}
			<span error-field='term_condition' class='validation-error'>{!! $errors->first('term_condition', ':message') !!}</span>
		</div> <!-- end form-group -->
	</div>

	@if(isset($estimate))
		{!! Form::hidden('id', $estimate->id) !!}
	@endif

	<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
		<div class='right-justify'>			
			{!! Form::submit('Save', ['name' => 'save', 'id' => 'save', 'class' => 'btn btn-primary']) !!}        
			@if(!isset($estimate))
				{!! Form::hidden('add_new', 0) !!}
				{!! Form::submit('Save and New', ['name' => 'save_and_new', 'id' => 'save-and-new', 'class' => 'btn btn-secondary']) !!}
			@endif
			{!! link_to_route('admin.sale-estimate.index', 'Cancel', [], ['class' => 'btn btn-secondary']) !!}
		</div>
	</div>
</div> <!-- end row -->