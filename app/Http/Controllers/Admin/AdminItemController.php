<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\Item;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminItemController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:sale.item.view', ['only' => ['index', 'itemData']]);
		$this->middleware('admin:sale.item.create', ['only' => ['store']]);
		$this->middleware('admin:sale.item.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:sale.item.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Sale Items List', 'item' => 'Item', 'field' => 'items', 'view' => 'admin.item', 'route' => 'admin.sale-item', 'permission' => 'sale.item', 'modal_size' => 'medium', 'mass_update_permit' => permit('mass_update.item'), 'mass_del_permit' => permit('mass_delete.item')];
		$table = ['thead' => ['ITEM', 'UNIT&nbsp;PRICE', ['TAX ( % )', 'data_class' => 'center'], ['DISCOUNT ( % )', 'data_class' => 'center']], 'list_order' => 'asc', 'checkbox' => Item::allowMassAction(), 'action' => Item::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'name', 'price' => ['className' => 'align-r'], 'tax', 'discount', 'action'], Item::hideColumns());

		return view('admin.item.index', compact('page', 'table'));
	}



	public function itemData(Request $request)
	{
		if($request->ajax()) :
			$items = Item::orderBy('id')->get();
			return DatatablesManager::itemData($items, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = Item::validate($data);

			if($validation->passes()) :
				$item = new Item;
				$item->name = $request->name;
				$item->price = $request->price;
				$item->currency_id = $request->currency_id;
				$item->tax = $request->tax;
				$item->discount = $request->discount;
				$item->save();
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, Item $item)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($item) && isset($request->id)) :
				if($item->id == $request->id) :
					$info = $item;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.sale-item.index');
	}



	public function update(Request $request, Item $item)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($item) && isset($request->id) && $item->id == $request->id) :
				$validation = Item::validate($data);
				if($validation->passes()) :
					$item->name = $request->name;
					$item->price = $request->price;
					$item->currency_id = $request->currency_id;
					$item->tax = $request->tax;
					$item->discount = $request->discount;
					$item->save();
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'saveId' => $request->id]);
		endif;
	}



	public function destroy(Request $request, Item $item)
	{
		if($request->ajax()) :
			$status = true;

			if($item->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$item->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$items = $request->items;

			$status = true;

			if(isset($items) && count($items) > 0) :
				Item::whereIn('id', $items)->delete();
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}



	public function selectItemData(Request $request, $linked_type = null, $linked_id = null)
	{
		if($request->ajax()) :
			$where_not_in = [];

			if(isset($linked_type) && isset($linked_id)) :
				$linked = morph_to_model($linked_type)::find($linked_id);
				if(isset($linked)) :
					$where_not_in = $linked->items->pluck('id')->toArray();
				endif;	
			endif;

			$items = Item::whereNotIn('id', $where_not_in)->orderBy('id')->get();
				
			return DatatablesManager::selectItemData($items, $request);
		endif;
	}



	public function cartItemData(Request $request, $linked_type, $linked_id)
	{
		if($request->ajax()) :
			$linked = morph_to_model($linked_type)::find($linked_id);
			if(isset($linked)) :
				$items = $linked->items;
				return DatatablesManager::cartItemData($items, $request);
			endif;
			
			return null;	
		endif;
	}



	public function cartItemAdd(Request $request, $linked_type, $linked_id)
	{
		if($request->ajax()) :
			$linked = morph_to_model($linked_type)::find($linked_id);
			$data = $request->all();
			$status = false;
			$errors = null;
			$realtime = [];

			if(isset($linked) && $linked_type == $request->linked_type && $linked_id == $request->linked_id) :
				$validation = Item::cartItemAddValidate($data);
				if($validation->passes()) :
					$status = true;
					$data = [];
					
					foreach($request->items as $item_id) :
						$item_exists = $linked->items()->where('item_id', $item_id)->get()->count();

						if(!$item_exists) :
							$item = Item::find($item_id);
							$rate = $item->price;
							if($item->currency_id != $linked->currency_id) :
								$rate = Currency::exchangeCurrency([$item->currency_id, $item->price], $linked->currency_id);
							endif;

							$data = ['item_id' => $item_id, 'linked_type' => $linked_type, 'linked_id' => $linked_id, 'unit' => 'Unit', 'quantity' => 1, 'rate' => $rate];
							DB::table('cart_items')->insert($data);
						endif;
					endforeach;

					$realtime['total'] = $linked->item_total;
				else :
					$errors = $validation->getMessageBag()->toArray();				
				endif;	
			else :
				$errors['linked_id'] = 'Invalid module';	
			endif;

			return response()->json(['status' => $status, 'realtime' => $realtime, 'errors' => $errors]);
		endif;
	}



	public function cartItemUpdate(Request $request, $linked_type, $linked_id, Item $item)
	{
		if($request->ajax()) :
			$linked = morph_to_model($linked_type)::find($linked_id);
			$data = $request->all();
			$status = false;
			$realtime = [];
			$errors = [];

			if(isset($linked) && isset($item) && $linked_type == $request->linked_type && $linked_id == $request->linked_id) :
				$validation = Item::cartItemUpdateValidate($data);
				if($validation->passes()) :
					$status = true;
					$data = [];
					
					if(isset($request->price)) :
						$data['rate'] = $request->price;
					endif;	

					if(isset($request->quantity)) :
						$data['quantity'] = $request->quantity;
					endif;

					DB::table('cart_items')
					->where('item_id', $item->id)
					->where('linked_type', $linked_type)
					->where('linked_id', $linked_id)
					->update($data);

					$realtime['total'] = $linked->item_total;
				else :
					$errors = $validation->getMessageBag()->toArray();				
				endif;	
			else :
				$errors['invalid'] = 'Invalid ' . $linked_type . ' or item';	
			endif;	

			return response()->json(['status' => $status, 'realtime' => $realtime, 'errors' => $errors]);
		endif;
	}
	


	public function cartItemRemove(Request $request, $linked_type, $linked_id, Item $item)
	{
		if($request->ajax()) :
			$linked = morph_to_model($linked_type)::find($linked_id);
			$status = false;
			$realtime = [];

			if(isset($linked) && isset($item) && $request->remove == true) :
				$status = true;
				$linked->items()->detach($item->id);
				$realtime['total'] = $linked->item_total;
			endif;

			return response()->json(['status' => $status, 'realtime' => $realtime]);
		endif;
	}
}