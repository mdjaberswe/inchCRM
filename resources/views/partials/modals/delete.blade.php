<div class='modal fade top' id='confirm-delete'>
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
</div> <!-- end confirm-delete -->

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $(document).on('click', '.delete', function(event)    
            {
                event.preventDefault();

                if($(this).hasClass('disabled'))
                {
                    $.notify({ message: 'This {!! isset($page['item']) ? strtolower($page['item']) : null !!} is used in other modules.' }, globalVar.dangerNotify);
                    return false;
                }

                var formUrl = $(this).parent('form').prop('action');
                var formData = $(this).parent('form').serialize();
                var itemName = typeof $(this).attr('data-item') === 'undefined' ? '{!! $page['item'] or null !!}' : $(this).attr('data-item');
                var itemLowerCase = itemName.toLowerCase();
                var parentItem = typeof $(this).attr('data-parentitem') !== 'undefined' ? $(this).attr('data-parentitem') : null;
                
                var message = '';
                if(typeof $(this).attr('data-associated') === 'undefined' || $(this).attr('data-associated') == 'true')
                {
                    message += 'This ' + itemLowerCase + ' will be removed along with all associated data.<br>';
                }        
                message += 'Are you sure you want to delete this ' + itemLowerCase + '?'; 

                var tableExist = typeof globalVar.jqueryDataTable !== 'undefined' ? true : false;

                if(parentItem != null)
                {
                    message = 'This ' + itemLowerCase + ' will be removed from the ' + parentItem + '.<br>Are you sure you want to remove this ' + itemLowerCase + '?'; 
                }

                var title = typeof $(this).attr('modal-title') === 'undefined' ? 'CONFIRM' : $(this).attr('modal-title');
                title += typeof $(this).attr('modal-sub-title') === 'undefined' ? '' : " <span class='shadow bracket'>" + $(this).attr('modal-sub-title') + "</span>";
                $('#confirm-delete .modal-title').html(title);

                confirmDelete(formUrl, formData, tableExist, itemName, message, parentItem);
            });   
        });      

        function confirmDelete(formUrl, formData, tableExist, itemName, message, parentItem = null)
        {
            $('#confirm-delete').modal({
                show : true,
                backdrop: false,
                keyboard: false
            });

            $('#confirm-delete .message').html(message);

            $('#confirm-delete .yes').click(function()
            {
                $('#confirm-delete .message').html("<img src='{!! asset('plugins/datatable/images/preloader.gif') !!}'>");
                $.ajax(
                {
                    type    : 'DELETE',
                    url     : formUrl,                      
                    data    : formData,
                    dataType: 'JSON',
                    success : function(data)
                              {
                                if(data.status == true)
                                {
                                    var actionTaken = parentItem != null ? 'removed' : 'deleted';
                                    $('#confirm-delete .message').html("<span class='fa fa-times-circle c-danger'></span> <span class='capitalize'>" + itemName + "</span> has been " + actionTaken + ".");
                                    delayModalHide('#confirm-delete', 1); 
                                    if(typeof data.tabTable != 'undefined' && typeof globalVar.dataTable[data.tabTable] != 'undefined')
                                    {
                                        globalVar.dataTable[data.tabTable].ajax.reload(null, false);
                                    }
                                    else if(tableExist)
                                    {
                                        globalVar.jqueryDataTable.ajax.reload(null, false);
                                    }
                                    @if(isset($page['modal_footer_delete']) && $page['modal_footer_delete'] == true)
                                        delayModalHide('#edit-form', 1);
                                    @endif

                                    if($('.calendar').get(0))
                                    {
                                        if(data.eventId != null)
                                        {
                                            $('.calendar').fullCalendar('removeEvents', data.eventId);
                                        }                                        
                                    }

                                    if($('.funnel-container').get(0))
                                    {
                                        $.each(data.kanban, function(index, cardId)
                                        {
                                            $('.funnel-stage #' + cardId).remove();
                                        });

                                        kanbanCountResponse(data);
                                    } 

                                    if(typeof data.defaultViewId != 'undefined' && data.defaultViewId != null)
                                    {
                                        $(".breadcrumb-select[name='view'] option[value='"+data.deletedViewId+"']").remove();
                                        $(".breadcrumb-select[name='view']").val(data.defaultViewId);
                                        $(".breadcrumb-select[name='view']").trigger('change');
                                        $(".breadcrumb-select[name='view']").closest('li').find('.view-btns').html('');
                                        $(".common-filter-btn[data-item='"+data.module+"'] .num-notify").html(data.filterCount);
                                    }

                                    if(typeof data.timelineInfoId != 'undefined' && data.timelineInfoId != null)
                                    {
                                        $(".timeline-info[data-id='"+data.timelineInfoId+"']").fadeOut(750);

                                        setTimeout(function() 
                                        {
                                            if($(".timeline-info[data-id='"+data.timelineInfoId+"']").hasClass('top'))
                                            {
                                                $(".timeline-info[data-id='"+data.timelineInfoId+"']").next('.timeline-info').addClass('top');
                                            }

                                            if(typeof data.timelineInfoCount != 'undefined' && data.timelineInfoCount == 0)
                                            {
                                                $($(".timeline-info[data-id='"+data.timelineInfoId+"']").closest('.timeline')).html('');
                                            }

                                            $(".timeline-info[data-id='"+data.timelineInfoId+"']").remove(); 
                                        }, 700);
                                    }

                                    if(typeof data.realtime != 'undefined')
                                    {
                                        $.each(data.realtime, function(index, value)
                                        {
                                            $("*[data-realtime='"+index+"']").html(value);
                                        });
                                    }

                                    if(typeof data.redirect != 'undefined' && data.redirect != null)
                                    {
                                        window.location = data.redirect;
                                    }
                                }
                                else
                                {
                                    if(typeof data.errorMsg != 'undefined')
                                    {
                                        $('#confirm-delete .message').html("<span class='fa fa-exclamation-triangle c-danger'></span> " + data.errorMsg);
                                        delayModalHide('#confirm-delete', 2);
                                    }
                                    else
                                    {
                                        $('#confirm-delete .message').html("<span class='fa fa-exclamation-circle c-danger'></span> Operation failed. Please try again.");
                                        delayModalHide('#confirm-delete', 1);
                                    }                                    
                                }
                              }
                });

                $(this).parent().find('.btn').off('click');
            });

            $('#confirm-delete .no').click(function()
            {
                $('#confirm-delete').modal('hide');
                $(this).parent().find('.btn').off('click');
            });
        }
    </script>
@endpush    