<div class='modal-body vertical perfectscroll'>
	<div class='full form-group-container near-10'>
		<div class='col-xs-12'>
		    <div class='form-group'>
		        <label for='name'>Pipeline Name <span class='c-danger'>*</span></label>
		        {!! Form::text('name', null, ['class' => 'form-control']) !!}
		        <span field='name' class='validation-error'></span>
		    </div> <!-- end form-group -->

		    <div class='form-group'>
		        <label for='deal_stage'>Deal Stages <span class='c-danger'>*</span></label>
	            <div class='full right-icon h-100'>
	                <i data-toggle='tooltip' data-placement='top' title='Add' class='fa fa-plus clickable add-pipeline-stage'></i>              
					<select name='stages[]' class='form-control white-select-type-multiple' multiple='multiple' data-placeholder='Please select deal stages'>
	                	@foreach($deal_stages as $deal_stage)
	                		<option value='{!! $deal_stage->id !!}' probability='{!! $deal_stage->probability !!}' category='{!! $deal_stage->category !!}' position='{!! $deal_stage->position !!}'>{!! $deal_stage->name !!}</option>
	                	@endforeach
	                </select>
	            </div>   
	            <span field='deal_stage' class='validation-error block'></span>
	            <span field='forecast' class='validation-error block'></span>
		    </div> <!-- end form-group -->

		    <div class='form-group table-m-0'>
    		    <table id='{!! 'posionable-datatable-' . $form !!}' class='table middle clean-border posionable-datatable' cellspacing='0' width='100%' data-item='' data-url='{!! route('admin.dealpipeline.stage.data') !!}' data-column='{!! $json_columns !!}'>
    				<thead>
    					<tr>
    	        			<th data-orderable='false' data-class-name='center' style='min-width: 45px'>
    	        				<span class='full center' data-toggle='tooltip' data-placement='bottom' title='Drag&nbsp;&amp;&nbsp;Drop'>
    	        					<i class='fa fa-arrows'></i>
    	        				</span>
    	        			</th>	
    						<th data-orderable='false' style='min-width: 180px;'>DEAL&nbsp;STAGE&nbsp;NAME</th>				
    						<th data-orderable='false' style='min-width: 90px;'>CATEGORY</th>
    						<th data-orderable='false' data-class-name='center'>PROBABILITY</th>	
    						<th data-orderable='false' data-class-name='center'>FORECAST</th>
    						<th data-orderable='false' data-class-name='center' class='action-1'></th>	      			        			
    					</tr>
    				</thead>
    			</table>
		    </div> <!-- end form-group -->

	        <div class='form-group'>
	            <label for='period'>Pipeline Rotting Period <span class='modal-hint'>(Days)</span> <span class='c-danger'>*</span></label>
	            <div class='full right-icon'>
	            	<i data-toggle='tooltip' data-placement='top' title='Days' class='mdi mdi-calendar-range lg'></i>
	    	        {!! Form::text('period', 30, ['class' => 'form-control numeric']) !!}
	    	        <span field='period' class='validation-error'></span>
	    	    </div>  
	    	    <div class='full'>
	    	    	<p class='pretty top-space info smooth'>
	    	    	    <input type='checkbox' name='default' value='1'>
	    	    	    <label><i class='mdi mdi-check'></i></label> Mark as default pipeline
	    	    	</p> 
	    	    </div>  
	        </div> <!-- end form-group -->	
		</div>    
	</div> <!-- end form-group-container -->
</div> <!-- end modal-body -->

@if(isset($form) && $form == 'edit')
    {!! Form::hidden('id', null) !!}
@endif