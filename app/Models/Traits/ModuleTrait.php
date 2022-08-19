<?php 

namespace App\Models\Traits;

use Carbon\Carbon;

trait ModuleTrait
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

	public function getLastActivityAttribute()
	{
		// Email, Call, SMS, Chat, Completed Task(by due date), Event(by end date)
		$types = [];
		$outcome = ['type' => null, 'date' => null];
		$today = date('Y-m-d H:i:s');
		$tasks = $this->tasks()->whereCompletion_percentage(100)->where('due_date', '<=', $today)->latest('due_date');
		$events = $this->events()->where('end_date', '<=', $today)->latest('end_date');

		if($tasks->count()) :
			$types['task'] = $tasks->first()->dateTimestamp('due_date');
		endif;

		if($events->count()) :
			$types['event'] = $events->first()->dateTimestamp('end_date');
		endif;	

		if(count($types)) :
			$type = array_search(max($types), $types);
			$date = Carbon::createFromTimestamp(max($types));
			$outcome = ['type' => $type, 'date' => $date];
		endif;	

		return $outcome;
	}

	public function getLastActivityTypeAttribute()
	{
		return $this->last_activity['type'];
	}

	public function getLastActivityDateAttribute()
	{
		return $this->last_activity['date'];
	}

	public function getNextActivityAttribute()
	{
		// Closest near future Email, Call, SMS, Task(by start date), Event(by start date)
		$types = [];
		$outcome = ['type' => null, 'date' => null];
		$today = date('Y-m-d H:i:s');
		$tasks = $this->tasks()->where('completion_percentage' , '<', 100)->where('start_date', '>=', $today)->orderBy('start_date');
		$events = $this->events()->where('start_date', '>=', $today)->orderBy('start_date');

		if($tasks->count()) :
			$types['task'] = $tasks->first()->dateTimestamp('start_date');
		endif;

		if($events->count()) :
			$types['event'] = $events->first()->dateTimestamp('start_date');
		endif;

		if(count($types)) :
			$type = array_search(min($types), $types);
			$date = Carbon::createFromTimestamp(min($types));
			$outcome = ['type' => $type, 'date' => $date];
		endif;	

		return $outcome;
	}

	public function getNextActivityTypeAttribute()
	{		
		return $this->next_activity['type'];
	}

	public function getNextActivityDateAttribute()
	{
		return $this->next_activity['date'];
	}

	public function updatedBy()
	{
		$last_updated = $this->revisionHistory->last();

		if($this->has_social_media && $this->socialmedia()->count()) :
			$social_last_updated = $this->socialmedia()->latest('id')->first()->revisionHistory->last();

			if(isset($social_last_updated) && $social_last_updated->updated_at > non_property_checker($last_updated, 'updated_at')) :
				$last_updated = $social_last_updated;
			endif;	
		endif;	

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

		if($this->has_social_media && $this->socialmedia()->count()) :
			$social_last_updated = $this->socialmedia()->latest('id')->first()->revisionHistory->last();

			if(isset($social_last_updated) && $social_last_updated->updated_at > $latest_updated_at) :
				$latest_updated_at = $social_last_updated->updated_at;
			endif;	
		endif;	

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

	public function getNextTaskHtmlAttribute()
	{
		if(method_exists($this, 'tasks') && $this->tasks->count()) :
			$html = "<div class='next-action'>
						<h3 class='shadow-title'>Next Action</h3>";
			
			$count = 0;
			$current_year = date('Y');
			foreach($this->tasks()->latest('id')->get() as $task) :
				if($count > 2) :
					break;
				endif;	

				if(!is_null($task->due_date) && $task->completion_percentage < 100) :
					$break_date = explode('-', $task->due_date);
					$year = $break_date[0];

					$tooltip = '';
					if($year != $current_year) :
						$tooltip = "data-toggle='tooltip' data-placement='right' title='" . no_space($task->readableDate('due_date')) . "'";
					endif;

					$task_tooltip = '';
					if(strlen($task->name) > 17) :
						$task_tooltip = "data-toggle='tooltip' data-placement='top' title='" . no_space($task->name) . "'";
					endif;	

					$html .= "<p class='para-date'>
								<span $tooltip class='" . $task->due_date_status . "'>" . date('M j', strtotime($task->due_date)) . "</span>
								<a href='" . $task->show_route . "' $task_tooltip>" . str_limit($task->name, 17) . "</a>
							 </p>";

					$count++;
				endif;			 
			endforeach;	


			if($this->tasks->count() > 3) :
				$html .= "<p class='para-date'>
							<a class='shadow tab-link' tabkey='tasks'>View all...</a>
						 </p>";
			endif;		

			$html .= "</div>";

			return  $html;
		endif;

		return null;
	}
}