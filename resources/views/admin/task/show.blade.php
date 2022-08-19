@extends('layouts.master')

@section('content')
	
	<div class='row  div-panel'>
		<div class='full div-panel-header'>
		    <div class='col-xs-12 col-sm-5 col-md-5 col-lg-6'>	    	
		    	<h4 class='title-panel'>{!! $page['item_title'] !!}</h4>
		    </div>

		    <div class='col-xs-12 col-sm-7 col-md-7 col-lg-6 xs-left-sm-right'>
		    	<div class='dropdown dark inline-block'>
		    		<a class='btn md btn-type-a first dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'>
		    			<i class='mdi mdi-plus-circle-multiple-outline'></i> Add...
		    		</a>

		    		<ul class='dropdown-menu up-caret'>		
		    			<li><a class='add-multiple' data-item='file' data-action='{!! route('admin.file.store') !!}' data-content='partials.modals.upload-file' data-default='linked_type:task|linked_id:{!! $task->id !!}' save-new='false' data-modalsize='medium' modal-title='Add Files'><i class='lg mdi mdi-file-plus'></i> Add File</a></li>
		    			<li><a class='add-multiple' data-item='link' data-action='{!! route('admin.link.store') !!}' data-content='partials.modals.add-link' data-default='linked_type:task|linked_id:{!! $task->id !!}' save-new='false' data-modalsize='' modal-title='Add Link'><i class='fa fa-link'></i> Add Link</a></li>
		    		</ul>
		    	</div>

		    	<div class='dropdown dark inline-block'>
		    		<a class='btn thiner btn-type-a dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'>
		    			<i class='mdi mdi-dots-vertical fa-md pe-va'></i>
		    		</a>

		    		<ul class='dropdown-menu up-caret'>		    			
		    			<li><a><i class='fa fa-send-o sm'></i> Send Email</a></li>
		    			<li><a><i class='mdi mdi-message sm'></i> Send SMS</a></li>
		    			<li>
							{!! Form::open(['route' => ['admin.task.destroy', $task->id], 'method' => 'delete']) !!}
								{!! Form::hidden('id', $task->id) !!}
								{!! Form::hidden('redirect', true) !!}
								<button type='submit' class='delete'><i class='mdi mdi-delete'></i> Delete</button>
				  			{!! Form::close() !!}
		    			</li>
		    		</ul>
		    	</div>

		    	<div class='inline-block prev-next'>
		    		<a @if($task->prev_record) href='{!! route('admin.task.show', $task->prev_record->id) !!}' @endif class='inline-block prev @if(is_null($task->prev_record)) disabled @endif' data-toggle='tooltip' data-placement='top' title='Previous&nbsp;Record'><i class='pe pe-7s-angle-left pe-va'></i></a>
		    		<a @if($task->next_record) href='{!! route('admin.task.show', $task->next_record->id) !!}' @endif class='inline-block next @if(is_null($task->next_record)) disabled @endif' data-toggle='tooltip' data-placement='top' title='Next&nbsp;Record'><i class='pe pe-7s-angle-right pe-va'></i></a>
		    	</div>
		    </div>
		</div> <!-- end full -->

		@include('partials.tabs.tab-index')
		    
	</div> <!-- end row -->    

@endsection

@section('modals')
	@include('partials.modals.delete')
	@include('partials.modals.access')
@endsection

@push('scripts')
	{!! HTML::script('js/tabs.js') !!}
@endpush