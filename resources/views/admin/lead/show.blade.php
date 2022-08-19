@extends('layouts.master')

@section('content')
	
	<div class='row  div-panel'>
		<div class='full div-panel-header  @if($lead->is_converted) padding-force-r-37 @endif'>
			<span class='lead-name none' data-realtime='first_name'>{!! $lead->name !!}</span>
			<span class='company-name none' data-realtime='company'>{!! $lead->company !!}</span>
			<span class='symbol none' value='{!! $lead->currency->id !!}' code='{!! $lead->currency->code !!}' icon='{!! currency_icon($lead->currency->code, $lead->currency->symbol) !!}'>{!! $lead->currency->solid_symbol !!}</span>

		    <div class='col-xs-12 col-sm-5 col-md-5 col-lg-6'>	    	
		    	<h4 class='title-panel'>{!! $page['item_title'] !!}</h4>
		    </div>

		    <div class='col-xs-12 col-sm-7 col-md-7 col-lg-6 xs-left-sm-right'>
		    	<div class='dropdown dark inline-block'>
		    		<a class='btn md btn-type-a first dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'>
		    			<i class='mdi mdi-plus-circle-multiple-outline'></i> Add...
		    		</a>

		    		<ul class='dropdown-menu up-caret'>		
		    			<li><a class='add-multiple' data-item='call' modal-title='Add Call Log' data-modalsize='medium' data-action='{!! route('admin.call.store') !!}' data-content='call.partials.form' data-default='{!! "client_type:lead|client_id:$lead->id" !!}' save-new='false'><i class='lg mdi mdi-phone-plus'></i> Add Call Log</a></li> 
		    			<li><a class='add-multiple' data-item='task' data-action='{!! route('admin.task.store') !!}' data-content='task.partials.form' data-default='related_type:lead|related_id:{!! $lead->id !!}' data-show='lead_id' save-new='false'><i class='fa fa-check-square'></i> Add Task</a></li>
		    			<li><a class='add-multiple' data-item='event' data-action='{!! route('admin.event.store') !!}' data-content='event.partials.form' data-default='related:lead|lead_id:{!! $lead->id !!}' data-show='lead_id' save-new='false'><i class='fa fa-calendar'></i> Add Event</a></li>
		    			<li><a class='add-multiple' modal-title='Add Items to Lead' modal-sub-title='{!! $lead->complete_name !!}' modal-datatable='true' datatable-url='{!! 'item-data/lead/' . $lead->id !!}' data-action='{!! route('admin.cart.item.add', ['lead', $lead->id]) !!}' data-content='item.partials.modal-add-item' data-default='{!! 'linked_type:lead|linked_id:' . $lead->id !!}' save-new='false' save-txt='Add to Lead'><i class='fa fa-shopping-cart'></i> Add Item</a></li>
		    			<li><a class='add-multiple' modal-title='Add Campaigns to Lead' modal-sub-title='{!! $lead->complete_name !!}' modal-datatable='true' datatable-url='{!! 'campaign-data-select/lead/' . $lead->id !!}' data-action='{!! route('admin.member.campaign.add', ['lead', $lead->id]) !!}' data-content='campaign.partials.modal-add-campaign' data-default='{!! 'member_type:lead|member_id:' . $lead->id !!}' save-new='false' save-txt='Add to Lead'><i class='fa fa-bullhorn'></i> Add Campaign</a></li>		    			
		    			<li><a class='add-multiple' data-item='file' data-action='{!! route('admin.file.store') !!}' data-content='partials.modals.upload-file' data-default='linked_type:lead|linked_id:{!! $lead->id !!}' save-new='false' data-modalsize='medium' modal-title='Add Files'><i class='lg mdi mdi-file-plus'></i> Add File</a></li>
		    			<li><a class='add-multiple' data-item='link' data-action='{!! route('admin.link.store') !!}' data-content='partials.modals.add-link' data-default='linked_type:lead|linked_id:{!! $lead->id !!}' save-new='false' data-modalsize='' modal-title='Add Link'><i class='fa fa-link'></i> Add Link</a></li>
		    		</ul>
		    	</div>

		    	@if(!$lead->is_converted)
		    		<a class='btn btn-type-a convert' editid='{!! $lead->id !!}'><i class='mdi mdi-account-convert'></i> Convert</a>
		    	@endif

		    	<div class='dropdown dark inline-block'>
		    		<a class='btn thiner btn-type-a dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'>
		    			<i class='mdi mdi-dots-vertical fa-md pe-va'></i>
		    		</a>

		    		<ul class='dropdown-menu up-caret'>		    			
		    			<li><a><i class='fa fa-send-o sm'></i> Send Email</a></li>
		    			<li><a><i class='mdi mdi-message sm'></i> Send SMS</a></li>
		    			<li>
							{!! Form::open(['route' => ['admin.lead.destroy', $lead->id], 'method' => 'delete']) !!}
								{!! Form::hidden('id', $lead->id) !!}
								{!! Form::hidden('redirect', true) !!}
								<button type='submit' class='delete'><i class='mdi mdi-delete'></i> Delete</button>
				  			{!! Form::close() !!}
		    			</li>
		    		</ul>
		    	</div>

		    	<div class='inline-block prev-next'>
		    		<a @if($lead->prev_record) href='{!! route('admin.lead.show', $lead->prev_record->id) !!}' @endif class='inline-block prev @if(is_null($lead->prev_record)) disabled @endif' data-toggle='tooltip' data-placement='{!! $lead->is_converted ? 'bottom' : 'top' !!}' title='Previous&nbsp;Record'><i class='pe pe-7s-angle-left pe-va'></i></a>
		    		<a @if($lead->next_record) href='{!! route('admin.lead.show', $lead->next_record->id) !!}' @endif class='inline-block next @if(is_null($lead->next_record)) disabled @endif' data-toggle='tooltip' data-placement='{!! $lead->is_converted ? 'bottom' : 'top' !!}' title='Next&nbsp;Record'><i class='pe pe-7s-angle-right pe-va'></i></a>
		    	</div>
		    </div>

		    @if($lead->is_converted)
		    	<div class='ribbon warning'>
		    		<span>CONVERTED</span>
		    	</div>
		    @endif
		</div> <!-- end full -->

		@include('partials.tabs.tab-index')
		    
	</div> <!-- end row -->    

@endsection

@section('modals')
	@include('partials.modals.delete')
	@include('partials.modals.access')
	@include('admin.lead.partials.modal-convert')
@endsection

@push('scripts')
	@include('admin.lead.partials.script')
	{!! HTML::script('js/tabs.js') !!}
@endpush