@extends('templates.listing')

@section('panelbtn')
	@permission('role.create')
		<a href="{!! route('admin.role.create') !!}" class='btn btn-type-a'>
			<i class='fa fa-plus-circle'></i> Add New Role
		</a>
	@endpermission
@endsection

@section('listingextend')
	@include('admin.role.partials.modal-role-users')
@endsection