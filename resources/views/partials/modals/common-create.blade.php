<div class='modal fade large' id='common-add'>
    <div class='modal-dialog'>
        <div class='modal-loader'>
            <div class='spinner'></div>
        </div>

    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
    	        <h4 class='modal-title capitalize'>Add New</h4>
    	    </div> <!-- end modal-header -->

	    	{!! Form::open(['route' => null, 'method' => 'post', 'class' => 'form-type-a']) !!}
	    	    <div id='common-add-content'></div>
	    	{!! Form::close() !!}

    		<div class='modal-footer space btn-container'>
    		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
	        	<button type='button' class='save-new btn btn-default'>Save and New</button>
    		    <button type='button' class='save btn btn-info'>Save</button>
    		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end add-multiple-form -->

@push('scripts')
	<script>
		$(document).ready(function()
		{
            $(document).on('click', '.add-multiple', function()    
            {
                openCommonCreateModal('#common-add', '#common-add-content', $(this));
            });

            $('#common-add .save').click(function()
            {               
                var form = $(this).parent().parent().find('form');      
                var listOrder = true;
                var tableDraw = true;
                var thisListOrder = $('#common-add .modal-body').attr('data-listorder');
                var thisTableDraw = $('#common-add .modal-body').attr('data-tabledraw');

                if(typeof thisListOrder != 'undefined' && thisListOrder == 'false')
                {
                    listOrder = false;
                }

                if(typeof thisTableDraw != 'undefined' && thisTableDraw == 'false')
                {
                    tableDraw = false;
                }

                modalDataStore('#common-add', form, listOrder, false, tableDraw);
            });

            $('#common-add .save-new').click(function()
            {
                var form = $(this).parent().parent().find('form');
                var listOrder = true;
                var thisListOrder = $('#common-add .modal-body').attr('data-listorder');

                if(typeof thisListOrder != 'undefined' && thisListOrder == 'false')
                {
                    listOrder = false;
                }

                modalDataStore('#common-add', form, listOrder, true);
            });
		});
	</script>
@endpush