@extends('templates.listing')

@section('panelbtn')
	@permission('sale.estimate.create')
		<a href='{!! route('admin.sale-estimate.create') !!}' class='btn btn-type-a'>
			<i class='fa fa-plus-circle'></i> Add Estimate
		</a>
	@endpermission
@endsection