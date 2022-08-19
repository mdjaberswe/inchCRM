@extends('layouts.default')

@section('content')

	<div class='row div-type-a'>
		<div class='full div-panel-header'>
		    <div class='col-xs-12 col-sm-4 col-md-5 col-lg-6'>	    	
		    	<h4 class='title-panel'>{!! $page['item_title'] !!}</h4>
		    </div>

		    <div class='col-xs-12 col-sm-8 col-md-7 col-lg-6 xs-left-sm-right'>
		    	<div class='dropdown dark inline-block'>
		    		<a class='btn btn-type-a first dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'>
		    			<i class='fa fa-plus-circle'></i> Add...
		    		</a>

		    		<ul class='dropdown-menu up-caret'>		    			
		    			<li><a class='add-multiple'><i class='fa fa-shopping-cart'></i> Add Item</a></li>
		    			<li><a class='add-multiple'><i class='fa fa-bullhorn'></i> Add Campaign</a></li>
		    			<li><a class='add-multiple' data-item='event' data-action='{!! route('admin.event.store') !!}' data-content='event.partials.form'><i class='fa fa-calendar'></i> Add Event</a></li>
		    			<li><a class='add-multiple'><i class='fa fa-book'></i> Add Note</a></li>
		    			<li><a class='add-multiple'><i class='fa fa-paperclip'></i> Attach File</a></li>
		    			<li><a class='add-multiple' data-item='task' data-action='{!! route('admin.task.store') !!}' data-content='task.partials.form'><i class='fa fa-check-square'></i> Add Task</a></li>
		    		</ul>
		    	</div>

		    	<a class='btn btn-type-a convert' editid='{!! $invoice->id !!}'><i class='mdi mdi-account-convert'></i> Convert</a>

		    	<div class='dropdown dark inline-block'>
		    		<a class='btn thiner btn-type-a dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'>
		    			<i class='mdi mdi-dots-vertical fa-md pe-va'></i>
		    		</a>

		    		<ul class='dropdown-menu up-caret'>		    			
		    			<li><a><i class='fa fa-send-o sm'></i> Send Email</a></li>
		    			<li><a><i class='mdi mdi-message sm'></i> Send SMS</a></li>
		    			<li>
							{!! Form::open(['route' => ['admin.sale-invoice.destroy', $invoice->id], 'method' => 'delete']) !!}
								{!! Form::hidden('id', $invoice->id) !!}
								{!! Form::hidden('redirect', true) !!}
								<button type='submit' class='delete'><i class='mdi mdi-delete'></i> Delete</button>
				  			{!! Form::close() !!}
		    			</li>
		    		</ul>
		    	</div>

		    	<div class='inline-block prev-next'>
		    		<a href='' class='inline-block prev' data-toggle='tooltip' data-placement='top' title='Previous&nbsp;Record'><i class='pe pe-7s-angle-left pe-va'></i></a>
		    		<a href='' class='inline-block next' data-toggle='tooltip' data-placement='top' title='Next&nbsp;Record'><i class='pe pe-7s-angle-right pe-va'></i></a>
		    	</div>
		    </div>
		</div> <!-- end full -->

		<div class="full">	
			<ul id="item-tab" class="menu-h"><li><a class="active" tabkey="overview">Overview</a></li><li><a class="" tabkey="items">Items</a></li><li><a class="" tabkey="campaigns">Campaigns</a></li><li><a class="" tabkey="events">Events</a></li><li><a class="" tabkey="notes">Notes</a></li><li><a class="" tabkey="attachments">Attachments</a></li><li><a class="" tabkey="tasks">Tasks</a></li><li><a class="" tabkey="emails">Emails</a></li><li><a class="" tabkey="sms">SMS</a></li><li><a class="" tabkey="activity-log">Activity Log</a></li></ul>
		</div>

