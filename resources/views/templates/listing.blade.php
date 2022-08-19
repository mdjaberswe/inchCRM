@extends('layouts.master')

@section('content')

	<div class='row'>
	    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
	    	@if(isset($page['breadcrumb']))
	    		<h4 class='title-type-a bold'>{!! $page['breadcrumb'] !!}</h4>
	    	@else
	        	<h4 class='title-type-a bold'>{!! $page['item_plural'] or $page['item'] . 's' !!}</h4>
	        @endif	

	        <div class='right-top'>
	        	@yield('panelbtn')

	        	@if(!isset($page['modal_create']) || isset($page['modal_create']) && $page['modal_create'] == true)
	        		@if(isset($page['permission']) && permit($page['permission'] . '.create'))
		        		<button type='button' id='add-new-btn' class='btn btn-type-a'>
	        				<i class='{!! $page['add_icon'] or 'fa fa-plus-circle' !!}'></i>
		        			Add {!! $page['item'] !!}
		        		</button>
	        		@endif
	        	@endif
	        </div>

	        <table id='datatable' class='table display responsive bulk-checkbox' cellspacing='0' width='100%'>
	        	<thead>
	        		<tr>
        				@if(!isset($table['checkbox']) || (isset($table['checkbox']) && $table['checkbox'] == true))
		        			<th data-priority='1' data-orderable='false' data-class-name='center all' class='{!! $table['checkbox_class'] or null !!}' style='{!! $table['checkbox_style'] or 'min-width: 40px' !!}'>
								<div id='select-all' class='pretty danger smooth' data-toggle='tooltip' data-placement='top' title='Select All'>
									<input type='checkbox'> 
									<label><i class='mdi mdi-check'></i></label>
								</div>
		        			</th>
		        		@endif	

		        		@foreach($table['thead'] as $key => $thead)
		        			@if(is_array($thead))
		        				<th data-priority='{!! ($key+3) !!}' data-orderable='{!! $thead['orderable'] or 'true' !!}' data-class-name='{!! $thead['data_class'] or null !!}' class='{!! $thead['class'] or null !!}' style='{!! $thead['style'] or null !!}'>
		        					@if(array_key_exists('tooltip', $thead)) 
		        						<span data-toggle='tooltip' data-placement='top' title='{!! $thead['tooltip'] !!}'>{!! $thead[0] !!}</span> 
		        					@else
		        						{!! $thead[0] !!}	
		        					@endif		        					
		        				</th>
		        			@else
		        				<th data-priority='{!! ($key+3) !!}'>{!! $thead !!}</th>
		        			@endif
		        		@endforeach

		        		@if(!isset($table['action']) || (isset($table['action']) && $table['action'] == true))     			
	        				<th data-priority='2' data-orderable='false' data-class-name='align-r all' class='{!! $table['action_class'] or 'action-2' !!}' style='{!! $table['action_style'] or null !!}'></th>
	        			@endif
	        		</tr>
	        	</thead>
	        </table>
	    </div> <!-- end div-type-a -->
	</div> <!-- end row -->

	@yield('listingextend')

@endsection

@section('modals')
	@include('partials.modals.initialize', ['yield' => false])
@endsection

