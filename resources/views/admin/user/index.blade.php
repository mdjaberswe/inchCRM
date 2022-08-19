@extends('templates.listing')

@section('panelbtn')	
	<div class='btn-group'>
		<a href='{!! route('admin.user.index') !!}' class='btn thin btn-type-a active' data-toggle='tooltip' data-placement='bottom' title='Tabular'><i class='fa fa-list'></i></a>
		<a href='{!! route('admin.user.profilecard') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Grid'><i class='fa fa-th-large'></i></a>
	</div>
	@permission('user.create')	
		<button type='button' class='btn btn-type-a'><i class='fa fa-envelope'></i> Send Invitation Email</button>
	@endpermission	
@endsection

@section('listingextend')
	@include('admin.user.partials.modal-password')
@endsection