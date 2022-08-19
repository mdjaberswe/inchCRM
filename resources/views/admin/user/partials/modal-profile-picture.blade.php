<div class='modal fade' id='profile-picture-modal'>
    <div class='modal-dialog'>
    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
    	        <h4 class='modal-title'>Create Profile Picture <span class='shadow bracket'>{!! $staff->name or 'User Name' !!}</span></h4>
    	    </div> <!-- end modal-header -->

	    	{!! Form::open(['route' => ['admin.user.image', $staff->id ? $staff->id : null], 'method' => 'post', 'files' => true, 'id' => 'profile-picture-form', 'class' => 'form-type-a']) !!}
	    	    <div class='modal-body perfectscroll'>   
                    <div class='form-group'>
                        <div id='show-current-avatar' class='col-xs-12 col-sm-3 col-md-3 col-lg-3 center min-h-125'>
                            <img src='{!! $staff->avatar or global_placeholder() !!}' alt='{!! $staff->last_name or 'User Name' !!}' class='img-type-a border'>
                        </div>

                        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                            <p class='para-type-j'>Upload your photo here. The photo can be GIF, PNG or JPG type file. Photo will be resized a width and height of 200 pixels.</p>
                            {!! Form::file('image', ['accept' => 'image/x-png,image/gif,image/jpeg,image/webp']) !!}
                            <span field='image' class='validation-error'></span>
                        </div>
                    </div> <!-- end form-group -->

                    {!! Form::hidden('id', $staff->id ? $staff->id : null) !!}
                </div> <!-- end modal-body -->    
	    	{!! Form::close() !!}

    		<div class='modal-footer space btn-container'>
    		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
    		    <button type='button' class='save btn btn-info'>Save</button>
    		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end add-new-form -->

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $('.profile-picture-btn').click(function()
            {
                $('#profile-picture-modal form').trigger('reset');
                $('#profile-picture-modal .processing').html('');
                $('#profile-picture-modal .processing').hide();
                $('#profile-picture-modal span.validation-error').html('');
                $('#profile-picture-modal .modal-body').animate({ scrollTop: 0 });

                var id = $(this).attr('editid');
                var updateUrl = globalVar.baseAdminUrl + '/user-image/' + id;
                $('#profile-picture-modal form').prop('action', updateUrl);
                $("#profile-picture-modal input[name='id']").val(id);

                var userName = $(this).closest('.user-info').find('.user-name').html();
                $('#profile-picture-modal .modal-title .shadow').html(userName);

                $('#show-current-avatar').children('img').remove();
                var imgSrc = $(this).parent().find('img').prop('src');
                var avatarHtml = "<img src='"+imgSrc+"' alt='"+userName+"' class='img-type-a border'>";
                $('#show-current-avatar').prepend(avatarHtml);

                $('#profile-picture-modal').modal();
            });

            $('#profile-picture-modal .save').click(function()
            {               
                var form = $(this).parent().parent().find('form');
                
                $('#profile-picture-modal .processing').html("<div class='loader-ring-sm'></div>");
                $('#profile-picture-modal .processing').show();              

                var formUrl = form.prop('action');
                var formData = new FormData($('#profile-picture-form').get(0));

                $.ajax(
                {
                    type    : 'POST',
                    url     : formUrl,
                    data    : formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success : function(data)
                              {
                                if(data.status == true)
                                {
                                    $('#profile-picture-modal span.validation-error').html('');
                                    $('#profile-picture-modal .processing').html("<span class='fa fa-check-circle success'></span>");                                    
                                    $(".profile-picture-btn[editid='"+data.id+"']").closest('.avatar').children('img').remove();
                                    $(".profile-picture-btn[editid='"+data.id+"']").closest('.avatar').prepend(data.avatar);
                                    delayModalHide('#profile-picture-modal', 0);
                                    $('#show-current-avatar').children('img').remove();
                                    $('#show-current-avatar').prepend(data.avatarborder);
                                }
                                else
                                {
                                    $('#profile-picture-modal span.validation-error').html('');
                                    $.each(data.errors, function(index, value)
                                    {
                                        $("#profile-picture-modal span[field='"+index+"']").html(value);
                                    });
                                    $('#profile-picture-modal .processing').html("<span class='fa fa-exclamation-circle error'></span>");
                                }
                              }
                });
            });
        });
    </script>
@endpush    