<div class='modal fade large' id='access-form'>
    <div class='modal-dialog'>
        <div class='modal-loader'>
            <div class='spinner'></div>
        </div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
	        	<h4 class='modal-title'>Private <span class='capitalize'></span> <span class='shadow bracket'></span></h4>
    	    </div> <!-- end modal-header -->

            {!! Form::open(['route' => null, 'method' => 'post', 'class' => 'form-type-a']) !!}
    		    <div class='modal-body min-h-150 perfectscroll'>   
                    <div class='form-group always-show'>
                        <div class='col-xs-9'>
                            {!! Form::select('staffs[]', $admin_users_list, null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Allow some users only']) !!}
                            <span field='staffs' class='validation-error'></span>
                        </div>

                        <div class='inline-block btn-container'>
                            <button type='button' class='allow-user btn thin-both btn-warning'>Add</button>
                        </div>
                    </div> <!-- end form-group -->  

                    <div class='form-group'>
                        <div class='col-xs-12 table-responsive min-h-150'>
                            <table class='table middle center'>
                                <thead>
                                    <tr>
                                        <th style='width: 30px;'>#</th>
                                        <th style='min-width: 270px;'>ALLOWED USER</th>
                                        <th style='width: 70px;'>READ</th>
                                        <th style='width: 70px;'>WRITE</th>
                                        <th style='width: 70px;'>DELETE</th>
                                        <th style='width: 30px;'></th>
                                    </tr>
                                </thead>

                                <tbody data-serial='true'>

                                </tbody>
                            </table>
                            <span field='serial' class='validation-error'></span>
                            <span field='id' class='validation-error'></span>
                            <span field='type' class='validation-error'></span>
                            <span field='allowed_staffs' class='validation-error'></span>
                        </div>
                    </div>              
    		    </div> <!-- end modal-body -->

                {!! Form::hidden('id', null) !!}
                {!! Form::hidden('type', null) !!}
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
            $('.allow-user').click(function()
            {
                var formGroup = $(this).closest('.form-group');
                var modalBody = formGroup.parent('.modal-body');
                var tbody = modalBody.find('table').find('tbody');
                var trCount = tbody.children('tr').length;
                var staffs = formGroup.find("*[name='staffs[]']").val();
                var addStaffs = [];

                $.each(staffs, function(index, val)
                {
                    var staffExist = tbody.find("tr[data-staff='"+val+"']");
                    
                    if(staffExist.length == 0)
                    {
                        addStaffs.push(val);
                    }
                });

                if(addStaffs.length > 0)
                {
                    $.ajax({
                        type    : 'POST',
                        url     : globalVar.baseAdminUrl + '/allowed-user-data',
                        data    : { staffs : addStaffs, serial : trCount },
                        dataType: 'JSON',
                        success : function(data)
                                  {
                                    if(data.status == true)
                                    {
                                        if(data.html != '')
                                        {
                                            tbody.append(data.html);                                        
                                            $('[data-toggle="tooltip"]').tooltip();
                                            formGroup.find("*[name='staffs[]']").val('');
                                            formGroup.find('.select2-hidden-accessible').trigger('change');
                                        }
                                    }
                                    else
                                    {   
                                        alert('Something went wrong! Please try again.');
                                    }
                                  }
                    });
                }
                else
                {
                    formGroup.find("*[name='staffs[]']").val('');
                    formGroup.find('.select2-hidden-accessible').trigger('change');
                }
            });

            $('#access-form .save').click(function()
            {                
                $('#access-form .processing').html("<div class='loader-ring-sm'></div>");
                $('#access-form .processing').show();

                var form = $(this).parent().parent().find('form');
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
                                    $('#access-form span.validation-error').html('');
                                    $('#access-form .processing').html("<span class='fa fa-check-circle success'></span>");                                   
                                    
                                    if(data.html)
                                    {
                                        $('#access').html(data.html);
                                        $('[data-toggle="tooltip"]').tooltip();
                                    }      

                                    if(data.updatedBy != null)
                                    {
                                        $("*[data-realtime='updated_by']").html(data.updatedBy);
                                    }   
                                                                  
                                    delayModalHide('#access-form', 1);
                                }
                                else
                                {
                                    $('#access-form span.validation-error').html('');
                                    $.each(data.errors, function(index, value)
                                    {
                                        $("#access-form span[field='"+index+"']").html(value);
                                    });
                                    $('#access-form .processing').html("<span class='fa fa-exclamation-circle error'></span>");
                                }
                              }
                });                
            });
        });
    </script>
@endpush