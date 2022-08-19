@extends('layouts.master')

@section('content')
	<div class='row'>
		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
			<h4 class='title-type-a'>Add New Account</h4>
			{!! Form::open(['route' => 'admin.account.store', 'method' => 'post', 'id' => 'add-account', 'class' => 'form-type-b', 'files' => true]) !!}
				@include('admin.account.partials.form')
			{!! Form::close() !!}
		</div> <!-- end div-type-a -->
	</div> <!-- end row -->
@endsection