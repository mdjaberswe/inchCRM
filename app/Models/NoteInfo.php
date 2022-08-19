<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class NoteInfo extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'note_infos';
	protected $fillable = ['description', 'linked_id', 'linked_type'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;
	protected static $types = ['lead', 'contact', 'account', 'deal', 'project', 'task', 'event', 'estimate', 'invoice'];

	public static function validate($data)
	{
		$valid_types = implode(',', self::$types);
		$table = $data['related_type'] . 's';

		$rules = ["related_type"=> "required|in:$valid_types",
				  "related_id"	=> "required|exists:$table,id,deleted_at,NULL",
				  "note"		=> "required|max:65535",
				  "uploaded_files"	=> "array|max:10"];

		return \Validator::make($data, $rules);
	}

	public static function loadValidate($data)
	{
		$valid_types = implode(',', self::$types);
		$type = $data['type'];
		$type_id = $data['typeid'];
		$table = $type . 's';

		$rules = ["type"	=> "required|in:$valid_types",
				  "typeid"	=> "required|exists:$table,id,deleted_at,NULL",
				  "latestid"=> "required|exists:notes,id,linked_type,$type,linked_id,$type_id,deleted_at,NULL"];

		return \Validator::make($data, $rules);
	}

	public static function types()
	{
		return self::$types;
	}	

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getDescriptionHtmlAttribute()
	{
		$html = '';
		$at_count = substr_count($this->description, '@');

		if($at_count == 0) :
		    if(strlen($this->description) < 220) :
		    	return $this->description;
		    endif;	

		    $show_part = substr($this->description, 0, 220);
		    $hidden_part = "<span class='extend none'>" . substr($this->description, 220) . "</span>";
		    $show_more = "<a class='more'><span>...</span> more</a>";
		    $html = $show_part . $hidden_part . $show_more;
		else :
			$names = [];
			$at_pos = 0;
			$html = $this->description;

			for($i=0; $i < $at_count; $i++) :
				$at_pos = strpos($this->description, '@', $at_pos) + 1;
				$space_pos = strpos($this->description, ' ', $at_pos);
				if($space_pos) :
					$length = $space_pos - $at_pos;
					$fname = substr($this->description, $at_pos, $length);
				else :	
					$fname = substr($this->description, $at_pos);
				endif;				
				$names[] = $fname;
			endfor;			

			$at_whos = Staff::whereIn('first_name', $names)
							->orWhere(function($query) use ($names) { $query->whereIn('last_name', $names); })
							->get();

			if(strlen($html) > 220) :
				$at_who_names = $at_whos->pluck('name')->toArray();	
				$at_who_str = implode(' ', $at_who_names);	
				$at_who_array = explode(' ', $at_who_str);	

				$break_point = null;
				for($j=220; $j < strlen($html); $j++) :
					$word_start_pos = strpos($html, ' ', $j);					

					if($word_start_pos) :
						$word_start_pos++;
						$word_next_pos = strpos($html, ' ', $word_start_pos);
						if($word_next_pos) :
							$word_length = $word_next_pos - $word_start_pos;
							$word = substr($html, $word_start_pos, $word_length);
							$word = str_replace('@', '', $word);

							if(in_array($word, $at_who_array)) :
								$j = $word_next_pos;
							else :
								$break_point = $word_start_pos;
								break;
							endif;	
						else :
							break;	
						endif;
					else :
						break;		
					endif;
				endfor;	

				if($break_point != null) :
					$show_part = substr($html, 0, $break_point);
					$hidden_part = "<span class='extend none'>" . substr($html, $break_point) . "</span>";
					$show_more = "<a class='more'><span>...</span> more</a>";
					$html = $show_part . $hidden_part . $show_more;
				endif;	
			endif;	

			foreach($at_whos as $at_who) :
				$at_str = '@' . $at_who->name;
				$html = str_replace($at_str, $at_who->show_link, $html);
			endforeach;
		endif;    

		return $html;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function notes()
	{
		return $this->hasMany(Note::class, 'note_info_id');
	}

	// relation: morphTo
	public function linked()
	{
		return $this->morphTo();
	}

	// relation: morphMany
	public function attachfiles()
	{
		return $this->morphMany(AttachFile::class, 'linked');
	}
}