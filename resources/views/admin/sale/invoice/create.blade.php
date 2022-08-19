@extends('layouts.master')

@section('content')

	<div class='row'>
		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
			<h4 class='title-type-a'>Add New Invoice</h4>

			{!! Form::open(['route' => 'admin.sale-invoice.store', 'method' => 'post', 'files' => true, 'class' => 'form-type-b']) !!}

				@include('admin.sale.invoice.partials.form')

			{!! Form::close() !!}
		</div> <!-- end div-type-a -->
	</div> <!-- end row -->

@endsection

@push('scripts')

	<script>
		$(document).ready(function()
		{
			$('.discount-type').val('pre').trigger('change');
			$('.grand-total').val('');
			$('.plain-grand-total').val('');
		});
	</script>		

	@include('admin.sale.partials.script')

@endpush