@push('scripts')
	<script>
		$(document).ready(function()
		{
			$('.pretty').find('input').prop('checked', false);

			var datatableDom = "<<'bulk'>lBfr<'table-responsive scroll-box-x only-thumb't>ip>";
			var tableColumns = [ {!! $table['json_columns'] !!} ];

			@if(isset($table['custom_filter']) && $table['custom_filter'] !== false)
				datatableDom = "<<'bulk'>lBf<'custom-filter'>r<'table-responsive scroll-box-x only-thumb't>ip>";
			@endif

			var table =
			$('#datatable').on('init.dt', function()
			{

				select2PluginInit();
				$('[data-toggle="tooltip"]').tooltip();
				$('html').getNiceScroll().resize();
				$('.pretty').find('input').prop('checked', false);
				$('.select-type-single').select2();
				$('.select-type-single-b').select2({ 'minimumResultsForSearch' : -1 });

			}).DataTable({
					'dom'           : datatableDom,
					'buttons'		: [
										{
						            		'extend'	: 'collection',
						            		'text'		: 'EXPORT',
						            		'buttons'	: ['excel', 'csv', 'pdf', 'print']
					            	 	},
			            	 	        {
			            	 	            'extend'	: 'collection',
			            	 	            'text' 		: "<i class='fa fa-eye-slash'></i>",
			            	 	            'buttons'	: [ {!! table_showhide_columns($table) !!} ],
			            	 	            'fade' 		: true
			            	 	        },
					            	 	{
			            	 	            'text'		: "<i class='fa fa-refresh'></i>",
			            	 	            'action'	: function(e, dt, node, config)
							            	 	          {
							            	 	          	dt.ajax.reload();
							            	 	          }
			            	 	        }
					            	  ],
					'pageLength'    : '{!! isset($page['page_length']) ? $page['page_length'] : 10 !!}',
					'lengthMenu'    : [10, 25, 50, 75, 100],
					'language'      : {
					                    'paginate'  : {
					                                    'previous'  : "<i class='fa fa-angle-double-left'></i>",
					                                    'next'      : "<i class='fa fa-angle-double-right'></i>"
					                                  },
					                    'info'      : '_START_ - _END_ / _TOTAL_',
					                    'lengthMenu': '_MENU_',
					                    'search'    : '_INPUT_',
					                    'searchPlaceholder' : 'Search',
					                    'infoFiltered': '',
					                    'sProcessing' : ''    
					                  },
					'order'			: [],
					'processing'	: true,
					'serverSide'	: true,
					'ajax'			: { 
										'url'	: '{!! route($page['route'] . '.data') !!}', 
										'type'	: 'POST',
										'data'	: function(d)
												  {
												  	$(tableColumns).each(function(index, value)
												  	{
												  		d.globalSearch = $('#datatable_filter').get(0) ? $("#datatable_filter label input[type='search']").val() : '';											  		
												  		var filterInput = value.data;
												  		var filterInputId = '#' + '{!! strtolower($page['item']) !!}' + '-' + filterInput;
											  			if(filterInput != 'checkbox' && filterInput != 'action')
											  			{
											  				d[filterInput] = $(filterInputId).get(0) ? $(filterInputId).val() : '';
											  			}											  			
												  	});												  	
												  }
									  },
					'columns'		: tableColumns,
					'fnDrawCallback': function(oSettings)
									  {
									  	select2PluginInit();
									  	$('[data-toggle="tooltip"]').tooltip();
									  	$('html').getNiceScroll().resize();
									  	$('.pretty').find('input').prop('checked', false);
									  	$('div.bulk').hide();
									  }			  
			});

			globalVar.jqueryDataTable = table;

			@if(isset($table['custom_filter']) && $table['custom_filter'] !== false)
				var filterInputHtml = "{!! table_filter_html($table['filter_input'], $page['item']) !!}";
				$('.custom-filter').html(filterInputHtml);

				$('.custom-filter select').change(function()
				{
					table.draw();
				});
			@endif

			$('main .dt-buttons').on('click.dt', '.show-hide', function(event)
			{
				var column = table.column($(this).index());				
		        column.visible(!column.visible());
		        if(column.visible())
		        {
		        	$(this).removeClass('unseen');
		        }
		        else
		        {
		        	$(this).addClass('unseen');
		        }
			});

			$('#datatable tbody').on('click.dt', '.edit', function(event)
			{
				var id = $(this).attr('editid');
				var data = {'id' : id};
				var tr = $($(this).closest('tr'));
				var url = '{!! route($page['route'] . '.index') !!}' + '/' + id + '/edit';
				var updateUrl = '{!! route($page['route'] . '.index') !!}' + '/' + id;				

				getEditData(id, data, url, updateUrl);
				modalCurrency(tr, '#edit-form');
			});

			$('#datatable tbody').on('click.dt', '.delete', function(event)
			{
				event.preventDefault();

				if($(this).hasClass('disabled'))
				{
					$.notify({ message: 'This {!! strtolower($page['item']) !!} is used in other modules.' }, globalVar.dangerNotify);
					return false;
				}

				var formUrl = $(this).parent('form').prop('action');
				var formData = $(this).parent('form').serialize();
				var itemName = '{!! $page['item'] !!}';
				var message = 'This {!! strtolower($page['item']) !!} will be removed along with all associated data.<br>Are you sure you want to delete this {!! strtolower($page['item']) !!}?'; 

				confirmDelete(formUrl, formData, table, itemName, message);
			});				

			var bulkHtml = "<div class='info col-xs-12 col-sm-4 col-md-3 col-lg-3'>";
			bulkHtml += "<p class='selection'>No {!! $page['item'] !!} Selected</p>";
			bulkHtml += "</div>";
			bulkHtml += "<div class='action col-xs-12 col-sm-8 col-md-9 col-lg-9'>";

			@if(isset($page['mass_del_permit']) && $page['mass_del_permit'] == true)
				@if(!isset($page['modal_bulk_delete']) || (isset($page['modal_bulk_delete']) && $page['modal_bulk_delete'] == true))
					bulkHtml += "<button id='bulk-delete' class='btn only-icon' data-toggle='tooltip' data-placement='bottom' title='Delete'><i class='mdi mdi-delete'></i></button>";
				@endif	
			@endif	

			@if(isset($page['bulk']))
				@if(strpos($page['bulk'], 'convert') !== false)
					bulkHtml += "<button class='btn'>Convert</button>";
				@endif

				@if(strpos($page['bulk'], 'update') !== false && isset($page['mass_update_permit']) && $page['mass_update_permit'] == true)
					bulkHtml += "<button id='bulk-update' class='btn'>Mass Update</button>";
				@endif

				@if(strpos($page['bulk'], 'sms') !== false)
					bulkHtml += "<button id='bulk-sms' class='btn'>Send SMS</button>";
				@endif

				@if(strpos($page['bulk'], 'email') !== false)
					bulkHtml += "<button id='bulk-email' class='btn'>Send Email</button>";				
				@endif

				@if(strpos($page['bulk'], 'inactive') !== false)
					bulkHtml += "<button status='inactive' danger='1' class='bulk-status btn'>Inactive</button>";		
				@endif

				@if(strpos($page['bulk'], 'active') !== false)
					bulkHtml += "<button status='active' danger='0' class='bulk-status btn'>Active</button>";		
				@endif			
			@endif

			bulkHtml += "</div>";
			$('div.bulk').html(bulkHtml);

			@if(!isset($table['checkbox']) || (isset($table['checkbox']) && $table['checkbox'] == true))

				var tBody = '#datatable tbody';
				var selectAllId = '#select-all';
				var inputSingleRow = 'input.single-row';
				var itemSingular = '{!! $page['item'] !!}';
				var itemPlural = '{!! $page['item_plural'] or $page['item'] . 's' !!}';
				
				bulkChecked(tBody, selectAllId, inputSingleRow, itemSingular, itemPlural);

				$('#bulk-delete').click(function()
				{
					$('[data-toggle="tooltip"]').tooltip('hide');

					var formUrl = '{!! route($page['route'] . '.bulk.delete') !!}';
					var fieldName = '{!! $page['field'] !!}' + '[]';		
					var checkedCount = $("input[name='"+ fieldName +"']:checked").size();
					var formData = $("input[name='"+ fieldName +"']:checked").serialize();
					var itemName = '{!! $page['item'] !!}';
					var message = 'The selected {!! strtolower($page['item']) !!}';

					if(checkedCount > 1)
					{
						itemName = '{!! $page['item_plural'] or $page['item'] . 's' !!}';
						message = 'The selected {!! isset($page['item_plural']) ? strtolower($page['item_plural']): strtolower($page['item']) . 's' !!}';
					}

					message += ' will be removed along with all associated data.<br>Are you sure you want to delete?';

					confirmBulkDelete(formUrl, formData, table, itemName, message, checkedCount);
				});

				$('#bulk-update').click(function()
				{
					var fieldName = '{!! $page['field'] !!}' + '[]';		
					var checkedCount = $("input[name='"+ fieldName +"']:checked").size();
					bulkUpdate(checkedCount);
				});

				$('#bulk-email').click(function()
				{
					var fieldName = '{!! $page['field'] !!}' + '[]';		
					var checkedCount = $("input[name='"+ fieldName +"']:checked").size();
					bulkEmail(checkedCount);
				});

				@if(Route::has($page['route'] . '.bulk.status'))
					$('.bulk-status').click(function()
					{
						$('[data-toggle="tooltip"]').tooltip('hide');

						var formUrl = '{!! route($page['route'] . '.bulk.status') !!}';
						var fieldName = '{!! $page['field'] !!}' + '[]';		
						var checkedCount = $("input[name='"+ fieldName +"']:checked").size();
						var formData = $("input[name='"+ fieldName +"']:checked").serialize();					
						var statusType = $(this).attr('status');
						var statusDanger = $(this).attr('danger');
						var itemName = '{!! $page['item'] !!}';
						var message = 'The selected {!! strtolower($page['item']) !!}';

						if(checkedCount > 1)
						{
							itemName = '{!! $page['item_plural'] or $page['item'] . 's' !!}';
							message = 'The selected {!! isset($page['item_plural']) ? strtolower($page['item_plural']): strtolower($page['item']) . 's' !!}';
						}

						message += ' status will be set to ' + statusType + '.<br>Are you sure you want to update status?';

						formData += '&status=' + statusType;
						confirmUpdateStatus(formUrl, formData, statusType, statusDanger, table, itemName, message, checkedCount);
					});
				@endif	

			@endif	
		});
	</script>

	@if(isset($page['script']) && $page['script'] == true)
		@include($page['view'] . '.partials.script')
	@endif	
@endpush

