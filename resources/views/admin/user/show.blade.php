@extends('layouts.master')

@section('content')
	<div class='row single div-type-a'>
		@include('admin.user.partials.show-profile')
		@include('partials.tabs.tab-index')
	</div> <!-- end row -->
@endsection

@section('extend')	
	@include('admin.user.partials.modal-message')

	@if($staff->follow_command_rule)
		@include('admin.user.partials.modal-profile-picture')
	@endif	
@endsection

@push('scripts')
	@include('admin.user.partials.script')
	{!! HTML::script('js/tabs.js') !!}
@endpush