{{-- 		<div class='full div-type-r'>
			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 xs-center-sm-left'>
				<a href='{!! route('admin.sale-invoice.edit', $invoice->id) !!}' class='btn btn-type-a'><i class='fa fa-pencil'></i> Edit Invoice</a>
				@permission('finance.payment.create')<button type='button' id='add-new-btn' class='btn btn-type-a'><i class='fa fa-money'></i> Record Payment</button>@endpermission
				<a href='#' class='btn btn-type-a'><i class='fa fa-send-o'></i> Send Email</a>
				<a href='#' class='btn btn-type-a'><i class='fa fa-envelope'></i> Send SMS</a>
				<a href='#' class='btn btn-type-a'><i class='fa fa-print'></i> Print</a>
				<a href='#' class='btn btn-type-a'><i class='fa fa-file-pdf-o'></i> PDF</a>
				<a href='#' class='btn btn-type-a'><i class='fa fa-globe'></i> Preview</a>
				<a href='#' class='btn btn-danger'><i class='fa fa-minus-circle'></i> Cancel</a>
			</div>
		</div> <!-- end full --> --}}

		<div class='full padding-top-20'>
			<div class='col-xs-12 col-sm-7 col-md-7 col-lg-8 xs-center-sm-left'>
				<img src='{!! asset('img/default-logo.png') !!}' alt='logo' class='img-type-b'>
			</div>

			<div class='col-xs-12 col-sm-5 col-md-5 col-lg-4 div-type-n xs-center-sm-right'>
				<h3 class='title-type-d' style='margin-top: 0'>INVOICE</h3>
				<p>SRN #{!! $invoice->number_format !!}</p>
				<p>Reference : {!! $invoice->reference !!}</p>
				<p>Gross Amount</p>
				<h4 class='title-type-e'><span class='symbol'>{!! $invoice->currency->symbol !!}</span>{!! $invoice->grand_total_format !!}</h4>
				{!! $invoice->status_html !!}
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
				<h4>{!! $invoice->account->account_name !!}</h4>
				<p>{!! $invoice->account->street !!}, {!! $invoice->account->city !!}</p>
				<p>{!! $invoice->account->state !!}, {!! $invoice->account->country->ascii_name !!}</p>
				<p>Phone: {!! $invoice->account->account_phone !!}</p>
				<p>Email: {!! $invoice->account->account_email !!}</p>
			</div>

			<div class='col-xs-12 col-sm-offset-2 col-sm-4 col-md-offset-3 col-md-3 col-lg-offset-3 col-lg-3 div-type-p xs-center-sm-left'>
				<p><span class='c-shadow'>Invoice Date : </span>{!! $invoice->invoice_date !!}</p>
				<p><span class='c-shadow'>Pay Before : </span>{!! $invoice->date_pay_before !!}</p>
				<p><span class='c-shadow'>Sale Agent : </span>{!! $invoice->saleagent->name !!}</p>
			</div>
		</div> <!-- end full -->

		<div class='full'>
			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
				<div class='table-responsive'>
				    <table class='table bg-none-a full'>
				    	@if(isset($invoice->subject) && $invoice->subject !='')
					    	<caption>
					    		<span class='c-shadow'>Subject :</span> {!! $invoice->subject !!}
					    	</caption>
					    @endif

				        <thead>
				            <tr>
				            	<th>#</th>
				                <th class='min-w-150'>Description</th>
				                <th class='min-w-100'>{!! $invoice->unique_unit !!}</th>
				                <th class='min-w-100'>Rate</th>				                				                
				                @if($invoice->discount_type == 'pre')
				                	@if($invoice->total_discount > 0)
				                		<th class='min-w-100'>Discount</th>
				                	@endif	

				                	@if($invoice->total_tax > 0)
				                		<th class='min-w-170'>Tax</th>
				                	@endif			                				                	
				                @else
				                	@if($invoice->total_tax > 0)
				                		<th class='min-w-100'>Tax</th>
				                	@endif

				                	@if($invoice->total_discount > 0)
				                		<th class='min-w-170'>Discount</th>
				                	@endif
				                @endif				                	                
				                <th class='align-r min-w-100'>Amount</th>
				            </tr>
				        </thead>

				        <tbody class='item-sheet-body'>
				        	@foreach($invoice->itemsheets as $item_sheet)
				        		{!! $item_sheet->single_row_html !!}
				        	@endforeach
				        </tbody>

				        <tfoot class='tfoot-type-a'>
				        	<tr>
				        		<td colspan='{!! $invoice->tfoot_colspan !!}'></td>
				        		<td class='title top'>Summary</td>
				        		<td class='value'></td>
				        	</tr>

				        	@if($invoice->total_discount > 0 || $invoice->total_tax > 0)
				        		<tr class='double'>
				        			<td colspan='{!! $invoice->tfoot_colspan !!}'></td>
				        			<td class='title'>Sub Total<br><span class='shadow'>without discounts and taxes</span></td>
				        			<td class='value'><span class='symbol'>{!! $invoice->currency->symbol !!}</span>{!! $invoice->sub_total_format !!}</td>
				        		</tr>
				        	@else
				        		<tr>
				        			<td colspan='{!! $invoice->tfoot_colspan !!}'></td>
				        			<td class='title'>Sub Total</td>
				        			<td class='value'><span class='symbol'>{!! $invoice->currency->symbol !!}</span>{!! $invoice->sub_total_format !!}</td>
				        		</tr>
				        	@endif

				        	@if($invoice->discount_type == 'pre')
        		        		@if($invoice->total_discount > 0)
        			        		<tr>
        			        			<td colspan='{!! $invoice->tfoot_colspan !!}'></td>
        			        			<td class='title'>Total Discount</td>
        			        			<td class='value'><span class='symbol'>{!! $invoice->currency->symbol !!}</span>{!! $invoice->total_discount_format !!}</td>
        			        		</tr>
        		        		@endif

        		        		@if($invoice->total_tax > 0)
        			        		<tr>
        			        			<td colspan='{!! $invoice->tfoot_colspan !!}'></td>
        			        			<td class='title'>Total Tax</td>
        			        			<td class='value'><span class='symbol'>{!! $invoice->currency->symbol !!}</span>{!! $invoice->total_tax_format !!}</td>
        			        		</tr>
        		        		@endif
				        	@else
        		        		@if($invoice->total_tax > 0)
        			        		<tr>
        			        			<td colspan='{!! $invoice->tfoot_colspan !!}'></td>
        			        			<td class='title'>Total Tax</td>
        			        			<td class='value'><span class='symbol'>{!! $invoice->currency->symbol !!}</span>{!! $invoice->total_tax_format !!}</td>
        			        		</tr>
        		        		@endif
        		        		
        		        		@if($invoice->total_discount > 0)
        			        		<tr>
        			        			<td colspan='{!! $invoice->tfoot_colspan !!}'></td>
        			        			<td class='title'>Total Discount</td>
        			        			<td class='value'><span class='symbol'>{!! $invoice->currency->symbol !!}</span>{!! $invoice->total_discount_format !!}</td>
        			        		</tr>
        		        		@endif
				        	@endif

				        	<tr>
				        		<td colspan='{!! $invoice->tfoot_colspan !!}'></td>
				        		<td class='title'>Adjustment</td>
				        		<td class='value'><span class='symbol'>{!! $invoice->currency->symbol !!}</span>{!! $invoice->adjustment_format !!}</td>
				        	</tr>

				        	<tr>
				        		<td colspan='{!! $invoice->tfoot_colspan !!}'></td>
				        		<td colspan='2' class='title bold-space attention'><span class='left-justify'>Total</span> <span class='value'><span class='symbol'>{!! $invoice->currency->symbol !!}</span>{!! $invoice->grand_total_format !!}</span></td>
				        	</tr>
				        </tfoot>
				    </table>
				</div> <!-- end table-responsive -->  
			</div>
		</div> <!-- end full -->

		<div class='full'>
			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
				<div class='div-type-q first border-none'>
					<h3>Credit Transactions</h3>
					@include('admin.sale.invoice.partials.payment')
				</div>	

				<div class='div-type-q'>
					<h3>Note</h3>
					{!! $invoice->note !!}
				</div>	

				<div class='div-type-q'>
					<h3>Terms &#38; Condition</h3>
					{!! $invoice->term_condition !!}
				</div>	

				<pre class='pre-type-a'>Public Access URL: http://crm.crispyapp.net/sale/estimate/view?id=12347&amp;token=3536463352364757856858585685765</pre>
			</div>
		</div> <!-- end full -->	
	</div> <!-- end row -->	

@endsection