<?php

namespace App\Models;

use Venturecraft\Revisionable\Revision as VenturecraftRevision;
use Illuminate\Database\Eloquent\SoftDeletes;

class Revision extends VenturecraftRevision
{
	use SoftDeletes;

	public $table = 'revisions';
	protected $fillable = ['revisionable_type', 'revisionable_id', 'user_id', 'key', 'old_value', 'new_value'];
	protected $appends = ['revisionable_name', 'description'];
	protected $dates = ['deleted_at'];
	protected $revision_show = [];
	protected $revision_hide = [];

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeGroupByGet($query)
	{
		$query->groupBy('revisionable_type')
			  ->groupBy('revisionable_id')
			  ->groupBy('created_at')
			  ->get();
	}

	public function scopeFilterByType($query)
	{
		$query->whereNotIn('revisionable_type', $this->revision_hide);
	}

	public static function getHistoryRow($type, $type_id, $operator, $history_id, $key, $asc_order = true)
	{
		$row = self::where('revisionable_type', $type)
				   ->where('revisionable_id', $type_id)
				   ->where('id', $operator, $history_id)
				   ->where('key', $key);

		$row = $asc_order ? $row->first() : $row->latest('id')->first();		   

		return $row;
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getDescriptionAttribute()
	{
		$outcome = null;

		switch($this->key) :
			case 'created_at' :
				$outcome = 'New ' . $this->revisionable_name . ' Created';
			break;

			case 'deleted_at' :
				$outcome = $this->revisionable_name . ' Deleted';
			break;

			default :
				$outcome = $this->revisionable_name . ' Updated';
		endswitch;

		return $outcome;
	}

	public function getRevisionableNameAttribute()
	{
		return snake_to_ucwords($this->revisionable_type);
	}

	public function getCheckboxHtmlAttribute($css = null)
	{
		$checkbox_name = $this->table . '[]';
		$checkbox = "<div class='pretty danger smooth'><input class='single-row' type='checkbox' name='" . $checkbox_name . "' value='" . $this->id . "'><label><i class='mdi mdi-check'></i></label></div>";
		return $checkbox;		
	}

	public function getCompactActionHtml($item, $edit_route = null, $delete_route, $action_permission = [], $common_modal = false)
	{
		$delete = '';
		$dropdown_menu = '';

		if(isset($action_permission['delete']) && $action_permission['delete'] == true) :
			$delete = "<li>" .
						\Form::open(['route' => [$delete_route, $this->id], 'method' => 'delete']) .
							\Form::hidden('id', $this->id) .
							"<button type='submit' class='delete'><i class='mdi mdi-delete'></i> Delete</button>" .
						\Form::close() .
					  "</li>";

			$dropdown_menu = "<ul class='dropdown-menu'>" . $delete . "</ul>";		  		  
		endif;	

		$toggle = 'dropdown';
		$toggle_class = '';
		$toggle_tooltip = '';		
		if(empty($delete)) :
			$toggle = '';
			$toggle_class = 'disable';	
			$toggle_tooltip = "data-toggle='tooltip' data-placement='left' title='Permission&nbsp;denied'";	
		endif;

		$open = "<div class='action-box $toggle_class' $toggle_tooltip>";

		$dropdown = "<div class='dropdown'>
						<a class='dropdown-toggle $toggle_class' data-toggle='" . $toggle . "'>
							<i class='fa fa-ellipsis-v'></i>
						</a>";

		$close = "</div></div>";

		$action = $open . $dropdown . $dropdown_menu . $close;
		
		return $action;		  
	}

	public static function allowAction()
	{
		if(permit('advanced.activity_log.delete')) :
			return true;
		endif;	

		return false;
	}

	public static function allowMassAction()
	{
		if(permit('mass_delete.activity_log')) :
			return true;
		endif;	

		return false;
	}

	public static function hideColumns()
	{
		$hide_columns = [];

		if(!permit('advanced.activity_log.delete')) :
			$hide_columns[] = 'action';
		endif;

		if(!permit('mass_delete.activity_log')) :
			$hide_columns[] = 'checkbox';
		endif;

		return $hide_columns;	 	
	}

	public function readableDateHtml($date, $time = false)
	{
		$html = '';

		if(isset($this->$date)) :
			$readable_date = is_object($this->$date) ? $this->$date->format('M j, Y') : date('M j, Y', strtotime($this->$date));
			$html .= "<span>" . $readable_date . "</span>";
			if($time == true) :
				$readable_time = is_object($this->$date) ? $this->$date->format('h:i a') : date('h:i a', strtotime($this->$date));
				$html .= "&nbsp;<span class='shadow'>" . $readable_time . "</span>";
			endif;	
		endif;

		return $html;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function user()
	{
		return $this->belongsTo(User::class)->withTrashed();
	}

	public function linked()
	{		
		return morph_to_model($this->revisionable_type)::withTrashed()->find($this->revisionable_id);
	}
}