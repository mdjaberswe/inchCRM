<table id='payments-table' class='table bg-none-a full'>
    <thead>
        <tr>
        	<th data-orderable='false'>Payment #</th>
        	<th data-orderable='false'>Date</th>
        	<th data-orderable='false'>Method</th>
        	<th data-orderable='false'>Note</th>
        	<th data-orderable='false'>Amount</th>
        	@if(permit('finance.payment.edit') || permit('finance.payment.delete'))
        		<th data-orderable='false' data-class-name='align-r' class='action-2'></th>
        	@endif	
        </tr>
    </thead>
</table>

@section('modalcreate')
    {!! Form::open(['route' => ['admin.invoice.payment.store', $invoice->id], 'method' => 'post', 'class' => 'form-type-a']) !!}
        @include('admin.payment.partials.form', ['form' => 'create'])
    {!! Form::close() !!}
@endsection

@section('modaledit')
    {!! Form::open(['route' => ['admin.invoice.payment.update', null, null], 'method' => 'put', 'class' => 'form-type-a']) !!}
        @include('admin.payment.partials.form', ['form' => 'edit'])
    {!! Form::close() !!}
@endsection

@push('scripts')
	<script>
		$(document).ready(function()
		{
			var table =
			$('#payments-table').on('init.dt', function()
			{

				$('[data-toggle="tooltip"]').tooltip();
				$('html').getNiceScroll().resize();

			}).DataTable({
					'dom'           : "<'full paging-false'r<'table-responsive't>>",
					'paging'		: false,
					'order'			: [],
					'processing'	: true,
					'oLanguage'		: {sProcessing: ''},
					'serverSide'	: true,
					'ajax'			: { 'url' : '{!! route('admin.invoice.payment.data', $invoice->id) !!}', 'type' : 'POST' },
					'columns'		: [
									   { data : 'payment_id' },
									   { data : 'payment_date' },
									   { data : 'payment_method', className: 'bold-tooltip' },
									   { data : 'note' },
									   { data : 'amount', className: 'align-r' },		
									   @if(permit('finance.payment.edit') || permit('finance.payment.delete'))							   
									   { data : 'action' }
									   @endif
									  ],
					'fnDrawCallback': function(oSettings)
									  {
									  	$('[data-toggle="tooltip"]').tooltip();
									  	$('html').getNiceScroll().resize();
									  }			  
			});

			globalVar.jqueryDataTable = table;

			$('#payments-table tbody').on('click.dt', '.dropdown-toggle', function(event)
			{
				var dropdownHeight = $(this).next('.dropdown-menu').height() + 2;
				var tr = $(this).closest('tr');
				var bottomHeight = 0;
				tr.nextAll('tr').each(function(index, value)
				{
					bottomHeight += $(value).height();
				}); 

				if(bottomHeight <= dropdownHeight)
				{
					$(this).parent('.dropdown').addClass('dropup');
				}
			});

			$('#payments-table tbody').on('click.dt', '.edit', function(event)
			{
				var id = $(this).attr('editid');
				var data = {'id' : id};
				var url = '{!! route('admin.invoice.payment.data', $invoice->id) !!}' + '/' + id + '/edit';
				var updateUrl = '{!! route('admin.invoice.payment.data', $invoice->id) !!}' + '/' + id;

				getEditData(id, data, url, updateUrl);
			});

			$('#payments-table tbody').on('click.dt', '.delete', function(event)
			{
				event.preventDefault();

				var formUrl = $(this).parent('form').prop('action');
				var formData = $(this).parent('form').serialize();
				var itemName = '{!! $page['item'] !!}';
				var message = 'This {!! strtolower($page['item']) !!} will be removed along with all associated data.<br>Are you sure you want to delete this {!! strtolower($page['item']) !!}?'; 

				confirmDelete(formUrl, formData, table, itemName, message);
			});
		});
	</script>
@endpush		