@extends('templates.listing-subnav')

@section('panelbtn')
	@permission('settings.lead_scoring_rule.edit')
		<button type='button' class='btn btn-type-a lead-score-slider'><i class='fa fa-th-large'></i> Classify Lead Score</button>
	@endpermission
@endsection

@section('listingextend')
	@include('admin.setting.leadscore.partials.modal-classify')
@endsection