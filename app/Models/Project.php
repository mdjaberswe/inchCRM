<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Project extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	
	protected $table = 'projects';
	protected $fillable = ['account_id', 'project_owner', 'deal_id', 'name', 'description', 'start_date', 'end_date', 'status', 'completion_percentage', 'access'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{	
		$start_date = $data['start_date'];
		$start_date_minus = date('Y-m-d', strtotime($start_date . ' -1 day'));

		$rules = ["name"			=> "required|max:200",
				  "account_id"		=> "required|exists:accounts,id,deleted_at,NULL",
				  "project_owner"	=> "required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "start_date"		=> "date",
				  "end_date"		=> "date|after:$start_date_minus",
				  "description"		=> "max:65535",
				  "access"			=> "required|in:public,private",
				  "status"			=> "required|in:upcoming,in_progress,completed,cancelled"];

		return \Validator::make($data, $rules);
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeCompleted($query)
	{
	    $query->whereStatus('completed');
	}

	public function scopeReadableIdentifier($query, $name)
	{
		return $query->where('name', $name);
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameHtmlAttribute()
	{
		return "<a href='" . route('admin.project.show', $this->id) . "'>$this->name</a><br><span class='sm-txt'>" . $this->account->account_name . "</span>";
	}

	public function getOwnerHtmlAttribute()
	{
		return $this->owner->profile_html;
	}

	public function getDateHtmlAttribute($tooltip_position = null)
	{
		$date = '';
		$span_class = 'shadow normal';
		$tooltip_position = isset($tooltip_position) ? $tooltip_position : 'right';

		if(isset($this->end_date)) :
			$span_class = 'shadow';
			$date .= "<span class='c-danger' data-toggle='tooltip' data-placement='" . $tooltip_position . "' title='End Date'>" . $this->end_date . "</span>";
			$date .= '<br>';
		endif;

		if(isset($this->start_date)) :
			$date .= "<span class='" . $span_class . "' data-toggle='tooltip' data-placement='" . $tooltip_position . "' title='Start Date'>" . $this->start_date . "</span>";
		endif;

		return $date;
	}

	public function getCompletionPercentageHtmlAttribute()
	{
		return "<a class='link-center-underline'>" . $this->completion_percentage . "%</a>";
	}

	public function getTaskCountAttribute()
	{
		return $this->tasks()->count();
	}

	public function getCompletedTaskCountAttribute()
	{
		return $this->tasks()->whereCompletion_percentage(100)->count();
	}

	public function getTaskCompletionPercentageAttribute()
	{
		$task_completion_percentage = -1;

		if($this->task_count > 0) :
			$task_completion_percentage = ($this->completed_task_count / $this->task_count) * 100;
			$task_completion_percentage = floor($task_completion_percentage);
		endif;		
		
		return $task_completion_percentage;
	}

	public function getTaskStatHtmlAttribute()
	{
		$task_completion_percentage = $this->task_completion_percentage;
		$statement = $this->task_completion_percentage . '%';

		if($this->task_completion_percentage == -1) :
			$task_completion_percentage = 0;
			$statement = 'No Tasks';
		endif;
					
		$task_stat_html = "<a class='completion-show'>								  	
							  <div class='progress'>
						            <div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='" . $task_completion_percentage . "' aria-valuemin='0' aria-valuemax='100' style='width: " . $task_completion_percentage . "%'>
						                <span class='sr-only'>" . $task_completion_percentage . "% Complete</span>
						            </div>
						            <span class='shadow'>" . $statement . "</span>
					       		</div>
					        </a>";

		return $task_stat_html;	
	}

	public function getMilestoneCountAttribute()
	{
		return $this->milestones()->count();
	}

	public function getCompletedMilestoneCountAttribute()
	{
		return $this->milestones()->whereCompletion_percentage(100)->count();
	}

	public function getMilestoneCompletionPercentageAttribute()
	{
		$milestone_completion_percentage = -1;

		if($this->milestone_count > 0) :
			$milestone_completion_percentage = ($this->completed_milestone_count / $this->milestone_count) * 100;
			$milestone_completion_percentage = floor($milestone_completion_percentage);
		endif;		
		
		return $milestone_completion_percentage;
	}

	public function getMilestoneStatHtmlAttribute()
	{
		$milestone_completion_percentage = $this->milestone_completion_percentage;
		$statement = $this->milestone_completion_percentage . '%';

		if($this->milestone_completion_percentage == -1) :
			$milestone_completion_percentage = 0;
			$statement = 'No Milestones';
		endif;
					
		$milestone_stat_html = "<a class='completion-show'>								  	
							  <div class='progress'>
						            <div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='" . $milestone_completion_percentage . "' aria-valuemin='0' aria-valuemax='100' style='width: " . $milestone_completion_percentage . "%'>
						                <span class='sr-only'>" . $milestone_completion_percentage . "% Complete</span>
						            </div>
						            <span class='shadow'>" . $statement . "</span>
					       		</div>
					        </a>";

		return $milestone_stat_html;	
	}

	public function getContactIdListAttribute()
	{
		return $this->contacts->pluck('id')->toArray();
	}

	public function getMembersHtmlAttribute()
	{
		$members_html = '';

		foreach($this->members as $member) :
			if($member->id !=  $this->project_owner) :
				$members_html .= "<a href='" . route('admin.user.show', $member->id) . "' class='link-with-img' data-toggle='tooltip' data-placement='top' title='" . $member->name . "'>" . 
									"<img src='" . $member->avatar . "'>" . 
						  		 "</a>";
			endif;		  				
		endforeach;
		
		return $members_html;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function account()
	{
		return $this->belongsTo(Account::class);
	}

	public function deal()
	{
		return $this->belongsTo(Deal::class);
	}

	public function owner()
	{
		return $this->belongsTo(Staff::class, 'project_owner')->withTrashed();
	}

	// relation: belongsToMany
	public function members()
	{
		return $this->belongsToMany(Staff::class, 'project_member')->withTimestamps()->withTrashed();
	}

	public function contacts()
	{
		return $this->belongsToMany(Contact::class, 'project_contact');
	}

	// relation: hasMany
	public function milestones()
	{
		return $this->hasMany(Milestone::class);
	}

	public function estimates()
	{
		return $this->hasMany(Estimate::class);
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}

	public function expenses()
	{
		return $this->hasMany(Expense::class);
	}

	// relation: morphMany
	public function tasks()
	{
		return $this->morphMany(Task::class, 'linked');
	}

	public function calls()
	{
		return $this->morphMany(Call::class, 'related');
	}

	public function events()
	{
		return $this->morphMany(Event::class, 'linked');
	}

	public function notificationInfos()
	{
		return $this->morphMany(NotificationInfo::class, 'linked');
	}

	public function linearNotes()
	{
		return $this->morphMany(NoteInfo::class, 'linked');
	}

	public function notes()
	{
		return $this->morphMany(Note::class, 'linked');
	}

	public function attachfiles()
	{
		return $this->morphMany(AttachFile::class, 'linked');
	}

	// relation: morphToMany
	public function participants()
	{
		return $this->morphToMany(Contact::class, 'linked', 'participant_contacts')->withPivot('linked_type');
	}
}