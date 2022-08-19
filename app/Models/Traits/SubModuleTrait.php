<?php 

namespace App\Models\Traits;

use Carbon\Carbon;

trait SubModuleTrait
{
	public static function defaultInfoType($type = null)
	{
		if(!is_null($type) && array_key_exists($type, self::informationTypes())) :
			return $type;
		endif;	

		return 'overview';
	}

	public static function fieldlist()
	{
		return self::$fieldlist;
	}

	public static function massfieldlist()
	{
		return self::$mass_fieldlist;
	}

	public static function massdropdown()
	{
		$except = array_diff(array_keys(self::fieldlist()), self::massfieldlist());
		$dropdown = array_except(self::fieldlist(), $except);
		return $dropdown;
	}

	public static function filterFieldList()
	{
		return self::$filter_fieldlist;
	}

	public static function filterFieldDropDown()
	{
		$except = array_diff(array_keys(self::fieldlist()), self::filterFieldList());
		$dropdown = array_except(self::fieldlist(), $except);
		return $dropdown;
	}

	public function getAllowedStaffTooltipAttribute()
	{
		$html = '';

		if($this->allowedstaffs->count()) :
			foreach($this->allowedstaffs as $allowed) :
				$html .= str_replace(' ', '&nbsp;', $allowed->staff->name) . '<br>';
			endforeach;	
		endif;
		
		return $html;	
	}

	public function getAccessHtmlAttribute()
	{
		$access_html = '';
		$title = is_null($this->complete_name) ? $this->name : $this->complete_name;
		$allowed_staff = $this->allowedstaffs->count();

		if($this->access == 'public') :
			$access_html = "Public - <span class='c-shadow sm'>Read Only</span>";
		endif;

		if($this->access == 'public_rwd') :
			$access_html = "Public - <span class='c-shadow sm'>Read/Write/Delete</span>";
		endif;

		if($this->access == 'private') :
			$access_html = 'Private';
			if($allowed_staff) :
				$access_html .= " - <a class='private-users' editid='" . $this->id .  "' type='" . $this->identifier . "' modal-title='" . $title . "' data-toggle='tooltip' data-placement='top' data-html='true' title='" . $this->allowed_staff_tooltip . "'>" . $allowed_staff . " (users)</a>";
			else :
				$access_html .= " <a class='private-users link-icon-md' data-toggle='tooltip' data-placement='top' title='" . $this->allowed_staff_tooltip . "Allow&nbsp;Some&nbsp;Users&nbsp;Only' editid='" . $this->id .  "' type='" . $this->identifier . "' modal-title='" . $title . "'><i class='mdi mdi-account-multiple'></i></a>";
			endif;		
		endif;

		return $access_html;
	}

	public function getHideInfoAttribute()
	{
		$session_var = $this->identifier . '_hide_details';
		$status = (\Session::has($session_var) && \Session::get($session_var)) ? true : false;
		return $status;
	}	

	public function updatedBy()
	{
		$last_updated = $this->revisionHistory->last();

		if($this->allowedstaffs()->count()) :
			$allowed_staffs_updated = $this->allowedstaffs()->latest('id')->first()->revisionHistory->last();

			if(isset($allowed_staffs_updated) && $allowed_staffs_updated->updated_at > non_property_checker($last_updated, 'updated_at')) :
				$last_updated = $allowed_staffs_updated;
			endif;	
		endif;	
		
		if(isset($last_updated)) :
			return $last_updated->userResponsible();
		endif;

		return \App\Models\Staff::superAdmin();
	}

	public function getModifiedAtAttribute()
	{
		$latest_updated_at = $this->updated_at;

		if($this->allowedstaffs()->count()) :
			$allowed_staffs_updated = $this->allowedstaffs()->latest('id')->first()->revisionHistory->last();

			if(isset($allowed_staffs_updated) && $allowed_staffs_updated->updated_at > $latest_updated_at) :
				$latest_updated_at = $allowed_staffs_updated->updated_at;
			endif;	
		endif;	

		return $latest_updated_at;
	}

	public function getUpdatedAmpmAttribute()
	{
		return $this->modified_at->format('Y-m-d h:i:s a');
	}

	public function getNotesHtmlAttribute($latest_id = null, $end_down = null)
	{
		$html = '';
		$i = 0;

		if($this->notes->count()) :
			$notes = $this->notes()->wherePin(0);
			
			if(isset($latest_id)) :
				$notes = $notes->where('id', '<', $latest_id);
			endif;	

			$notes = $notes->latest()->take(10)->get();

			foreach($notes as $note) :
				$i++;
				$top = $i == 1 ? true : null;
				$html .= $note->getNoteHtmlAttribute($top);
			endforeach;
		endif;	

		if(isset($end_down) && isset($latest_id)) :
			$end_down_disable = $this->notes()->where('id', '<', $latest_id)->count() < 11 ? 'disable' : '';

			$html .= "<div class='timeline-info end-down " . $end_down_disable . "'>
						<i class='load-icon fa fa-circle-o-notch fa-spin'></i>
						<div class='timeline-icon'><a class='load-timeline'><i class='fa fa-angle-down'></i></a></div>
					 </div>";
		endif;	

		return $html;	
	}

	public function getPinNoteHtmlAttribute()
	{
		$pin_note = $this->notes()->wherePin(1)->get();

		if($pin_note->count()) :
			$pin_note = $pin_note->first();
			$this->notes()->where('id', '!=', $pin_note->id)->update(['pin' => 0]);

			return $pin_note->getNoteHtmlAttribute(null, null, true);
		endif;
		
		return null;	
	}
}