@extends('layouts.default')

@section('content')
	<div class='row margin-zero'>
		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
			<div class='full'>
				<h4 class='title-type-a bold near'>{!! $page['item_title'] !!}</h4>

				<div class='right-top'>
					<div class='btn-group light'>
						<a href='{!! route('admin.task.index') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Tabular'><i class='fa fa-list'></i></a>
						<a href='{!! route('admin.task.kanban') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Kanban'><i class='fa fa-align-left rotate-90'></i></a>
						<a href='{!! route('admin.task.calendar') !!}' class='btn thin btn-type-a active' data-toggle='tooltip' data-placement='bottom' title='Calendar'><i class='fa fa-calendar'></i></a>
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

			<div class='full padding-20-t-0'>
				<div class='calendar' data-url='{!! route('admin.task.calendar.data') !!}' data-position-url='{!! route('admin.task.calendar.update.position') !!}' data-route='{!! route('admin.task.index') !!}'></div>
			</div>  		
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