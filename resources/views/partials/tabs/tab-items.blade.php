<div class='full'>
	<h4 class='title-type-a'>Items of Interest</h4>

    <div class='right-top'>
   		<button type='button' class='btn btn-type-a add-multiple' modal-title='Add Items to {!! ucfirst($linked_type) !!}' modal-sub-title='{!! $linked->complete_name or $linked->name !!}' modal-datatable='true' datatable-url='{!! 'item-data/' . $linked_type . '/' . $linked->id !!}' data-action='{!! route('admin.cart.item.add', [$linked_type, $linked_id]) !!}' data-content='item.partials.modal-add-item' data-default='{!! 'linked_type:' . $linked_type . '|linked_id:' . $linked_id !!}' save-new='false' save-txt='Add to {!! ucfirst($linked_type) !!}'>
   			<i class='fa fa-plus-circle'></i> Add Items
   		</button>
    </div>

    <table id='datatable' class='table display responsive top-0 v-middle' cellspacing='0' width='100%' dataurl='{!! 'cart-item/' . $linked_type . '/' . $linked_id !!}' datacolumn='{!! $items_table['json_columns'] !!}' databtn='{!! table_showhide_columns($items_table) !!}' perpage='10' pagination='false' processing='false'>
		<thead>
			<tr>
				<th data-priority='1' style='max-width: 50px' data-class='center all' data-orderable='false'>#</th>				
				<th data-priority='2' data-class='all' data-orderable='false'>item&nbsp;name</th>
				<th data-priority='5' style='min-width: 120px; max-width: 120px' data-class='center' data-orderable='false'>quantity</th>	
				<th data-priority='6' style='min-width: 170px; max-width: 170px' data-class='align-r' data-orderable='false'><span class='padding-r-26'>unit&nbsp;price</span></th>	
				<th data-priority='4' style='min-width: 170px; max-width: 170px' data-class='align-r' data-orderable='false'>total</th> 
				<th data-priority='3' style='min-width: 50px; max-width: 50px' data-class='center all' data-orderable='false'></th>       			      			        			
			</tr>
		</thead>
	</table>

	<div class='full padding-0-65-20'>
		<div class='foot-bold-stat bold-space'>
			<span class='stat-title left-justify'>Total amount:</span> 
			<span class='stat-box {!! strlen($linked->item_total)  > 13 ? 'left-justify' : 'right-justify' !!}'>{!! $linked->currency->symbol_html !!}<span class='stat' realtime='total'>{!! $linked->item_total !!}</span></span>
			<input type='hidden' value='{!! $linked->item_total !!}' class='value' realtime='total'>
		</div>
	</div>
</div> <!-- end full -->	