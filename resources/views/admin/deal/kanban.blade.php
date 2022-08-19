@extends('layouts.default')

@section('content')
	<div class='row margin-zero'>
		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
			<div class='full'>
				<h4 class='title-type-a bold near'>{!! $page['item_title'] !!}</h4>

				<div class='right-top'>
					<div class='para-group'>
						<p><span class='para-label'>TOTAL DEALS</span> <br> <span data-realtime='total_deal'>{!! $total_info['total_deal'] !!}</span></p>
						<p><span class='para-label'>TOTAL AMOUNT</span> <br> <span data-realtime='total_amount'>{!! $total_info['total_amount_html'] !!}</span></p>
						<p><span class='para-label'>REVENUE FORECAST</span> <br> <span data-realtime='revenue_forecast'>{!! $total_info['total_forecast_html'] !!}</span></p>
					</div>

					<div class='btn-group light'>
						<a href='{!! route('admin.deal.index') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Tabular'><i class='fa fa-list'></i></a>
						<a href='{!! route('admin.deal.kanban') !!}' class='btn thin btn-type-a active' data-toggle='tooltip' data-placement='bottom' title='Kanban'><i class='fa fa-align-left rotate-90'></i></a>
						<a href='{!! route('admin.deal.report') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Report'><i class='fa fa-line-chart'></i></a>
					</div>

					<button type='button' class='btn btn-type-a only-icon' data-toggle='tooltip' data-placement='bottom' title='Filter'><i class='fa fa-filter'></i></button>

					@permission('import.deal')
						<button type='button' class='btn btn-type-a only-icon import-btn'  data-item='deal' data-url='{!! route('admin.import.csv') !!}' data-toggle='tooltip' data-placement='bottom' title='Import Deals'><i class='mdi mdi-file-excel pe-va'></i></button>
					@endpermission

					@permission('deal.create')	
	        			<button type='button' id='add-new-btn' class='btn btn-type-a' data-default='{!! 'deal_pipeline_id:' . $current_pipeline->id !!}'><i class='fa fa-plus-circle'></i> Add Deal</button>
		        	@endpermission
				</div>	
			</div> <!-- end full -->
			
			<div class='full funnel-wrap'>
				<div class='full funnel-container scroll-box-x only-thumb' data-source='deal' data-stage='deal_stage_id' data-order='desc'>
					@foreach($deals_kanban as $key => $deal_kanban)					
						<div id='{!! $key !!}' class='funnel-stage' data-stage='{!! $deal_kanban['stage']['id'] !!}' data-pipeline='{!! $deal_kanban['stage']['pipeline_id'] !!}' data-count='{!! count($deal_kanban['data']) !!}' data-load='{!! $deal_kanban['stage']['load_status'] !!}' data-url='{!! $deal_kanban['stage']['load_url'] !!}'>
							<div class='funnel-stage-header'>
								<h3 class='title double-line'>
									{!! $deal_kanban['stage']['name'] !!} <span class='shadow count'>({!! count($deal_kanban['data']) !!})</span>
									<br>
									<p class='sub-info'>{!! $deal_kanban['stage']['total_amount_html'] !!}</p>
									<p class='stat' data-toggle='tooltip' data-placement='left' data-html='true' title="{!! $deal_kanban['stage']['forecast_html'] !!}">{!! $deal_kanban['stage']['probability'] !!}<i>%</i></p>
								</h3>								
								<div class='funnel-arrow bullet'><span class='bullet'></span></div>
							</div> <!-- end funnel-stage-header -->

							<div class='funnel-card-container scroll-box only-thumb' data-card-type='deal'>								
								<ul class='kanban-list'>
									<div id='{!! $key . '-cards' !!}' class='full li-container'>									
										@foreach($deal_kanban['quick_data'] as $deal)
											{!! $deal->kanban_card_html !!}
										@endforeach
									</div>	

									<span class='content-loader bottom'></span>	
								</ul>
							</div> <!-- end funnel-card-container -->			
						</div> <!-- end funnel-stage -->
					@endforeach	

					<span class='content-loader all'></span>				
				</div> <!-- end funnel-container -->	
				<a class='funnel-container-arrow left'><i class='fa fa-chevron-left'></i></a>
				<a class='funnel-container-arrow right'><i class='fa fa-chevron-right'></i></a>
			</div> <!-- end funnel-wrap -->
		</div>
	</div>	
@endsection

@section('modalcreate')
	{!! Form::open(['route' => 'admin.deal.store', 'method' => 'post', 'class' => 'form-type-a']) !!}
	    @include('admin.deal.partials.form', ['form' => 'create'])
	{!! Form::close() !!}
@endsection

@section('modaledit')
	{!! Form::open(['route' => ['admin.deal.update', null], 'method' => 'put', 'class' => 'form-type-a']) !!}
	    @include('admin.deal.partials.form', ['form' => 'edit'])
	{!! Form::close() !!}
@endsection

@push('scripts')
	@include('admin.deal.partials.script')
@endpush	