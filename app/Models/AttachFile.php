<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class AttachFile extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'attach_files';
	protected $fillable = ['name', 'format', 'size', 'location', 'linked_id', 'linked_type'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;
	protected static $linkedTypes = ['lead', 'contact', 'account', 'deal', 'project', 'task', 'event', 'estimate', 'invoice', 'note_info'];

	public static function validate($data)
	{
		$valid_linked_type = implode(',', self::$linkedTypes);
		$linked_table = $data['linked_type'] . 's';

		$rules = ["linked_id"		=> "required|exists:$linked_table,id,deleted_at,NULL",
				  "linked_type"		=> "required|in:$valid_linked_type",
				  "uploaded_files"	=> "required|array|max:10"];

		return \Validator::make($data, $rules);		  
	}

	public static function linkValidate($data)
	{
		$valid_linked_type = implode(',', self::$linkedTypes);
		$linked_table = $data['linked_type'] . 's';

		$rules = ["linked_id"		=> "required|exists:$linked_table,id,deleted_at,NULL",
				  "linked_type"		=> "required|in:$valid_linked_type",
				  "url"				=> "required|valid_domain"];

		return \Validator::make($data, $rules);		  
	}

	public static function uploadValidate($data)
	{
		$valid_linked = implode(',', self::$linkedTypes);

		$rules = ["file"	=> "required|file",
				  "linked"	=> "required|in:$valid_linked"];

		return \Validator::make($data, $rules);		  
	}

	public static function avatarValidate($data)
	{
		$valid_linked = implode(',', self::$linkedTypes);

		$rules = ['photo'	=> 'required|image|mimetypes:image/webp,image/jpeg,image/png,image/jpg,image/gif|max:3072',
				  'x'		=> 'required|integer',
				  'y'		=> 'required|integer',
				  'width'	=> 'required|integer',
				  'height'	=> 'required|integer',
				  'linked_type' => 'required|in:lead,contact,account,staff'];

		$error_messages = ['mimetypes' => ' The image must be a file of type: jpeg, png, gif, webp. '];

		return \Validator::make($data, $rules, $error_messages);	  
	}

	public static function removeValidate($data)
	{
		$valid_linked_type = implode(',', self::$linkedTypes);

		$rules = ["linked"			=> "required|in:$valid_linked_type",
				  "uploaded_files"	=> "required"];

		return \Validator::make($data, $rules);		  
	}

	public static function linkedTypes()
	{
		return self::$linkedTypes;
	}	

	public static function directoryRule($linked_type)
	{
		if(!is_null($linked_type) && in_array($linked_type, self::$linkedTypes)) :
			return ['location' => 'app.attachments', 'public' => 0];
		endif;

		return ['location' => 'uploads', 'public' => 1];
	}
	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameHtmlAttribute()
	{
		$tooltip = "";
		if(strlen($this->name) > 70) :
			$tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->name . "'";
		endif;	

		return "<a target='_blank' $tooltip class='" . $this->filelink_css . "' data-valid='" . $this->is_exist . "' href='" . $this->href . "'>" . $this->file_icon . str_limit($this->name, 70) . "</a>";
	}

	public function getThumbHtmlAttribute()
	{
		$html = "<div class='file-thumb'>";
		
		if($this->is_image) :
			$html .= "<div class='img-thumb'>";
			$html .= "<a target='_blank'  class='" . $this->filelink_css . ' ' . $this->tooltip_css . "' data-valid='" . $this->is_exist . "' href='" . $this->href . "'><img src='" . $this->image_thumb_src . "' data-toggle='tooltip' data-placement='top' title='" . $this->name . "'></a>";
			$html .= "</div>";
		else :
			$html .= "<div class='filelink-thumb'>";
			$tooltip = "";
			if(strlen($this->name) > 9) :
				$tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->name . "'";
			endif;
			$html .= "<a target='_blank' $tooltip class='" . $this->filelink_css . ' ' . $this->tooltip_css . "' data-valid='" . $this->is_exist . "' href='" . $this->href . "'>" . $this->file_icon . '<br>' . str_limit($this->name, 9) . "</a>";
			$html .= "</div>";
		endif;	

		$html .= "<div class='filethumb-bottom'>";
		$html .= "<a target='_blank'  class='" . $this->filelink_css . "' data-valid='" . $this->is_exist . "' href='" . $this->href . "'><i class='fa fa-eye'></i></a>";
		$html .= "<a class='download' data-valid='" . $this->is_exist . "' href='" . route('admin.file.show', [$this->id, $this->name, 'download']) . "'><i class='fa fa-download'></i></a>";
		$html .= "</div>";
		$html .= "</div>";

		return $html;
	}

	public function getImageThumbSrcAttribute()
	{
		if($this->is_image && $this->is_exist) :
			return (string)\Image::make($this->full_path)->resize(100, null, function ($constraint) 
				   {
    				$constraint->aspectRatio();
				   })->encode('data-url');
		endif;
		
		return null;	
	}

	public function getHrefAttribute()
	{
		if($this->is_link) :
			return domain_to_url($this->location);
		endif;
		
		return route('admin.file.show', [$this->id, $this->name]);
	}

	public function getFileIconAttribute()
	{
		if($this->is_link) :
			return "<i class='icon fa fa-link'></i>";
		endif;	

		return get_file_icon($this->format);
	}

	public function getIsLinkAttribute()
	{
		return (is_null($this->format) && is_null($this->size));
	}

	public function getIsImageAttribute()
	{
		$image_formats = ['webp', 'jpeg', 'jpg', 'png', 'gif'];
		
		if(!is_null($this->format)) :
			return in_array($this->format, $image_formats);
		endif;	

		return false;
	}

	public function getSizeHtmlAttribute()
	{
		if(is_null($this->size)) :
			return "<span class='shadow normal'>-</span>";
		endif;	

		return readable_filesize($this->size);
	}

	public function getPathAttribute()
	{
		if(!$this->is_link) :
			$directory = self::directoryRule($this->linked_type);
			$file_path = str_replace('.', '/', $directory['location']) . '/' . $this->location;
			return $file_path;
		endif;
		
		return $this->location;	
	}

	public function getFullPathAttribute()
	{
		if($this->is_link) :
			return $this->location;
		endif;
			
		return $this->public ? public_path($this->path) : storage_path($this->path);
	}

	public function getIsExistAttribute()
	{
		if($this->is_link) :
			return filter_var($this->location, FILTER_VALIDATE_URL) ? 1 : 0;
		endif;
			
		return file_exists($this->full_path) ? 1 : 0;
	}

	public function getPublicAttribute()
	{
		$directory = self::directoryRule($this->linked_type);
		return $directory['public'];
	}

	public function getFilelinkCssAttribute()
	{
		return $this->is_link ? null : 'filelink';
	}

	public function getTooltipCssAttribute()
	{
		return strlen($this->name) > 30  ? 'tooltip-lg' : 'tooltip-md';
	}

	public function getTypeNameAttribute()
	{
		return $this->is_link ? 'link' : 'file';
	}

	public function getCompactActionHtml($item, $edit_route = null, $delete_route, $action_permission = [], $common_modal = false)
	{
		$view = "<div class='inline-action'>
					<a target='_blank'  class='" . $this->filelink_css . "' data-valid='" . $this->is_exist . "' href='" . $this->href . "'><i class='fa fa-eye'></i></a>
				</div>";

		$email = "<li><a><i class='fa fa-send-o sm'></i> Send Email</a></li>";

		$download = null;

		if(!$this->is_link) :
			$download = "<li><a class='download' data-valid='" . $this->is_exist . "' href='" . route('admin.file.show', [$this->id, $this->name, 'download']) . "'><i class='fa fa-download'></i> Download</a></li>";
		endif;

		$delete = "<li>" .
					\Form::open(['route' => ['admin.file.destroy', $this->id], 'method' => 'delete']) .
						\Form::hidden('id', $this->id) .
						"<button type='submit' class='delete' data-item='" . $this->type_name . "'><i class='mdi mdi-delete'></i> Delete</button>" .
					\Form::close() .
			  	  "</li>";

		$dropdown_menu = $email . $download . $delete;	  	  

		$complete_dropdown_menu = "<ul class='dropdown-menu'>" . $dropdown_menu . "</ul>";

		$open = "<div class='action-box'>";

		$dropdown = "<div class='dropdown'>
						<a class='dropdown-toggle' data-toggle='dropdown'>
							<i class='fa fa-ellipsis-v'></i>
						</a>";

		$close = "</div></div>";

		$action = $open . $view . $dropdown . $complete_dropdown_menu . $close;

		return $action;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: morphTo
	public function linked()
	{
		return $this->morphTo();
	}
}