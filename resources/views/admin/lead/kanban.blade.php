@extends('layouts.default')

@section('content')
	<div class='row margin-zero'>
		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
			<div class='full'>
				<h4 class='title-type-a bold near'>{!! $page['item_title'] !!}</h4>

				<div class='right-top'>
					<div class='btn-group light'>
						<a href='{!! route('admin.lead.index') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Tabular'><i class='fa fa-list'></i></a>
						<a href='{!! route('admin.lead.kanban') !!}' class='btn thin btn-type-a active' data-toggle='tooltip' data-placement='bottom' title='Kanban'><i class='fa fa-align-left rotate-90'></i></a>
						<a href='{!! route('admin.lead.report') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Report'><i class='fa fa-line-chart'></i></a>
					</div>

					<button type='button' class='btn btn-type-a only-icon' data-toggle='tooltip' data-placement='bottom' title='Filter'><i class='fa fa-filter'></i></button>

					@permission('import.lead')
						<button type='button' class='btn btn-type-a only-icon import-btn'  data-item='lead' data-url='{!! route('admin.import.csv') !!}' data-toggle='tooltip' data-placement='bottom' title='Import Leads'><i class='mdi mdi-file-excel pe-va'></i></button>
					@endpermission

					@permission('lead.create')	
	        			<button type='button' id='add-new-btn' class='btn btn-type-a'><i class='fa fa-user-plus'></i> Add Lead</button>
		        	@endpermission
				</div>	
			</div> <!-- end full -->
			
			<div class='full funnel-wrap'>
				<div class='full funnel-container scroll-box-x only-thumb' data-source='lead' data-stage='lead_stage_id' data-order='desc'>
					@foreach($leads_kanban as $key => $lead_kanban)					
						<div id='{!! $key !!}' class='funnel-stage' data-stage='{!! $lead_kanban['stage']['id'] !!}' data-count='{!! count($lead_kanban['data']) !!}' data-load='{!! $lead_kanban['stage']['load_status'] !!}' data-url='{!! $lead_kanban['stage']['load_url'] !!}'>
							<div class='funnel-stage-header'>
								<h3 class='title'>{!! $lead_kanban['stage']['name'] !!} <span class='shadow count'>({!! count($lead_kanban['data']) !!})</span></h3>
								<div class='funnel-arrow'><span></span></div>
							</div> <!-- end funnel-stage-header -->

							<div class='funnel-card-container scroll-box only-thumb' data-card-type='lead'>
								<ul class='kanban-list'>
									<div id='{!! $key . '-cards' !!}' class='full li-container'>
										@foreach($lead_kanban['quick_data'] as $lead)
											{!! $lead->kanban_card_html !!}
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
	{!! Form::open(['route' => 'admin.lead.store', 'method' => 'post', 'class' => 'form-type-a']) !!}
	    @include('admin.lead.partials.form', ['form' => 'create'])
	{!! Form::close() !!}
@endsection

@section('modaledit')
	{!! Form::open(['route' => ['admin.lead.update', null], 'method' => 'put', 'class' => 'form-type-a']) !!}
	    @include('admin.lead.partials.form', ['form' => 'edit'])
	{!! Form::close() !!}
@endsection

@section('extend')	
	@include('admin.lead.partials.modal-convert')
@endsection

@push('scripts')
	@include('admin.lead.partials.script')
@endpush	