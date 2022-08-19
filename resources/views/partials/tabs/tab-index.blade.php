<div class='full'>	
	{!! tab_nav_html($page['tabs']) !!}
</div> <!-- end full -->

<div id='item-tab-details' class='full {!! $page['tabs']['min_height_class'] or 'min-h-230' !!}' item='{!! $page['item'] !!}' itemid='{!! $page['tabs']['item_id'] !!}' taburl='{!! $page['tabs']['url'] !!}'>
	<div id='item-tab-content'>
		@include($page['view'] . '.partials.tabs.tab-' . $page['tabs']['default'])
	</div> <!-- item-tab-content -->	
</div> <!-- end item-tab-details -->

@push('scripts')
	<script>
		$(document).ready(function()
		{
			tabDatatableInit(null, 'item-tab-details');
		});
	</script>
@endpush