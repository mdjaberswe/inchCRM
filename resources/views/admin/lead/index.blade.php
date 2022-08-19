@extends('templates.listing')

@section('panelbtn')
	<div class='btn-group light'>
		<a href='{!! route('admin.lead.index') !!}' class='btn thin btn-type-a active' data-toggle='tooltip' data-placement='bottom' title='Tabular'><i class='fa fa-list'></i></a>
		<a href='{!! route('admin.lead.kanban') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Kanban'><i class='fa fa-align-left rotate-90'></i></a>
		<a href='{!! route('admin.lead.report') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Report'><i class='fa fa-line-chart'></i></a>
	</div>

	<button type='button' class='btn btn-type-a only-icon' data-toggle='tooltip' data-placement='bottom' title='Filter'><i class='fa fa-filter'></i></button>

	@permission('import.lead')
		<button type='button' class='btn btn-type-a only-icon import-btn'  data-item='lead' data-url='{!! route('admin.import.csv') !!}' data-toggle='tooltip' data-placement='bottom' title='Import Leads'><i class='mdi mdi-file-excel pe-va'></i></button>
	@endpermission
@endsection

@section('listingextend')
	@include('admin.lead.partials.modal-convert')
@endsection