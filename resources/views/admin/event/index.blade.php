@extends('layouts.default')

@section('content')

	<div class='row'>
	    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
	    	<div class='full'>
		        <h4 class='title-type-a bold'>Calendar</h4>

		        <div class='right-top'>
		        	@permission('advanced.calendar.create_event')<button type='button' id='add-new-btn' class='btn btn-type-a'><i class='fa fa-plus-circle'></i> Add Event</button>@endpermission
		        </div>
		    </div>
		    
		    <div class='full padding-20-t-0'>
		    	<div class='calendar'></div>
		    </div>  
	    </div> <!-- end div-type-a -->
	</div> <!-- end row -->

@endsection

@section('modalcreate')
    {!! Form::open(['route' => 'admin.event.store', 'method' => 'post', 'class' => 'form-type-a']) !!}
        @include('admin.event.partials.form', ['form' => 'create'])
    {!! Form::close() !!}
@endsection

@section('modaledit')
    {!! Form::open(['route' => ['admin.event.update', null], 'method' => 'put', 'class' => 'form-type-a']) !!}
        @include('admin.event.partials.form', ['form' => 'edit'])
    {!! Form::close() !!}
@endsection

@push('scripts')
	<script>
		$(document).ready(function()
		{
			$('.none').hide();

			$('.calendar').fullCalendar(
			{
				header:
				{
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				selectable: true,
				selectHelper: true,
				editable: true,

				events:
				{
					url: '{!! route('admin.event.data') !!}',
					type: 'POST',
					data: { start_date: null, end_date: null },
  				},

				select: function(start, end)
				{
					addNewEvent();
					$("input[name='start_date']").val(moment(start).hours(10).format('YYYY-MM-DD hh:mm A'));
					$("input[name='end_date']").val(moment(end).add('-1', 'days').hours(11).format('YYYY-MM-DD hh:mm A'));
				},

				eventDrop: function(event, delta, revertFunc)
				{
					$.ajax(
					{
						type 	: 'POST',
						url		: '{!! route('admin.event.update.position') !!}',
						data 	: { id : event.id, start : event.start.format(), end : event.end.format() },
					});
				},

				eventClick: function(event, jsEvent, view)
				{
					var id = event.id;
					var data = {'id' : id};
					var url = '{!! route('admin.event.index') !!}' + '/' + id + '/edit';
					var updateUrl = '{!! route('admin.event.index') !!}' + '/' + id;

					getEditData(id, data, url, updateUrl);
				},
			});

			$('.delete').click(function(event)
			{
				event.preventDefault();
				var formUrl = $(this).parent('form').prop('action');
				var formData = $(this).parent('form').serialize();
				var itemName = '{!! $page['item'] !!}';
				var message = 'This {!! strtolower($page['item']) !!} will be removed along with all associated data.<br>Are you sure you want to delete this {!! strtolower($page['item']) !!}?'; 
				confirmDelete(formUrl, formData, null, itemName, message);
			});
		});
	</script>
@endpush			