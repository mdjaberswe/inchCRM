<?php

namespace App\Models;

use App\Models\BaseModel;
use Venturecraft\Revisionable\RevisionableTrait;

class Import extends BaseModel
{
	use RevisionableTrait;

	protected $table = 'imports';
	protected $fillable = ['file_name', 'module_name', 'is_imported', 'import_type', 'created_data', 'updated_data', 'skipped_data', 'initial_data'];
	protected $revisionCreationsEnabled = true;
	protected static $modules = ['lead', 'contact', 'account', 'deal', 'project', 'task', 'event', 'estimate', 'invoice'];

	public static function validate($data)
	{
		$valid_modules = implode(',', self::$modules);

		$rules = ["import_file"	=> "required|file",
				  "import_type"	=> "required|in:new,update,update_overwrite",
				  "module"		=> "required|in:$valid_modules"];

		return \Validator::make($data, $rules);
	}

	public static function validateMap($data)
	{	
		$rules = ['import'	=> 'required|exists:imports,id,is_imported,0'];
				
		return \Validator::make($data, $rules);
	}

	public static function modules()
	{
		return self::$modules;
	}	

	public static function clearNonImported($module = null)
	{
		$yesterday = date('Y-m-d H:i:s',strtotime('-1 days'));

		if(is_null($module)) :
			self::where('is_imported', 0)->where('created_at', '<', $yesterday)->delete();
		else :
			self::where('module_name', $module)->where('is_imported', 0)->where('created_at', '<', $yesterday)->delete();
		endif;

		return true;	
	}

	public function setRoute()
	{
		return true;
	}

	public function setPermission()
	{
		return 'administration.import';
	}
}