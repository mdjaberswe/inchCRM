<div class='modal-body perfectscroll'>
	<div class='form-group'>
		<label for='member_status' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Member Status</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('member_status', $member_status_list, null, ['class' => 'form-control white-select-type-single-b']) !!}
			<span field='member_status' class='validation-error'></span>
			<span field='campaign_id' class='validation-error'></span>
			<span field='member_id' class='validation-error'></span>
			<span field='member_type' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->
</div> <!-- end modal-body -->	

{!! Form::hidden('campaign_id', null) !!}
{!! Form::hidden('member_id', null) !!}
{!! Form::hidden('member_type', null) !!}