@extends('layouts.master')

@section('content')

	<div class='row div-type-a'>
		<div class='full div-type-r'>
			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 xs-center-sm-left'>
				<a href='{!! route('admin.sale-estimate.edit', $estimate->id) !!}' class='btn btn-type-a'><i class='fa fa-pencil'></i> Edit Estimate</a>
				<a href='#' class='btn btn-type-a'><i class='fa fa-send-o'></i> Send Email</a>
				<a href='#' class='btn btn-type-a'><i class='fa fa-envelope'></i> Send SMS</a>
				<a href='#' class='btn btn-type-a'><i class='fa fa-print'></i> Print</a>
				<a href='#' class='btn btn-type-a'><i class='fa fa-file-pdf-o'></i> PDF</a>
				<a href='#' class='btn btn-type-a'><i class='fa fa-globe'></i> Preview</a>
				<a href='#' class='btn btn-type-a'><i class='fa fa-level-up'></i> Convert to Invoice</a>
				<a href='#' class='btn btn-danger'><i class='fa fa-minus-circle'></i> Cancel</a>
			</div>
		</div> <!-- end full -->

		<div class='full'>
			<div class='col-xs-12 col-sm-7 col-md-7 col-lg-8 xs-center-sm-left'>
				<img src='{!! asset('img/default-logo.png') !!}' alt='logo' class='img-type-b'>
			</div>

			<div class='col-xs-12 col-sm-5 col-md-5 col-lg-4 div-type-n xs-center-sm-right'>
				<h3 class='title-type-d'>ESTIMATE</h3>
				<p>SRN #{!! $estimate->number_format !!}</p>
				<p>Reference : {!! $estimate->reference !!}</p>
				<p>Total Estimated</p>
				<h4 class='title-type-e'><span class='symbol'>{!! $estimate->currency->symbol !!}</span>{!! $estimate->grand_total_format !!}</h4>
				{!! $estimate->status_html !!}
			</div>
		</div> <!-- end full -->

		<div class='full'>
			<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-type-o xs-center-sm-left'>
				<h5>From</h5>
				<h4>Account Name</h4>
				<p>Street Address, Post Office</p>
				<p>City, State</p>
				<p>Phone: 0123456789</p>
				<p>Email: account@gmail.com</p>
			</div>
		</div> <!-- end full -->

		<div class='full'>
			<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-type-o xs-center-sm-left'>
				<h5>To</h5>
				<h4>{!! $estimate->account->account_name !!}</h4>
				<p>{!! $estimate->account->street !!}, {!! $estimate->account->city !!}</p>
				<p>{!! $estimate->account->state !!}, {!! $estimate->account->country->ascii_name !!}</p>
				<p>Phone: {!! $estimate->account->account_phone !!}</p>
				<p>Email: {!! $estimate->account->account_email !!}</p>
			</div>

			<div class='col-xs-12 col-sm-offset-2 col-sm-4 col-md-offset-3 col-md-3 col-lg-offset-3 col-lg-3 div-type-p xs-center-sm-left'>
				<p><span class='c-shadow'>Estimate Date : </span>{!! $estimate->estimate_date !!}</p>
				<p><span class='c-shadow'>Expiry Date : </span>{!! $estimate->expiry_date !!}</p>
				<p><span class='c-shadow'>Sale Agent : </span>{!! $estimate->saleagent->name !!}</p>
			</div>
		</div> <!-- end full -->

		<div class='full'>
			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
				<div class='table-responsive'>
				    <table class='table bg-none-a full'>
				    	@if(isset($estimate->subject) && $estimate->subject !='')
					    	<caption>
					    		<span class='c-shadow'>Subject :</span> {!! $estimate->subject !!}
					    	</caption>
					    @endif

				        <thead>
				            <tr>
				            	<th>#</th>
				                <th class='min-w-150'>Description</th>
				                <th class='min-w-100'>{!! $estimate->unique_unit !!}</th>
				                <th class='min-w-100'>Rate</th>				                				                
				                @if($estimate->discount_type == 'pre')
				                	@if($estimate->total_discount > 0)
				                		<th class='min-w-100'>Discount</th>
				                	@endif	

				                	@if($estimate->total_tax > 0)
				                		<th class='min-w-170'>Tax</th>
				                	@endif				                				                	
				                @else
				                	@if($estimate->total_tax > 0)
				                		<th class='min-w-100'>Tax</th>
				                	@endif

				                	@if($estimate->total_discount > 0)
				                		<th class='min-w-170'>Discount</th>
				                	@endif	
				                @endif				                	                
				                <th class='align-r min-w-100'>Amount</th>
				            </tr>
				        </thead>

				        <tbody class='item-sheet-body'>
				        	@foreach($estimate->itemsheets as $item_sheet)
				        		{!! $item_sheet->single_row_html !!}
				        	@endforeach
				        </tbody>

				        <tfoot class='tfoot-type-a'>
				        	<tr>
				        		<td colspan='{!! $estimate->tfoot_colspan !!}'></td>
				        		<td class='title top'>Summary</td>
				        		<td class='value'></td>
				        	</tr>

				        	@if($estimate->total_discount > 0 || $estimate->total_tax > 0)
				        		<tr class='double'>
				        			<td colspan='{!! $estimate->tfoot_colspan !!}'></td>
				        			<td class='title'>Sub Total<br><span class='shadow'>without discounts and taxes</span></td>
				        			<td class='value'><span class='symbol'>{!! $estimate->currency->symbol !!}</span>{!! $estimate->sub_total_format !!}</td>
				        		</tr>
				        	@else
				        		<tr>
				        			<td colspan='{!! $estimate->tfoot_colspan !!}'></td>
				        			<td class='title'>Sub Total</td>
				        			<td class='value'><span class='symbol'>{!! $estimate->currency->symbol !!}</span>{!! $estimate->sub_total_format !!}</td>
				        		</tr>
				        	@endif

				        	@if($estimate->discount_type == 'pre')
				        		@if($estimate->total_discount > 0)
					        		<tr>
					        			<td colspan='{!! $estimate->tfoot_colspan !!}'></td>
					        			<td class='title'>Total Discount</td>
					        			<td class='value'><span class='symbol'>{!! $estimate->currency->symbol !!}</span>{!! $estimate->total_discount_format !!}</td>
					        		</tr>
				        		@endif

				        		@if($estimate->total_tax > 0)
					        		<tr>
					        			<td colspan='{!! $estimate->tfoot_colspan !!}'></td>
					        			<td class='title'>Total Tax</td>
					        			<td class='value'><span class='symbol'>{!! $estimate->currency->symbol !!}</span>{!! $estimate->total_tax_format !!}</td>
					        		</tr>
				        		@endif
				        	@else
				        		@if($estimate->total_tax > 0)
					        		<tr>
					        			<td colspan='{!! $estimate->tfoot_colspan !!}'></td>
					        			<td class='title'>Total Tax</td>
					        			<td class='value'><span class='symbol'>{!! $estimate->currency->symbol !!}</span>{!! $estimate->total_tax_format !!}</td>
					        		</tr>
				        		@endif
				        		
				        		@if($estimate->total_discount > 0)
					        		<tr>
					        			<td colspan='{!! $estimate->tfoot_colspan !!}'></td>
					        			<td class='title'>Total Discount</td>
					        			<td class='value'><span class='symbol'>{!! $estimate->currency->symbol !!}</span>{!! $estimate->total_discount_format !!}</td>
					        		</tr>
				        		@endif
				        	@endif

				        	<tr>
				        		<td colspan='{!! $estimate->tfoot_colspan !!}'></td>
				        		<td class='title'>Adjustment</td>
				        		<td class='value'><span class='symbol'>{!! $estimate->currency->symbol !!}</span>{!! $estimate->adjustment_format !!}</td>
				        	</tr>

				        	<tr>
				        		<td colspan='{!! $estimate->tfoot_colspan !!}'></td>
				        		<td colspan='2' class='title bold-space attention'><span class='left-justify'>Total</span> <span class='value'><span class='symbol'>{!! $estimate->currency->symbol !!}</span>{!! $estimate->grand_total_format !!}</span></td>
				        	</tr>
				        </tfoot>
				    </table>
				</div> <!-- end table-responsive -->  
			</div>
		</div> <!-- end full -->

		<div class='full'>
			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
				<div class='div-type-q first'>
					<h3>Note</h3>
					{!! $estimate->note !!}
				</div>	

				<div class='div-type-q'>
					<h3>Terms &#38; Condition</h3>
					{!! $estimate->term_condition !!}
				</div>	

				<pre class='pre-type-a'>Public Access URL: http://crm.crispyapp.net/sale/estimate/view?id=12347&amp;token=3536463352364757856858585685765</pre>
			</div>
		</div> <!-- end full -->	
	</div> <!-- end row -->	

@endsection