@extends('templates.listing')

@section('panelbtn')
	@permission('sale.invoice.create')
		<a href='{!! route('admin.sale-invoice.create') !!}' class='btn btn-type-a'>
			<i class='fa fa-plus-circle'></i> Add Invoice
		</a>
	@endpermission
@endsection