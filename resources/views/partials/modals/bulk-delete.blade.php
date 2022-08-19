<div class='modal fade top' id='confirm-bulk-delete'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h4 class='modal-title'>CONFIRM</h4>
            </div>
            <div class='modal-body'>                    
                <p class='message para-type-d'>Are you sure? You won't be able to undo this action.</p>                                
            </div> <!-- end modal-body -->
            <div class='modal-footer btn-container'>
                <button type='button' class='no btn btn-primary'>No</button>
                <button type='button' class='yes btn btn-danger'>Yes</button>
            </div>
        </div>
    </div> <!-- end modal-dialog -->
</div> <!-- end confirm-bulk-delete -->

@push('scripts')
    <script>
        function confirmBulkDelete(formUrl, formData, table, itemName, message, checkedCount)
        {
            $('#confirm-bulk-delete').modal({
                show : true,
                backdrop: false,
                keyboard: false
            });

            $('#confirm-bulk-delete .message').html(message);

            var confirmMessage = itemName + " has been deleted.";
            if(checkedCount > 1)
            {
                confirmMessage = itemName + " have been deleted.";
            }

            $('#confirm-bulk-delete .yes').click(function()
            {
                $('#confirm-bulk-delete .message').html("<img src='{!! asset('plugins/datatable/images/preloader.gif') !!}'>");
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
                                    $('#confirm-bulk-delete .message').html("<span class='fa fa-times-circle c-danger'></span> " + confirmMessage);
                                    delayModalHide('#confirm-bulk-delete', 1)
                                    table.ajax.reload(null, false);
                                }
                                else
                                {
                                    $('#confirm-bulk-delete .message').html("<span class='fa fa-exclamation-circle c-danger'></span> Operation failed. Please try again.");
                                    delayModalHide('#confirm-bulk-delete', 1);
                                }
                              }
                });

                $(this).off('click');
            });

            $('#confirm-bulk-delete .no').click(function()
            {
                $('#confirm-bulk-delete').modal('hide');
                $(this).off('click');
            });
        }
    </script>
@endpush    