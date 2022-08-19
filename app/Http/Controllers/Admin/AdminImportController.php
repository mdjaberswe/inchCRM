<?php

namespace App\Http\Controllers\Admin;

use Excel;
use App\Models\Import;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminImportController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();
	}



	public function getCsv(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$html = null;
			$csv_import = null;

			if(isset($request->module) &&  in_array($request->module, Import::modules())) :
				$csv_import = route('admin.import.map');
				$html = view('admin.' . $request->module . '.partials.import-csv')->render();
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'html' => $html]);
		endif;
	}



	public function map(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$html = null;
			$data = $request->all();
			$validation = Import::validate($data);
			$title = "Map Columns to " . ucfirst($request->module) . " Fields";
			$info = [];

			if($validation->passes()) :
				$file = $request->file('import_file');
				$file_name = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				$path = $file->getRealPath();	

				if(in_array($extension, ['csv', 'xls', 'xlsx'])) :
					$model = morph_to_model($request->module);
					$excel = \Excel::load($path);
					$row_collection = $excel->formatDates(true, 'Y-m-d H:i:s')->get();
					$row_array = $row_collection->toArray();

					if($row_collection->count()) :
						$import = new Import;
						$import->file_name = $file_name;
						$import->module_name = $request->module;
						$import->import_type = $request->import_type;
						$import->initial_data = json_encode($row_array);
						$import->save();

						$info['import'] = $import->id;
						$list = $model::fieldlist();

						asort($list);
						config(['excel.import.heading' => 'original']);
						$headings = $excel->get()->first()->keys()->toArray();
						config(['excel.import.heading' => 'slugged']);	
						
						$keys = $row_collection->first()->keys()->toArray();												
						$field_list = ['' => 'Choose a field'] + $list;
						$lower_field_list = array_map('strtolower',$field_list);
						$selected_fields = [];

						$tr = '';
						foreach($keys as $key => $column) :
							$auto_select = map_auto_select($headings[$key], $column, $field_list, $lower_field_list);
							
							if(!is_null($auto_select) && !in_array($auto_select, $selected_fields)) :
								$selected_fields[] = $auto_select;
							else :
								$auto_select = null;
							endif;							

							$tr .= render_map_row($headings[$key], $column, $field_list, $auto_select);							
						endforeach;	
						
						$html = view('admin.' . $request->module . '.partials.import-map', ['tr' => $tr])->render();
					else :
						$status = false;
						$errors['import_file'][] = 'The import file has no data.';
					endif;	
				else :
					$status = false;
					$errors['import_file'][] = 'The import file extension is not valid.';
				endif;				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'html' => $html, 'info' => $info, 'modalTitle' => $title]);
		endif;	
	}



	public function import(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$html = null;
			$data = $request->all();			
			$validation = Import::validateMap($data);
			$title = "Importing <span class='dots-processing'>...</span>";

			if($validation->passes()) :
				unset($data['_token']);

				$import = Import::find($request->import);
				$model = morph_to_model($import->module_name);
				$column = array_keys($data);
				$import_data = json_decode($import->initial_data, true);
				$column_key = array_keys($import_data[0]);
				$import_column = array_prepend($column_key, 'import');

				if($column === $import_column) :
					$field = array_keys($model::fieldlist());
					$in = 'in:' . implode(',', $field);
					$rules = array_fill_keys($column_key, $in);
					$field_validation = \Validator::make($data, $rules);

					if($field_validation->fails()) :
						$status = false;
						$field_errors = $field_validation->getMessageBag()->toArray();
						$field_errors = array_flatten($field_errors);
						foreach($field_errors as $field_error) :
							$errors['field'][] = $field_error;
						endforeach;						
					endif;
					
					if($status == true)	:
						$non_repeated = array_unique($data);
						$repeated = array_diff_assoc($data, $non_repeated);
						$repeated = array_filter($repeated);
						if(count($repeated)) :
							$status = false;
							foreach($repeated as $repeated_field_key) :
								$repeated_field = $model::fieldlist()[$repeated_field_key];								
								$errors['field'][] = 'The ' . strtolower($repeated_field) . ' field is repeated.';
							endforeach;
						endif;	

						if($status == true)	:

							$module_validation = $model::importValidate($data);
							$status = $module_validation['status'];

							if($status == false) :
								foreach($module_validation['errors'] as $module_error) :
									$errors['field'][] = $module_error;
								endforeach;	
							endif;	
							
							if($status == true)	:
								$html = view('partials.modals.common-import-success')->render();
								$response = ['status' => true, 'errors' => null, 'html' => $html, 'modalTitle' => $title];
								$info = ['map' => $data, 'import_data' => $import_data];
								flush_response($response);
								$job = '\App\Jobs\Import' . ucfirst($import->module_name) . 'Data'; 
								dispatch(new $job($import, $info));
							endif;
						endif;
					endif;
				else :
					$status = false;
					$errors['column'][] = 'File headers have not matched to file columns.';
				endif;	
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'html' => $html, 'modalTitle' => $title]);
		endif;	
	}
}	