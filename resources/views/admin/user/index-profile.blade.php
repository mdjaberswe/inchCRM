@extends('layouts.default')

@section('content')	
	<div class='row'>
		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 div-type-a'>
			<h4 class='title-type-a near'>{!! $page['item_plural'] or $page['item'] . 's' !!}</h4>

			<div class='right-top'>
				<div class='btn-group'>
					<a href='{!! route('admin.user.index') !!}' class='btn thin btn-type-a' data-toggle='tooltip' data-placement='bottom' title='Tabular'><i class='fa fa-list'></i></a>
					<a href='{!! route('admin.user.profilecard') !!}' class='btn thin btn-type-a active' data-toggle='tooltip' data-placement='bottom' title='Grid'><i class='fa fa-th-large'></i></a>
				</div>
				@permission('user.create')	
					<button type='button' class='btn btn-type-a'><i class='fa fa-envelope'></i> Send Invitation Email</button>
        			<button type='button' id='add-new-btn' class='btn btn-type-a'><i class='fa fa-user-plus'></i> Add User</button>
	        	@endpermission
			</div>	
		</div>
	</div>	

	<div class='row left-early'>
		@foreach($staffs as $staff)
			<div class='col-xs-12 col-sm-6 col-md-4 col-lg-4 user-info'>
				<div class='full div-type-a space-bottom div-profile-card'>
					<div class='full avatar center'>
						<img src='{!! $staff->avatar !!}' alt='{!! $staff->last_name !!}' class='img-type-a'>
						@if($staff->follow_command_rule)
							<a class='profile-picture-btn btn-type-b'  editid='{!! $staff->id !!}' data-toggle='tooltip' data-placement='top' title='Change avatar'><i class='fa fa-camera'></i></a>
						@endif
						<div class='div-type-k'>
							{!! $staff->status_html !!}
						</div>
					</div>

					<div class='full div-type-i'>
						<p class='para-type-g'>{!! "<span class='user-name'>" . str_limit($staff->name, 40, ".") . "</span> <span class='access-status'>" . $staff->admin_html . "</span>" !!}</p>
						<p class='para-type-h'>{!! str_limit($staff->title, 20, '.') !!} at <a href='#'>inchCRM</a></p>
						<p class='para-type-i'><span class='fa fa-envelope-o'></span> {!! str_limit($staff->email, 30) !!}</p>
						<p class='para-type-i'><span class='fa fa-phone'></span> {!! str_limit($staff->phone, 30) !!}</p>
						<p class='center'><a href='{!! route('admin.user.show', $staff->id) !!}' class='btn btn-type-a'><span class='fa fa-user-circle'></span> Profile</a></p>
					</div>
				</div>	
			</div>
		@endforeach	
	</div>

	@if($staffs->links())
		<div class='row hr'>
			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 pagination-box'>
	            {{ $staffs->links() }}
	        </div>
	    </div>  
    @endif  
@endsection

@section('modalcreate')
	{!! Form::open(['route' => 'admin.user.store', 'method' => 'post', 'class' => 'form-type-a']) !!}
	    @include('admin.user.partials.form', ['form' => 'create'])
	{!! Form::close() !!}
@endsection

@section('extend')	
	@include('admin.user.partials.modal-profile-picture')
@endsection

@push('scripts')
	@include('admin.user.partials.script')
@endpush	