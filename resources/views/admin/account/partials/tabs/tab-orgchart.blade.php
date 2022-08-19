<div class='full'>
	<h4 class='title-type-a margin-0'>Org Chart</h4>

	<div class='right-top dropdown dark'>
		<button type='button' class='btn btn-type-a dropdown-toggle m-bottom-5' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'>
			<i class='fa fa-download'></i> Export
		</button>
		<ul class='dropdown-menu up-caret'>		
			<li><a class='orgchart-export' data-export-type='pdf' data-export-name='{!! $account->account_name !!}' data-orgchart-id='account-hierarchy-{!! $account->id !!}'><i class='fa fa-file-pdf-o'></i> Export PDF</a></li> 
			<li><a class='orgchart-export' data-export-type='png' data-export-name='{!! $account->account_name !!}' data-orgchart-id='account-hierarchy-{!! $account->id !!}'><i class='fa fa-file-image-o'></i> Export PNG</a></li> 
		</ul>
	</div>
</div>	

<div class='full'>
	<div class='view-hierarchy-container full-height scroll-box both-xy'>
		<div id='account-hierarchy-{!! $account->id !!}' class='view-hierarchy' data-module='account' data-id='{!! $account->id !!}' data-url='{!! route('admin.account.hierarchy', $account->id) !!}' data-total-node='{!! $account->total_node !!}'></div>
	</div>
</div>