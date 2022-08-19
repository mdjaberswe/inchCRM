@extends('templates.listing')

@section('panelbtn')
	<div class='btn-group light'>
		<a href='{!! route('admin.contact.index') !!}' class='btn thin btn-type-a active' data-toggle='tooltip' data-placement='bottom' title='Tabular'><i class='fa fa-list'></i></a>
		<a href='{!! route('admin.lead.report') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Report'><i class='fa fa-line-chart'></i></a>
	</div>

	<button type='button' class='btn btn-type-a only-icon' data-toggle='tooltip' data-placement='bottom' title='Filter'><i class='fa fa-filter'></i></button>

	@permission('import.contact')
		<button type='button' class='btn btn-type-a only-icon import-btn'  data-item='contact' data-url='{!! route('admin.import.csv') !!}' data-toggle='tooltip' data-placement='bottom' title='Import Contacts'><i class='mdi mdi-file-excel pe-va'></i></button>
	@endpermission

	@permission('contact.create')	
		<button type='button' class='btn btn-type-a only-icon' data-toggle='tooltip' data-placement='bottom' title='Send Invitation'><i class='fa fa-send-o'></i></button>
	@endpermission
@endsection