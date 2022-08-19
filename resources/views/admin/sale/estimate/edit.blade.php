@extends('layouts.master')

@section('content')

	<div class='row'>
		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
			<h4 class='title-type-a'>Edit Estimate : {!! 'EST #' . $estimate->number_format . ' ' . $estimate->subject !!}</h4>

			{!! Form::model($estimate, ['route' => ['admin.sale-estimate.update', $estimate->id], 'method' => 'put', 'files' => true, 'class' => 'form-type-b']) !!}

				@include('admin.sale.estimate.partials.form')

			{!! Form::close() !!}
		</div> <!-- end div-type-a -->
	</div> <!-- end row -->

@endsection

@push('scripts')

	@include('admin.sale.partials.script')

@endpush