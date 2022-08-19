<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminCurrencyController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:settings.currency.view', ['only' => ['index', 'currencyData']]);
		$this->middleware('admin:settings.currency.create', ['only' => ['store']]);
		$this->middleware('admin:settings.currency.edit', ['only' => ['edit', 'update', 'updateBase']]);
		$this->middleware('admin:settings.currency.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Currencies', 'item' => 'Currency', 'list_title' => 'Currencies', 'field' => 'currencies', 'view' => 'admin.setting.currency', 'route' => 'admin.administration-setting-currency', 'plain_route' => 'admin.currency', 'permission' => 'settings.currency', 'subnav' => 'setting', 'modal_bulk_delete' => false, 'modal_size' => 'large', 'save_and_new' => false];
		$table = ['thead' => [['NAME', 'style' => 'min-width: 195px'], ['CODE', 'data_class' => 'center'], ['SYMBOL', 'data_class' => 'center'], ['EXCHANGE&nbsp;RATE', 'style' => 'max-width: 105px'], ['FACE&nbsp;VALUE', 'style' => 'max-width: 90px']], 'list_order' => 'asc', 'action' => Currency::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'code', 'symbol', 'exchange_rate', 'face_value', 'action'], Currency::hideColumns());
		$reset_position = Currency::resetPosition();

		return view('admin.setting.currency.index', compact('page', 'table', 'reset_position'));
	}



	public function currencyData(Request $request)
	{
		if($request->ajax()) :
			$currencies = Currency::orderBy('position')->get(['id', 'position', 'name', 'code', 'symbol', 'symbol_position', 'decimal_separator', 'thousand_separator', 'exchange_rate', 'face_value', 'base']);
			return DatatablesManager::currencyData($currencies, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = Currency::validate($data);
			$bottom_position = Currency::getBottomPosition();
			$separators = [",", ".", "'", " "];

			if($validation->passes()) :				
				$currency = new Currency;
				$currency->name = $request->name;
				$currency->code = strtoupper($request->code);
				$currency->face_value = $request->face_value;
				$currency->exchange_rate = $request->exchange_rate;
				$currency->symbol = trim($request->symbol);
				$currency->symbol_position = $request->symbol_position;
				$currency->decimal_separator = in_array_filter($request->decimal_separator, $separators, '.');
				$currency->thousand_separator = in_array_filter($request->thousand_separator, $separators, ',');
				$currency->position = $bottom_position;
				$currency->save();				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, Currency $currency)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($currency) && isset($request->id)) :
				if($currency->id == $request->id) :
					$info = $currency->toArray();

					$info['freeze'] = [];

					if($currency->base) :
						$info['freeze'][] = 'face_value';
						$info['freeze'][] = 'exchange_rate';
					endif;	

					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.administration-setting-currency.index');
	}



	public function update(Request $request, Currency $currency)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($currency) && isset($request->id) && $currency->id == $request->id) :
				$validation = Currency::validate($data);
				$separators = [",", ".", "'", " "];

				if($validation->passes()) :					
					$currency->name = $request->name;
					$currency->code = strtoupper($request->code);
					$currency->face_value = $currency->base ? 1 : $request->face_value;
					$currency->exchange_rate = $currency->base ? 1 : $request->exchange_rate;
					$currency->symbol = trim($request->symbol);
					$currency->symbol_position = $request->symbol_position;
					$currency->decimal_separator = in_array_filter($request->decimal_separator, $separators, '.');
					$currency->thousand_separator = in_array_filter($request->thousand_separator, $separators, ',');
					$currency->save();
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



	public function updateBase(Request $request, Currency $currency)
	{
		if($request->ajax()) :
			$status = false;
			$checked = null;

			if(isset($currency) && isset($request->id) && $currency->id == $request->id) :
				Currency::exchangeRateReform($currency);
				Currency::where('id', '!=', $currency->id)->update(['base' => 0]);
				$currency->update(['base' => 1]);
				$status = true;
			endif;

			return response()->json(['status' => $status, 'checked' => $checked]);
		endif;
	}



	public function destroy(Request $request, Currency $currency)
	{
		if($request->ajax()) :
			$status = true;

			if($currency->id != $request->id || $currency->base || !$currency->can_delete) :
				$status = false;
			endif;

			if($status == true) :
				$currency->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}
}