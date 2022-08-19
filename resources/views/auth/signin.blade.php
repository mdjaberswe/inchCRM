@extends('layouts.auth')

@section('content')
	<div class='row'>
		<div id='auth-form-container' class='col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4'>
			<h3 class='title'><i class='fa fa-sign-in'></i> Sign In</h3>

			{!! Form::open(['route' => 'auth.signin.post', 'method' => 'post']) !!}

				<div class='form-group'>
					<label for='email' class='lbl'>Email</label>
					<i class='fa fa-envelope'></i>
					{!! Form::text('email', null, ['class' => 'input']) !!}
					{!! $errors->first('email', "<span class='validation-error'>:message</span>") !!}
				</div> <!-- end form-group -->

				<div class='form-group'>
					<label for='password' class='lbl'>Password</label>
					<i class='fa fa-key'></i>
					{!! Form::password('password', ['class' => 'input']) !!}
					{!! $errors->first('password', "<span class='validation-error'>:message</span>") !!}
				</div> <!-- end form-group -->

				<div class='form-group'>
					<div class='half'>
						<p class='para-type-c pretty danger smooth'>
							<input type='checkbox' name='remember_me' value='1'>
							<label><i class='mdi mdi-check'></i></label> 
							<span>Remember me</span>
						</p>
					</div>

					<div class='half'>
						<a href='' class='right-justify'>Forget Password?</a>
					</div>
				</div> <!-- end form-group -->

    			@if(\Session::has('danger_message'))
    				<div class='full'>
    					<p class='danger-message'>{!! \Session::get('danger_message') !!}</p>
    				</div>	
    			@endif

				<div class='form-group'>
					{!! Form::submit('Signin', ['class' => 'btn btn-type-a']) !!}
				</div> <!-- end form-group -->

			{!! Form::close() !!}
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		$(document).ready(function()
		{
			if($('.input').val() != '')
			{
				$('label').addClass('focus');
			}

			$('.input').on('focus input keyup keydown blur change', function()
			{
				if($(this).val() != '')
				{
					$(this).parent().find('label').addClass('focus');
					$(this).parent().find('i').addClass('focus');
					$(this).parent().find('input').addClass('focus');
				}
			});

			$('.input').focusout(function()
			{
				if($(this).val() == '')
				{
					$(this).parent().find('label').removeClass('focus');
				}
				$(this).parent().find('i').removeClass('focus');
				$(this).parent().find('input').removeClass('focus');
			});
		});
	</script>
@endpush		