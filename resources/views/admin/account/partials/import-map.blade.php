{!! Form::open(['route' => 'admin.import.post', 'method' => 'post', 'class' => 'form-type-a']) !!}
	<div class='modal-body perfectscroll'>
		{!! Form::hidden('import', null) !!}

		<div class='form-group'>
			<div class='col-xs-12'>
				<p class='para-type-j'>Map the source file's column with the appropriate account fields.</p>

				<div class='alert-note warning'>
					<p>You've to map the <strong>Account Name</strong> mandatory field to start importing data.</p>
				</div>	
			</div>
		</div> <!-- end form-group -->

		<div class='full'>
			<div class='col-xs-12 error-content'></div>
		</div>

		<div class='form-group m-Top-15'>
		    <div class='col-xs-12 table-responsive'>
		        <table class='table table-hover middle less-border space'>
		            <thead>
		                <tr>
		                    <th style='min-width: 130px'>FILE&nbsp;HEADERS</th>
		                    <th style='width: 220px'>ACCOUNT&nbsp;FIELDS</th>
		                </tr>
		            </thead>

		            <tbody class='unique-field-val'>
		            	{!! $tr or null !!}
		            </tbody>
		        </table>
		    </div>
		</div>    
	</div> <!-- end modal-body -->
{!! Form::close() !!}	