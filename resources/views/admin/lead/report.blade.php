@extends('layouts.default')

@section('content')
	<div class='row margin-zero'>
		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
			<div class='full'>
				<h4 class='title-type-a bold'>{!! $page['item_plural'] or $page['item'] !!} Report</h4>

				<div class='right-top'>
					<div class='btn-group light'>
						<a href='{!! route('admin.lead.index') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Tabular'><i class='fa fa-list'></i></a>
						<a href='{!! route('admin.lead.kanban') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Kanban'><i class='fa fa-align-left rotate-90'></i></a>
						<a href='{!! route('admin.lead.report') !!}' class='btn thin btn-type-a active' data-toggle='tooltip' data-placement='bottom' title='Report'><i class='fa fa-line-chart'></i></a>
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
			
			<div class='full padding-0-5'>
				<div class='full'>
					<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 chart-box'>
						<h3 class='title-border-sm-bold'>
							Lead Funnel
							<span class='filter-info'>Reporting period: <span data-realtime='lead-time-period'>{!! $lead_report['lead_funnel']['timeperiod_display'] !!}</span></span>
						</h3>

						<div class='right-top custom-a'>
							<a class='common-edit-btn bg-light' modal-title='Parameters (Lead Funnel)' data-url='{!! route('admin.lead.report.filter', 'lead_funnel') !!}' data-posturl='{!! route('admin.lead.post.report.filter', 'lead_funnel') !!}' editid='lead_funnel' modal-small='true'><i class='fa fa-cog'></i></a>
						</div>

						<div class='full chart'>
							<div id='lead-d3-funnel' class='d3-funnel' data-funnel='{!! $lead_report['lead_funnel']['data'] or null !!}' data-pinched='{!! $lead_report['lead_funnel']['pinched'] or 2 !!}'></div>
						</div>
					</div>

					<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 chart-box'>
						<h3 class='title-border-sm-bold'>
							Lead Sources
							<span class='filter-info'>Reporting period: <span data-realtime='lead-source-time-period'>{!! $lead_report['lead_pie_source']['timeperiod_display'] !!}</span></span>
						</h3>		

						<div class='right-top custom-a'>
							<a class='common-edit-btn bg-light' modal-title='Parameters (Lead Source)' data-url='{!! route('admin.lead.report.filter', 'lead_pie_source') !!}' data-posturl='{!! route('admin.lead.post.report.filter', 'lead_pie_source') !!}' editid='lead_pie_source' modal-small='true'><i class='fa fa-cog'></i></a>
						</div>		

						<div class='full chart padd-top-xs-0-md-30'>
							<canvas id='lead-source-pie' class='chart-js-pie' data-pie='{!! $lead_report['lead_pie_source']['string_leads_count'] !!}' data-label='{!! $lead_report['lead_pie_source']['string_names'] !!}' data-background='{!! $lead_report['lead_pie_source']['string_background'] !!}'></canvas>
						</div>	
					</div>
				</div> <!-- end full -->	

				<div class='full'>
					<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 padd-right-xs-15-md-0 chart-box'>
						<div class='full chart-stat bg-top'>
							<h3 class='title-border-sm-bold space'>
								Number of Active Leads
								<span class='filter-info bright'>Reporting period: <span data-realtime='lead-stat-time-period'>{!! $lead_report['lead_stat']['timeperiod_display'] !!}</span></span>
							</h3>

							<div class='right-top custom-b'>
								<a class='common-edit-btn bg-light shadow' modal-title='Parameters (Number of Leads)' data-url='{!! route('admin.lead.report.filter', 'lead_stat') !!}' data-posturl='{!! route('admin.lead.post.report.filter', 'lead_stat') !!}' editid='lead_stat' modal-small='true'><i class='fa fa-cog'></i></a>
							</div>

							<p class='stat'><a data-realtime='active-lead'>{!! $lead_report['lead_stat']['active_leads'] !!}</a></p>
						</div>

						<div class='full'>
							<div class='half chart-stat bg-bottom-a'>
								<h3 class='title-border-sm-bold space'>Number of Converted Leads</h3>
								<p class='stat'><a data-realtime='converted-lead'>{!! $lead_report['lead_stat']['converted_leads'] !!}</a></p>
							</div>

							<div class='half chart-stat bg-bottom-b'>
								<h3 class='title-border-sm-bold space'>Number of Lost Leads</h3>
								<p class='stat white'><a data-realtime='lost-lead'>{!! $lead_report['lead_stat']['lost_leads'] !!}</a></p>
							</div>
						</div>
					</div>

					<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 chart-box'>
						<div class='full chart-stat single m-bottom-force-15 bg-success'>
							<h3 class='title-border-sm-bold space'>
								Conversion Rate
								<span class='filter-info bright'>Reporting period: <span data-realtime='lead-conversion-time-period'>{!! $lead_report['lead_conversion']['timeperiod_display'] !!}</span></span>
							</h3>

							<div class='right-top custom-b'>
								<a class='common-edit-btn bg-light shadow' modal-title='Parameters (Lead Conversion Rate)' data-url='{!! route('admin.lead.report.filter', 'lead_conversion') !!}' data-posturl='{!! route('admin.lead.post.report.filter', 'lead_conversion') !!}' editid='lead_conversion' modal-small='true'><i class='fa fa-cog'></i></a>
							</div>

							<p class='stat white' data-realtime='lead-conversion'>{!! $lead_report['lead_conversion']['conversion'] !!}</p>
						</div>

						<div class='full chart-stat single bg-danger'>
							<h3 class='title-border-sm-bold space'>
								Lost Rate
								<span class='filter-info bright'>Reporting period: <span data-realtime='lead-lost-rate-time-period'>{!! $lead_report['lost_lead_rate']['timeperiod_display'] !!}</span></span>
							</h3>

							<div class='right-top custom-b'>
								<a class='common-edit-btn bg-light shadow' modal-title='Parameters (Lost Lead Rate)' data-url='{!! route('admin.lead.report.filter', 'lost_lead_rate') !!}' data-posturl='{!! route('admin.lead.post.report.filter', 'lost_lead_rate') !!}' editid='lost_lead_rate' modal-small='true'><i class='fa fa-cog'></i></a>
							</div>

							<p class='stat white' data-realtime='lead-lost-rate'>{!! $lead_report['lost_lead_rate']['lost_lead_rate'] !!}</p>
						</div>
					</div>
				</div> <!-- end full -->	

				<div class='full'>
					<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 chart-box'>
						<h3 class='title-border-sm-bold'>
							Conversion Timeline
							<span class='filter-info'>Reporting period: <span data-realtime='lead-conversion-time-line-period'>{!! $lead_report['lead_conversion_timeline']['timeperiod_display'] !!}</span></span>
						</h3>

						<div class='right-top custom-a'>
							<a class='common-edit-btn bg-light' modal-title='Parameters (Lead Conversion Timeline)' data-url='{!! route('admin.lead.report.filter', 'lead_conversion_timeline') !!}' data-posturl='{!! route('admin.lead.post.report.filter', 'lead_conversion_timeline') !!}' editid='lead_conversion_timeline' modal-small='true'><i class='fa fa-cog'></i></a>
						</div>

						<div class='full chart'>
							<canvas id='lead-conversion-timeline' class='chart-js-timeline' data-suffix='%' data-min='0' data-max='100' data-step='5' data-step-size='20' data-days='{!! $lead_report['lead_conversion_timeline']['string_days'] !!}' data-years='{!! $lead_report['lead_conversion_timeline']['string_years'] !!}' data-labels-y='{!! $lead_report['lead_conversion_timeline']['string_labels'] !!}' data-backgrounds-y='{!! $lead_report['lead_conversion_timeline']['string_backgrounds'] !!}' data-borders-y='{!! $lead_report['lead_conversion_timeline']['string_borders'] !!}' data-y1='{!! $lead_report['lead_conversion_timeline']['string_conversion'] !!}' data-y2='{!! $lead_report['lead_conversion_timeline']['string_lost_rate'] !!}'></canvas>
						</div>
					</div>
				</div> <!-- end full -->

				<div class='full'>
					<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 m-bottom-20'>
						<div class='full chart-stat min-h bg-board'>
							<h3 class='title-border-sm-bold space'>
								Leads Converted Leaderboard
								<span class='filter-info bright'>Reporting period: <span data-realtime='lead-converted-leaderboard-time-period'>{!! $lead_report['lead_converted_leaderboard']['timeperiod_display'] !!}</span></span>
							</h3>

							<div class='right-top custom-b'>
								<a class='common-edit-btn bg-light shadow' modal-title='Parameters (Leads Converted Leaderboard)' data-url='{!! route('admin.lead.report.filter', 'lead_converted_leaderboard') !!}' data-posturl='{!! route('admin.lead.post.report.filter', 'lead_converted_leaderboard') !!}' editid='lead_converted_leaderboard' modal-small='true'><i class='fa fa-cog'></i></a>
							</div>

							<div class='full'>
								<div class='col-xs-12 col-sm-12 col-md-4 col-lg-6' data-realtime='rank-html-1'>
									{!! $lead_report['lead_converted_leaderboard']['rank_html1'] !!}
								</div> <!-- end rank -->

								<div class='col-xs-12 col-sm-12 col-md-4 col-lg-3' data-realtime='rank-html-2'>
									{!! $lead_report['lead_converted_leaderboard']['rank_html2'] !!}
								</div> <!-- end rank -->	

								<div class='col-xs-12 col-sm-12 col-md-4 col-lg-3' data-realtime='rank-html-3'>
									{!! $lead_report['lead_converted_leaderboard']['rank_html3'] !!}
								</div> <!-- end rank -->	
							</div>
						</div> <!-- end chart-stat -->
					</div>
				</div> <!-- end full -->	
			</div> <!-- end full -->
		</div>
	</div>	
@endsection

@section('modalcreate')
	{!! Form::open(['route' => 'admin.lead.store', 'method' => 'post', 'class' => 'form-type-a']) !!}
	    @include('admin.lead.partials.form', ['form' => 'create'])
	{!! Form::close() !!}
@endsection

@push('scripts')
	@include('admin.lead.partials.script')
@endpush	