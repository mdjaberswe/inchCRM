@extends('templates.listing')

@section('panelbtn')
	<div class='btn-group light'>
		<a href='{!! route('admin.deal.index') !!}' class='btn thin btn-type-a active' data-toggle='tooltip' data-placement='bottom' title='Tabular'><i class='fa fa-list'></i></a>
		<a href='{!! route('admin.deal.kanban') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Kanban'><i class='fa fa-align-left rotate-90'></i></a>
		<a href='{!! route('admin.deal.report') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Calendar'><i class='fa fa-calendar'></i></a>
		<a href='{!! route('admin.deal.report') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Report'><i class='fa fa-line-chart'></i></a>
	</div>

	<button type='button' class='btn btn-type-a only-icon' data-toggle='tooltip' data-placement='bottom' title='Filter'><i class='fa fa-filter'></i></button>

	@permission('import.deal')
		<button type='button' class='btn btn-type-a only-icon import-btn'  data-item='deal' data-url='{!! route('admin.import.csv') !!}' data-toggle='tooltip' data-placement='bottom' title='Import Activities'><i class='mdi mdi-file-excel pe-va'></i></button>
	@endpermission

	<div class='dropdown dark inline-block m-left-5'>
		<a class='btn md btn-type-a first dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'>
			<i class='mdi mdi-plus-circle-multiple-outline'></i> Add...
		</a>

		<ul class='dropdown-menu up-caret m-Top-5'>		
			<li><a class='add-multiple' data-item='call' modal-title='Add Call Log' data-modalsize='medium' data-action='{!! route('admin.call.store') !!}' data-content='call.partials.form' save-new='false'><i class='lg mdi mdi-phone-plus'></i> Add Call Log</a></li> 
			<li><a class='add-multiple' data-item='task' data-action='{!! route('admin.task.store') !!}' data-content='task.partials.form' save-new='false'><i class='fa fa-check-square'></i> Add Task</a></li>
			<li><a class='add-multiple' data-item='event' data-action='{!! route('admin.event.store') !!}' data-content='event.partials.form' save-new='false'><i class='fa fa-calendar'></i> Add Event</a></li>
		</ul>
	</div>

	<div class='dropdown dark inline-block m-left-5'>
		<a class='btn thiner btn-type-a dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'>
			<i class='mdi mdi-dots-vertical fa-md pe-va'></i>
		</a>

		<ul class='dropdown-menu up-caret m-Top-5'>		
			<li><a><i class='fa fa-send-o sm'></i> Send Email</a></li>
			<li><a><i class='mdi mdi-message sm'></i> Send SMS</a></li>
			<li><a href='{!!  route('admin.task.index') !!}'><i class='mdi mdi-playlist-check xx-lg'></i> Tasks List</a></li>
			<li><a><i class='mdi mdi-calendar-multiple-check lg'></i> Events List</a></li>
			<li><a><i class='mdi mdi-phone-log lg'></i> Calls List</a></li>
		</ul>
	</div>
@endsection