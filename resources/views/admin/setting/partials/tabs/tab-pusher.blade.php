<h4 class='title-type-a near'>Pusher Settings</h4>

{!! Form::open(['route' => 'admin.setting.update.pusher', 'class' => 'form-type-b']) !!}
	<div class='form-group'>
		<label for='pusher_app_id' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Pusher App Id <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('pusher_app_id', check_before_decrypt(config('setting.pusher_app_id')), ['class' => 'form-control']) !!}
			<span field='pusher_app_id' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='pusher_app_key' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>App Key <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('pusher_app_key', check_before_decrypt(config('setting.pusher_app_key')), ['class' => 'form-control']) !!}
			<span field='pusher_app_key' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='pusher_app_secret' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>App Secret <span class='c-danger'>*</span></label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('pusher_app_secret', check_before_decrypt(config('setting.pusher_app_secret')), ['class' => 'form-control']) !!}
			<span field='pusher_app_secret' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='pusher_cluster' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Pusher Cluster</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			{!! Form::text('pusher_cluster', config('setting.pusher_cluster'), ['class' => 'form-control']) !!}
			<span field='pusher_cluster' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='realtime_notification' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Real&nbsp;Time&nbsp;Notification</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			<div class='inline-input'>
			    <span><input type='radio' name='realtime_notification' value='1' {!! tag_attr('1', config('setting.realtime_notification'), 'checked') !!}> Yes</span>
			    <span><input type='radio' name='realtime_notification' value='0' {!! tag_attr('0', config('setting.realtime_notification'), 'checked') !!}> No</span>
			</div>
			<span field='realtime_notification' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
		<label for='desktop_notification' class='col-xs-12 col-sm-4 col-md-3 col-lg-2'>Desktop&nbsp;Notification</label>

		<div class='col-xs-12 col-sm-8 col-md-9 col-lg-10'>
			<div class='inline-input'>
			    <span><input type='radio' name='desktop_notification' value='1' {!! tag_attr('1', config('setting.desktop_notification'), 'checked') !!}> Yes</span>
			    <span><input type='radio' name='desktop_notification' value='0' {!! tag_attr('0', config('setting.desktop_notification'), 'checked') !!}> No</span>
			</div>
			<span field='desktop_notification' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group'>
	    <div class='col-xs-12 col-sm-offset-4 col-sm-8 col-md-offset-3 col-md-9 col-lg-offset-2 col-lg-10'>
	        {!! Form::submit('Save', ['name' => 'save', 'class' => 'save btn btn-primary']) !!}
	    </div>
	</div> <!-- end form-group -->
{!! Form::close() !!}