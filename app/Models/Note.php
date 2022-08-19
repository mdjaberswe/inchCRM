<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Note extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'notes';
	protected $fillable = ['note_info_id', 'linked_id', 'linked_type', 'pin'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{
		$rules = ['id'	=> 'required|exists:notes,id,deleted_at,NULL',
				  'note'=> 'required|max:65535',
				  'uploaded_files'	=> 'array|max:10',
				  'removed_files'	=> 'array|max:10'];

		return \Validator::make($data, $rules);
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNoteHtmlAttribute($top = null, $new = null, $is_pin = null)
	{
		$top = isset($top) ? 'top' : null;
		$circle = isset($new) ? "<span class='circle'></span>" : null;

		$pin = null;
		$pin_mark = null;
		$pin_btn = "<a class='pin-btn' data-pin='1' data-url='" . route('admin.note.pin', $this->id) . "'><i class='mdi mdi-pin'></i> Pin</a>";		
		if(isset($is_pin)) :
			$pin = 'pin';
			$pin_mark = "<span class='pin-mark rotate-45'><i class='mdi mdi-pin'></i></span>";
			$pin_btn = "<a class='pin-btn' data-pin='0' data-url='" . route('admin.note.pin', $this->id) . "'><i class='mdi mdi-pin-off'></i> Unpin</a>";			
		endif;	

		$attachfiles_html = "<div class='full attachfile-container'>";
		foreach($this->attachfiles as $file) :
			$attachfiles_html .= $file->thumb_html;
		endforeach;	
		$attachfiles_html .= "</div>";

		$html = "<div class='timeline-info $top $pin box' data-id='" . $this->id . "'>
					<div class='timeline-icon'>
					    <i class='mdi mdi-clipboard-text'></i>
					</div> " .
					$circle . $pin_mark . "
					<div class='timeline-details'>
						<img src='" . $this->info->updatedBy()->linked->avatar . "' alt='" . $this->info->updatedBy()->linked->last_name . "' class='img-avt'>
						<div class='timeline-details-content'>
							<div class='timeline-title'>
								<p class='along-action'>" . $this->info->description_html . "</p>
								<div class='action-box light'>
									<div class='inline-action'>
										<a class='timeline-edit' data-id='" . $this->id . "' data-url='" . route('admin.note.edit', $this->id) . "'>
											<i class='fa fa-pencil'></i>
										</a>
									</div>									
									<div class='dropdown'>
										<a class='dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown'>
											<i class='fa fa-ellipsis-v'></i>
										</a>
										<ul class='dropdown-menu'>
											<li>$pin_btn</li>
											<li>" .
												\Form::open(['route' => ['admin.note.destroy', $this->id], 'method' => 'delete']) .
													\Form::hidden('note_id', $this->id) .
													"<button type='submit' class='delete' data-item='note'><i class='mdi mdi-delete'></i> Delete</button>" .
									  			\Form::close() . "
											</li>
										</ul>
									</div>
								</div>
							</div>

							" . $attachfiles_html . "

							<div class='timeline-record'>
								<span class='capsule'>
									" . ucfirst($this->info->linked_type) . " - <a href='#'>" . $this->info->linked->name . "</a>
								</span>
								<span class='capsule'>
									<i class='dot fa fa-circle'></i>
									<i class='fa fa-clock-o '></i>
									<span data-toggle='tooltip' data-placement='bottom' title='" . $this->info->readableDateAmPm('updated_at') . "'>" . $this->info->readableDate('updated_at') . "</span>
								</span>
								<span class='capsule'>
									<i class='dot fa fa-circle'></i>	
									<span class='type'>by</span> 
									" . $this->info->updatedBy()->linked->name . "
								</span>						
							</div>
						</div>
					</div>
				</div>";

		$count = self::whereLinked_type($this->linked_type)
					 ->whereLinked_id($this->linked_id)
					 ->wherePin(0)
					 ->count();		

		if($count == 1 && $this->pin == 0) :
			$html .= "<div class='timeline-info end-down disable'>
						<i class='load-icon fa fa-circle-o-notch fa-spin'></i>
						<div class='timeline-icon'><a class='load-timeline'><i class='fa fa-angle-down'></i></a></div>
					 </div>";
		endif;			 

		return $html;		
	}

	public function getEditFormAttribute()
	{
		$attached_files = "";
		$attached_file_inputs = "";
		foreach($this->attachfiles as $file) :
			$attached_files .= 	"<div class='dz-preview dz-file-preview'>
									<div class='dz-details' style='min-width: 65%;'>" .
										$file->icon . " 
										<div class='dz-size'>
											<span>" . $file->size_html . "</span>
										</div>    
										<div class='dz-filename' data-original='" . $file->location . "'>
											<span data-checked='false'>" . $file->name . "</span>
										</div>  
									</div>  
									<a class='dz-remove edit-dz-remove' style='right: auto; margin-left: 10px;'>Remove file</a>
								</div>";

			$attached_file_inputs .= "<input type='hidden' name='uploaded_files[]' value='" . $file->location . "'>";
		endforeach;	

		$html = "<div class='timeline-form' data-posturl='" . route('admin.note.update', $this->id) . "'>
					<div class='form-group'>" .
						\Form::textarea('note', $this->info->description, ['class' => 'form-control atwho-inputor', 'placeholder' => 'Start typing to leave a note...', 'at-who' => Staff::atWhoData()]) .
						\Form::hidden('id', $this->id) . "
					</div>

					<div class='form-group bottom'>
						<div class='full'>
							<div class='option-icon'>
								<a class='dropzone-attach rotate--90' data-toggle='tooltip' data-placement='bottom' title='Attach File'><i class='fa fa-paperclip'></i></a>
							</div>

							<div class='form-btn'>					
								<button class='first btn btn-primary update-comment'>Save</button>
								<button class='cancel btn btn-secondary'>Cancel</button>
							</div>
						</div>
						
						<div class='full'>
							<div class='col-xs-12 col-sm-12 col-md-12 col-lg-10 modalfree dropzone-container update-dz'>
								<div class='modalfree-dropzone' data-linked='note_info' data-url='" . route('admin.file.upload') . "' data-removeurl='" . route('admin.file.remove') . "'></div>
								<div class='dz-preview-container'>" . $attached_files . "</div>
								" . $attached_file_inputs . "
							</div>
						</div>		
					</div>	
				</div>";

		return $html;		
	}

	public function getAttachfilesAttribute()
	{
		return $this->info->attachfiles;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function info()
	{
		return $this->belongsTo(NoteInfo::class, 'note_info_id');
	}

	public function attachfiles()
	{
		return $this->info->attachfiles;
	}

	// relation: morphTo
	public function linked()
	{
		return $this->morphTo();
	}
}