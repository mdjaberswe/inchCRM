<div class='modal-body perfectscroll'>
	<div class='form-group show-if' data-show-only='between'>
		<label for='timeperiod' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Reporting Period</label>

		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			{!! Form::select('timeperiod', $timeperiod_list, null, ['class' => 'form-control white-select-type-single']) !!}
			<span field='timeperiod' class='validation-error'></span>
		</div>
	</div> <!-- end form-group -->

	<div class='form-group start_date-input none'>
		<label for='between' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Between Dates</label>
		
		<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
			<div class='full'>
				<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
					<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='Start Date'>
						<i class='fa fa-calendar-check-o'></i>
						{!! Form::text('start_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Start Date']) !!}
						<span field='start_date' class='validation-error'></span>
					</div> <!-- end form-group -->
				</div>

				<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 div-double-input'>
					<div class='full left-icon' data-toggle='tooltip' data-placement='top' title='End Date'>
						<i class='fa fa-calendar-times-o'></i>
						{!! Form::text('end_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'End Date']) !!}
						<span field='end_date' class='validation-error'></span>
					</div> <!-- end form-group -->
				</div>
			</div>
		</div>
	</div> <!-- end form-group -->
</div> <!-- end modal-body -->

{!! Form::hidden('type', $type) !!}