@extends('templates.listing')

@section('panelbtn')
	<div class='btn-group light'>
		<a href='{!! route('admin.deal.index') !!}' class='btn thin btn-type-a active' data-toggle='tooltip' data-placement='bottom' title='Tabular'><i class='fa fa-list'></i></a>
		<a href='{!! route('admin.deal.kanban') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Kanban'><i class='fa fa-align-left rotate-90'></i></a>
		<a href='{!! route('admin.deal.report') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Report'><i class='fa fa-line-chart'></i></a>
	</div>

	<button type='button' class='btn btn-type-a only-icon' data-toggle='tooltip' data-placement='bottom' title='Filter'><i class='fa fa-filter'></i></button>

	@permission('import.deal')
		<button type='button' class='btn btn-type-a only-icon import-btn'  data-item='deal' data-url='{!! route('admin.import.csv') !!}' data-toggle='tooltip' data-placement='bottom' title='Import Deals'><i class='mdi mdi-file-excel pe-va'></i></button>
	@endpermission
@endsection