<h4 class='title-type-a'>Notification Cases</h4>

<table id='datatable' class='table display notifycase' cellspacing='0' width='100%' dataurl='{!! $cases_table['dataurl'] !!}' datacolumn='{!! $cases_table['json_columns'] !!}' databtn='{!! table_showhide_columns($cases_table) !!}' perpage='100'>
	<thead>
		<tr>
			<th data-orderable='false' style='min-width: 200px'>case<br><div class='m-top-5'>&nbsp;</div></th>
			<th data-orderable='false' data-class-name='center' style='min-width: 130px; max-width: 135px'>web&nbsp;notification <br><div class='m-top-5'>{!! $cases_table['all_web'] !!}</div></th>
			<th data-orderable='false' data-class-name='center' style='min-width: 130px; max-width: 135px'>email&nbsp;notification <br><div class='m-top-5'>{!! $cases_table['all_email'] !!}</div></th>	  
			<th data-orderable='false' data-class-name='center' style='min-width: 130px; max-width: 135px'>sms&nbsp;notification <br><div class='m-top-5'>{!! $cases_table['all_sms'] !!}</div></th> 			      			        			
		</tr>
	</thead>
</table>