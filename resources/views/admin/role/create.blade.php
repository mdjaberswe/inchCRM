@extends('layouts.master')

@section('content')

	<div class='row'>
	    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
	        <h4 class='title-type-a'>{!! $page['item_title'] !!}</h4>

	        {!! Form::open(['route' => 'admin.role.store', 'method' => 'post', 'class' => 'form-type-b']) !!}

	        	@include('admin.role.partials.form')

	        {!! Form::close() !!}
	    </div> <!-- end div-type-a -->
	</div> <!-- end row -->

@endsection

@push('scripts')

	<script>
		$(document).ready(function()
		{
			$('.switch').find('input').prop('checked', false);
			$('.para-type-c').find('input').prop('checked', false);
		});
	</script>		
	
	@include('admin.role.partials.script')

@endpush