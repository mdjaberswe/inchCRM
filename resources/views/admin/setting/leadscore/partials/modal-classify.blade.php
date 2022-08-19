<div class='modal fade large' id='classify-lead-score'>
    <div class='modal-dialog'>
        <div class='modal-loader'>
            <div class='spinner'></div>
        </div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
	        	<h4 class='modal-title'>Classify Lead Score</h4>
    	    </div> <!-- end modal-header -->

    		{!! Form::open(['route' => 'admin.post.classify.lead.score', 'method' => 'post', 'class' => 'form-type-a']) !!}
    		    <div class='modal-body perfectscroll'>   
                    <div class='form-group'>
                        <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                            <p class='para-type-j'>
                                There are three default categories for leads: Hot, Warm and Cold. Hot leads are your sales-ready leads, while Warm and Cold leads need nurturing.
                                Use the slider below to edit the lead score range for each category. 
                            </p>
                        </div>
                    </div> <!-- end form-group -->

                    <div class='form-group'>
                        <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                            <div class='full slider-range-container'>
                                <div class='full slider-box'>                                    
                                    <div class='slider-range' data-start='{!! config('setting.warm_lead_low') !!}' data-end='{!! config('setting.warm_lead_up') !!}'>
                                        <div class='hot-area'></div>
                                        {!! Form::hidden('range_start', config('setting.warm_lead_low')) !!}
                                        {!! Form::hidden('range_end', config('setting.warm_lead_up')) !!}
                                    </div>
                                </div>    

                                <ul class='meter piece-21'>
                                    <li>0</li>
                                    <li>5</li>
                                    <li>10</li>
                                    <li>15</li>
                                    <li>20</li>
                                    <li>25</li>
                                    <li>30</li>
                                    <li>35</li>
                                    <li>40</li>
                                    <li>45</li>
                                    <li>50</li>
                                    <li>55</li>
                                    <li>60</li>
                                    <li>65</li>
                                    <li>70</li>
                                    <li>75</li>
                                    <li>80</li>
                                    <li>85</li>
                                    <li>90</li>
                                    <li>95</li>
                                    <li>99</li>
                                </ul>    
                            </div>
                            <span field='range_start' class='validation-error'></span>
                            <span field='range_end' class='validation-error'></span>
                        </div>
                    </div>        

                    <div class='form-group'>
                        <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                            <div class='full'>
                                <div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 div-double-input'>
                                    <div class='full right-icon lg-txt'>
                                        <p>
                                            <span class='color cold'></span>
                                            <span class='cold-range'>{!! config('setting.cold_lead_low') . '-' . config('setting.cold_lead_up') !!}</span>
                                        </p>
                                        {!! Form::text('cold_lead', config('setting.cold_lead_label'), ['class' => 'form-control', 'placeholder' => 'Nurturing Level 1 (Cold)']) !!}
                                        <span field='cold_lead' class='validation-error'></span>
                                    </div>
                                </div>

                                <div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 div-double-input triple'>
                                    <div class='full right-icon lg-txt'>
                                        <p>
                                            <span class='color warm'></span>
                                            <span class='warm-range'>{!! config('setting.warm_lead_low') . '-' . config('setting.warm_lead_up') !!}</span>
                                        </p>
                                        {!! Form::text('warm_lead', config('setting.warm_lead_label'), ['class' => 'form-control', 'placeholder' => 'Nurturing Level 2 (Warm)']) !!}
                                        <span field='warm_lead' class='validation-error'></span>
                                    </div>
                                </div>

                                <div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 div-double-input'>
                                    <div class='full right-icon lg-txt'>
                                        <p>
                                            <span class='color hot'></span>
                                            <span class='hot-range'>{!! config('setting.hot_lead_low') . '-' . config('setting.hot_lead_up') !!}</span>
                                        </p>
                                        {!! Form::text('hot_lead', config('setting.hot_lead_label'), ['class' => 'form-control', 'placeholder' => 'Sales-ready Level (Hot)']) !!}
                                        <span field='hot_lead' class='validation-error'></span>
                                    </div>
                                </div>
                            </div> <!-- end full -->
                        </div>
                    </div> <!-- end form-group -->
    		    </div> <!-- end modal-body -->
    		{!! Form::close() !!}

	 		<div class='modal-footer space btn-container'>
	 		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	 		    <button type='button' class='save btn btn-info'>Save</button>
	 		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end convert-lead-form -->

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $('#classify-lead-score .save').click(function()
            {				
            	var form = $(this).parent().parent().find('form');
            	
            	$('#classify-lead-score .processing').html("<div class='loader-ring-sm'></div>");
            	$('#classify-lead-score .processing').show();			    

            	var formUrl = form.prop('action');
            	var formData = form.serialize();

            	$.ajax(
            	{
            	    type    : 'POST',
            	    url     : formUrl,
            	    data    : formData,
            	    dataType: 'JSON',
            	    success : function(data)
            	              {
            	                if(data.status == true)
            	                {
            	                	$('#classify-lead-score span.validation-error').html('');
            	                    $('#classify-lead-score .processing').html("<span class='fa fa-check-circle success'></span>");				                    
                                	delayModalHide('#classify-lead-score', 1);
            	                }
            	                else
            	                {
            	                	$('#classify-lead-score span.validation-error').html('');
            	                	$.each(data.errors, function(index, value)
            	                	{
            	                		$("#classify-lead-score span[field='"+index+"']").html(value);
            	                	});
            	                	$('#classify-lead-score .processing').html("<span class='fa fa-exclamation-circle error'></span>");
            	                }
            	              }
            	});
            });
        });
    </script>
@endpush