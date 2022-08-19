<h4 class='title-type-a near'>Social Profiles</h4>

{!! Form::model($staff, ['route' => ['admin.user.info.update', $staff->id, 'social-profiles'], 'class' => 'form-type-b plain-line']) !!}
	<div class='form-group'>
		<label for='facebook' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Facebook</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('facebook', non_property_checker($staff->getSocialDataAttribute('facebook'), 'link'), ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'https://www.facebook.com/user_id']) !!}
			<span field='facebook' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='twitter' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Twitter</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('twitter', non_property_checker($staff->getSocialDataAttribute('twitter'), 'link'), ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'https://twitter.com/user_id']) !!}
			<span field='twitter' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->	

	<div class='form-group'>
		<label for='linkedin' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Linkedin</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('linkedin', non_property_checker($staff->getSocialDataAttribute('linkedin'), 'link'), ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'https://www.linkedin.com/user_id']) !!}
			<span field='linkedin' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='googleplus' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Google Plus</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('googleplus', non_property_checker($staff->getSocialDataAttribute('googleplus'), 'link'), ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'https://plus.google.com/user_id']) !!}
			<span field='googleplus' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='skype' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Skype</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('skype', non_property_checker($staff->getSocialDataAttribute('skype'), 'link'), ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'skype id']) !!}
			<span field='skype' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='snapchat' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Snapchat</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('snapchat', non_property_checker($staff->getSocialDataAttribute('snapchat'), 'link'), ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'snapchat id']) !!}
			<span field='snapchat' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='youtube' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>YouTube</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('youtube', non_property_checker($staff->getSocialDataAttribute('youtube'), 'link'), ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'https://www.youtube.com/user_id']) !!}
			<span field='youtube' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='instagram' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Instagram</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('instagram', non_property_checker($staff->getSocialDataAttribute('instagram'), 'link'), ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'https://www.instagram.com/user_id']) !!}
			<span field='instagram' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='pinterest' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Pinterest</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('pinterest', non_property_checker($staff->getSocialDataAttribute('pinterest'), 'link'), ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'https://www.pinterest.com/user_id']) !!}
			<span field='pinterest' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='github' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>GitHub</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('github', non_property_checker($staff->getSocialDataAttribute('github'), 'link'), ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'https://github.com/user_id']) !!}
			<span field='github' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='tumblr' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Tumblr</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('tumblr', non_property_checker($staff->getSocialDataAttribute('tumblr'), 'link'), ['class' => 'form-control ' . $input['class'], 'readonly' => $input['readonly'], 'placeholder' => 'https://www.tumblr.com/user_id']) !!}
			<span field='tumblr' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	{!! Form::hidden('id', $staff->id) !!}
	{!! Form::hidden('type', 'social-profiles') !!}

	@if($staff->follow_command_rule)
		<div class='form-group'>
		    <div class='col-xs-12 col-sm-offset-4 col-sm-8 col-md-offset-3 col-md-9 col-lg-offset-2 col-lg-10'>
		        {!! Form::submit('Save', ['name' => 'save', 'class' => 'save btn btn-primary']) !!}
		    </div>
		</div> <!-- end form-group -->
	@endif	
{!! Form::close() !!}