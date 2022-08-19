<div class='modal fade top' id='confirm-remove-node'>
    <div class='modal-dialog'>
        <div class='modal-loader'>
            <div class='spinner'></div>
        </div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
	        	<h4 class='modal-title'>Remove Node Confirmation <span class='shadow bracket'></span></h4>
    	    </div> <!-- end modal-header -->

    		{!! Form::open(['route' => 'admin.hierarchy.remove.child', 'method' => 'post', 'class' => 'form-type-a']) !!}
    		    <div class='modal-body vertical perfectscroll'>        
                    <div class='form-group'>
                        <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                            <label for='confirmation' class='padding-0'>Choose what you want to do</label>

                            <div class='inline-input'>
                                <p class='pretty top-space info smooth'>
                                    <input type='radio' name='confirmation' value='one'>
                                    <label><i class='mdi mdi-check'></i></label>  Remove the node only 
                                </p> 
                                <br>
                                <p class='pretty top-space info smooth'>
                                    <input type='radio' name='confirmation' value='all' checked>
                                    <label><i class='mdi mdi-check'></i></label> Remove the node along with all its childs node
                                </p>
                            </div>  

                            <div class='full'>
                                <span field='confirmation' class='validation-error block'></span>
                                <span field='module' class='validation-error block'></span>
                                <span field='orgchart' class='validation-error block'></span>
                                <span field='id' class='validation-error block'></span>  
                            </div>    
                        </div>
                    </div> <!-- end form-group -->
    		    </div> <!-- end modal-body -->
    		    
    		    {!! Form::hidden('id', null) !!}
                {!! Form::hidden('module', null) !!}
                {!! Form::hidden('orgchart', null) !!}
    		{!! Form::close() !!}

	 		<div class='modal-footer space btn-container'>
	 		    <button type='button' class='cancel btn btn-primary' data-dismiss='modal'>Cancel</button>
	 		    <button type='button' class='save btn btn-danger'>Remove</button>
	 		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end convert-lead-form -->

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $(document).on('click', '.node .remove', function(e)    
            {
                var $node = $($(this).closest('.node'));
                var $viewHierarchy = $($node.closest('.view-hierarchy'));
                var nodeChildrens = globalVar.orgChart[$viewHierarchy.attr('id')].getRelatedNodes($node, 'children');

                if(nodeChildrens.length == 0 || $(this).attr('data-nextchild') != '')
                {
                    var confirmation = nodeChildrens.length == 0 ? 'all' : 'one';
                    var formData = { 'id' : parseInt($node.attr('id')), 'module' : $viewHierarchy.data('module'), 'orgchart' : $viewHierarchy.data('id'), 'confirmation' : confirmation};
                    nodeRemoveRequest(formData);
                    return false;
                }

                // reset to default values
                $('#confirm-remove-node form').trigger('reset');
                $('#confirm-remove-node .processing').html('');
                $('#confirm-remove-node .processing').hide();
                $('#confirm-remove-node span.validation-error').html('');
                $('#confirm-remove-node .modal-body').animate({ scrollTop: 1 });
                $('#confirm-remove-node .modal-title .shadow').html($node.find('.node-info h3').data('name'));
                $("#confirm-remove-node input[name='id']").val($node.attr('id'));
                $("#confirm-remove-node input[name='module']").val($viewHierarchy.data('module'));
                $("#confirm-remove-node input[name='orgchart']").val($viewHierarchy.data('id'));

                $('#confirm-remove-node').modal({
                    show : true,
                    backdrop: false,
                    keyboard: false
                });
            });

            $('#confirm-remove-node .save').click(function()
            {                  
                $('#confirm-remove-node .processing').html("<div class='loader-ring-sm'></div>");
                $('#confirm-remove-node .processing').show();             

                var form = $($(this).closest('.modal')).find('form');
                var formData = form.serialize();
                nodeRemoveRequest(formData);
            });
        });

        function nodeRemoveRequest(formData)
        {
            $.ajax(
            {
                type    : 'POST',
                url     : globalVar.baseAdminUrl + '/hierarchy-remove-child',
                data    : formData,
                dataType: 'JSON',
                success : function(data)
                          {
                            if(data.status == true)
                            {
                                if(data.removeAll == true)
                                {
                                    var $node = $($('#' + data.orgchartId).find(".node[id='"+ data.id +"']"));
                                    globalVar.orgChart[data.orgchartId].removeNodes($node);
                                }
                                else
                                {
                                    orgChartRefresh(data.orgchartId, data.totalNode);
                                }

                                $('#confirm-remove-node span.validation-error').html('');
                                $('#confirm-remove-node .processing').html("<span class='fa fa-check-circle success'></span>");                                   
                                delayModalHide('#confirm-remove-node', 1);
                            }
                            else
                            {
                                $('#confirm-remove-node span.validation-error').html('');
                                $.each(data.errors, function(index, value)
                                {
                                    $("#confirm-remove-node span[field='"+index+"']").html(value);
                                });
                                $('#confirm-remove-node .processing').html("<span class='fa fa-exclamation-circle error'></span>");
                            }
                          }
            });
        }
    </script>
@endpush