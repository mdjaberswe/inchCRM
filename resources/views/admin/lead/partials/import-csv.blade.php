{!! Form::open(['route' => 'admin.import.map', 'method' => 'post', 'files' => true, 'id' => 'import-file-form', 'class' => 'form-type-a']) !!}
	<div class='modal-body perfectscroll'>
		<div class='form-group'>
			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
				<p class='para-type-j'>
					Upload your Leads CSV file with a header row. Make sure the CSV file has columns for all the required fields on Leads. Download <a>Sample CSV</a>.
				</p>

				<div class='alert-note'>
					<h3>Notes</h3>
					<ol>
						<li>Only <strong>.csv</strong> or <strong>.xls</strong> or <strong>.xlsx</strong> formats are supported.</li>
						<li>Date field format is <strong>'MM/DD/YYYY'</strong></li>
						<li>Date Time field format is <strong>'MM/dd/yyyy hh:mm:ss'</strong></li>
						<li>Checkbox values are either <strong>1/0</strong> or <strong>True/False</strong></li>
						<li><strong>Email</strong> field is <strong>unique</strong> and <strong>identifies matches</strong> in leads.</li>
					</ol>
				</div>	
			</div>
		</div> <!-- end form-group -->

		{!! Form::hidden('module', 'lead') !!}

		<div class='form-group'>
			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
				<label for='import_file'>Choose a file to import</label>		
				{!! Form::file('import_file', ['accept' => '.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/plain, text/csv, text/tsv']) !!}
				<span field='import_file' class='validation-error'></span>
			</div>	
		</div> <!-- end form-group -->

		<div class='form-group'>
			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
				<div class='full'>
					<label for='import_type'>Choose what you want to do</label>		
					<div class='inline-input'>
					    <div class='full'><span><input type='radio' name='import_type' value='new' checked> Add new records <i class='c-danger'>(Skip duplicate or existing <strong>email</strong> rows)</i></span></div>
					    <div class='full'><span><input type='radio' name='import_type' value='update'> Add new and update existing records without overwriting values <i class='fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='Match&nbsp;By:&nbsp;Email'></i></span></div>
					    <div class='full'><span><input type='radio' name='import_type' value='update_overwrite'> Add new and update existing records overwriting values <i class='fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='Match&nbsp;By:&nbsp;Email'></i></span></div>      
					</div>  
				</div>	

				<span field='import_type' class='validation-error'></span>
				<span field='module' class='validation-error'></span>
			</div>	
		</div> <!-- end form-group -->
	</div> <!-- end modal-body -->
{!! Form::close() !!}	