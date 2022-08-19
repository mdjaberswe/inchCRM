@extends('layouts.default')

@section('content')
	<div class='row margin-zero'>
		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
			<div class='full'>
				<h4 class='title-type-a bold near'>{!! $page['item_title'] !!}</h4>

				<div class='right-top'>
					<div class='btn-group light'>
						<a href='{!! route('admin.task.index') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Tabular'><i class='fa fa-list'></i></a>
						<a href='{!! route('admin.task.kanban') !!}' class='btn thin btn-type-a active' data-toggle='tooltip' data-placement='bottom' title='Kanban'><i class='fa fa-align-left rotate-90'></i></a>
						<a href='{!! route('admin.task.calendar') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Calendar'><i class='fa fa-calendar'></i></a>
						<a href='{!! route('admin.deal.report') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Report'><i class='fa fa-line-chart'></i></a>
					</div>

					<button type='button' class='btn btn-type-a only-icon' data-toggle='tooltip' data-placement='bottom' title='Filter'><i class='fa fa-filter'></i></button>

					@permission('import.task')
						<button type='button' class='btn btn-type-a only-icon import-btn'  data-item='task' data-url='{!! route('admin.import.csv') !!}' data-toggle='tooltip' data-placement='bottom' title='Import Tasks'><i class='mdi mdi-file-excel pe-va'></i></button>
					@endpermission

					@permission('task.create')	
	        			<button type='button' id='add-new-btn' class='btn btn-type-a'><i class='fa fa-plus-circle'></i> Add Task</button>
		        	@endpermission
				</div>	
			</div> <!-- end full -->
			
			<div class='full funnel-wrap'>
				<div class='full funnel-container scroll-box-x only-thumb' data-source='task' data-stage='task_status_id' data-order='desc'>
					@foreach($tasks_kanban as $key => $task_kanban)					
						<div id='{!! $key !!}' class='funnel-stage' data-stage='{!! $task_kanban['status']['id'] !!}' data-count='{!! count($task_kanban['data']) !!}' data-load='{!! $task_kanban['status']['load_status'] !!}' data-url='{!! $task_kanban['status']['load_url'] !!}'>
							<div class='funnel-stage-header'>
								<h3 class='title'>
									{!! $task_kanban['status']['name'] !!} <span class='shadow count'>({!! count($task_kanban['data']) !!})</span>
									<p class='stat'>{!! $task_kanban['status']['completion_percentage'] !!}<i>%</i></p>
								</h3>								
								<div class='funnel-arrow bullet'><span class='bullet'></span></div>
							</div> <!-- end funnel-stage-header -->

							<div class='funnel-card-container scroll-box only-thumb' data-card-type='task'>								
								<ul class='kanban-list'>
									<div id='{!! $key . '-cards' !!}' class='full li-container'>									
										@foreach($task_kanban['quick_data'] as $task)
											{!! $task->kanban_card_html !!}
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
	{!! Form::open(['route' => 'admin.task.store', 'method' => 'post', 'class' => 'form-type-a']) !!}
	    @include('admin.task.partials.form', ['form' => 'create'])
	{!! Form::close() !!}
@endsection

@section('modaledit')
	{!! Form::open(['route' => ['admin.task.update', null], 'method' => 'put', 'class' => 'form-type-a']) !!}
	    @include('admin.task.partials.form', ['form' => 'edit'])
	{!! Form::close() !!}
@endsection	