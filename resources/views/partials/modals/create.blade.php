<div class='modal fade {!! $page['modal_size'] or 'large' !!}' id='add-new-form'>
    <div class='modal-dialog'>
    	<div class='modal-content'>
    		<div class='processing'></div>
    	    <div class='modal-header'>
    	        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span></button>
    	        <h4 class='modal-title'>@if(isset($page['modal_icon'])) <i class='{!! $page['modal_icon'] !!}'></i> @endif Add New {!! $page['item'] !!}</h4>
    	    </div> <!-- end modal-header -->

    	    @if(!isset($yield) || (isset($yield) && $yield == true))
    	    	@yield('modalcreate')
    	    @else
    	    	{!! Form::open(['route' => $page['route'] . '.store', 'method' => 'post', 'class' => 'form-type-a']) !!}
    	    	    @include($page['view'] . '.partials.form', ['form' => 'create'])
    	    	{!! Form::close() !!}
    	    @endif

    		<div class='modal-footer space btn-container'>
    		    <button type='button' class='cancel btn btn-default' data-dismiss='modal'>Cancel</button>
   		        @if(!isset($page['save_and_new']) || (isset($page['save_and_new']) && $page['save_and_new'] == true))
   		        	<button type='button' class='save-new btn btn-default'>Save and New</button>
   		        @endif
    		    <button type='button' class='save btn btn-info'>Save</button>
    		</div> <!-- end modal-footer -->
    	</div>	
    </div> <!-- end modal-dialog -->
</div> <!-- end add-new-form -->

@push('scripts')
	<script>
		$(document).ready(function()
		{
			$('#add-new-btn').click(function()
			{
				// reset to default values
				$('#add-new-form form').trigger('reset');				
				$('#add-new-form form').find('.select2-hidden-accessible').trigger('change');
				
				$('#add-new-form form').find('option').prop('disabled', false);
				$('#add-new-form form').find('.white-select-type-single').select2('destroy').select2({containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});
				$('#add-new-form form').find('.white-select-type-single-b').select2('destroy').select2({minimumResultsForSearch : -1, containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});

				$('#add-new-form .processing').html('');
				$('#add-new-form .processing').hide();
				$('#add-new-form .none').slideUp();
				$('#add-new-form span.validation-error').html('');
				$('#add-new-form .modal-body').animate({ scrollTop: 1 });

				var div = $('#add-new-form').find('div.amount');
				var currencyList = div.find('.currency-list');
				var tagIcon = div.find('i');

				if(typeof currencyList !== 'undefined' || typeof tagIcon !== 'undefined')
				{	
					var icon = div.attr('icon');
					var alterIcon = div.attr('alter-icon');
					var baseId = div.attr('base-id');		
					var currencyId = div.find("input[name='currency_id']");		
					resetCurrency(currencyList, currencyId, baseId, tagIcon, icon, alterIcon);
				}

				$('#add-new-form .datepicker').each(function(index, value)
				{
					$(this).datepicker('update', $(this).val());
				});

				if($('#add-new-form .toggle-permission').get(0))
				{
					$('#add-new-form .child-permission').css('opacity', 1);
					$('#add-new-form .child-permission').find('input').attr('disabled', false);
					$('#add-new-form .child-permission').find("input[data-default='true']").prop('checked', true);
				}	

				if($('#add-new-form .modal-image').get(0))
				{
					var defaultImage = $('#add-new-form .modal-image').data('image');
					$('#add-new-form .modal-image img').attr('src', defaultImage);
				}

				if($('#add-new-form .posionable-datatable').get(0))
				{
					var tableId = '#' + $('#add-new-form .posionable-datatable').attr('id');
					var dataUrl = $('#add-new-form .posionable-datatable').attr('data-url');
					var tableColumns = $('#add-new-form .posionable-datatable').attr('data-column');
					posionableDatatableInit(tableId, dataUrl, tableColumns);
				}

				if(typeof $(this).attr('data-default') != 'undefined')
				{
					var fieldSet = $(this).attr('data-default').split('|');

					$(fieldSet).each(function(index, singleField)
					{
						var fieldData = singleField.split(':');
						$("#add-new-form *[name='"+fieldData[0]+"']").val(fieldData[1]).trigger('change');
					});
				}

				$('#add-new-form').modal({
	                show : true,
	                backdrop: false,
	                keyboard: false
            	});

            	var imageLeft = $('#add-new-form .modal-title').width() + 40;
            	$('#add-new-form .modal-image').css('left', imageLeft + 'px');

				$('#add-new-form .modal-body').animate({ scrollTop: 0 });			
			});

			$('#add-new-form .save').click(function()
			{				
				var form = $(this).parent().parent().find('form');		
				var listOrder = true;
				@if(isset($table['list_order']))
					listOrder = false;
				@endif

				modalDataStore('#add-new-form', form, listOrder);
			});

			$('#add-new-form .save-new').click(function()
			{
				var form = $(this).parent().parent().find('form');
				var listOrder = true;
				@if(isset($table['list_order']))
					listOrder = false;
				@endif

				modalDataStore('#add-new-form', form, listOrder, true);
			});

			$(document).on('click', '.add-new-common', function()    
			{
				openCommonCreateModal('#add-new-form', '#add-new-content', $(this));
			});
		});

		function addNewEvent()
		{
			$('#add-new-form form').trigger('reset');				
			$('#add-new-form form').find('.select2-hidden-accessible').trigger('change');
			
			$('#add-new-form form').find('option').prop('disabled', false);
			$('#add-new-form form').find('.white-select-type-single').select2('destroy').select2({containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});
			$('#add-new-form form').find('.white-select-type-single-b').select2('destroy').select2({minimumResultsForSearch : -1, containerCssClass: 'white-container', dropdownCssClass: 'white-dropdown'});

			$('#add-new-form .processing').html('');
			$('#add-new-form .processing').hide();
			$('#add-new-form .none').slideUp();
			$('#add-new-form span.validation-error').html('');
			$('#add-new-form').modal();
		}
	</script>
@endpush