<?php

namespace App\Http\Controllers\Admin;

use App\Models\Note;
use App\Models\NoteInfo;
use App\Models\AttachFile;
use App\Jobs\SaveUploadedFile;
use App\Jobs\CleanRemovedFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminNoteController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();
		$this->setUploadDirectoryLocation('note_info');
	}



	public function getData(Request $request, $type)
	{
		if($request->ajax()) :
			$data = $request->all();
			$html = null;
			$status = false;
			$errors = [];

			if(isset($request->type) && $request->type == $type && in_array($request->type, NoteInfo::types())) :
				$validation = NoteInfo::loadValidate($data);

				if($validation->passes()) :
					$model = morph_to_model($request->type);
					$parent = $model::find($request->typeid);
					$html = $parent->getNotesHtmlAttribute($request->latestid, true);
					$status = true;
				else :
					$messages = $validation->getMessageBag()->toArray();
					foreach($messages as $msg) :
						$errors[] = $msg;
					endforeach;	
				endif;	
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'html' => $html]);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$data = $request->all();
			$html = null;
			$status = false;
			$errors = [];

			if(isset($request->related_type) && in_array($request->related_type, NoteInfo::types())) :
				$validation = NoteInfo::validate($data);

				if($validation->passes()) :
					$note_info = new NoteInfo;
					$note_info->description = $request->note;
					$note_info->linked_id = $request->related_id;
					$note_info->linked_type = $request->related_type;
					$note_info->save();

					$note = new Note;
					$note->note_info_id = $note_info->id;
					$note->linked_id = $request->related_id;
					$note->linked_type = $request->related_type;
					$note->save();

					dispatch(new SaveUploadedFile($request->uploaded_files, 'note_info', $note_info->id, $this->directory, $this->location));

					$html = $note->getNoteHtmlAttribute(true, true);
					$status = true;
				else :
					$messages = $validation->getMessageBag()->toArray();
					foreach($messages as $msg) :
						$errors[] = $msg;
					endforeach;	
				endif;	
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'html' => $html]);
		endif;
	}



	public function edit(Request $request, Note $note)
	{
		if($request->ajax()) :
			$status = true;
			$html = null;

			if(isset($note) && isset($request->id) && $note->id == $request->id) :
				$html = $note->edit_form;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'html' => $html]);
		endif;
	}



	public function update(Request $request, Note $note)
	{
		if($request->ajax()) :
			$data = $request->all();
			$html = null;
			$location = null;
			$status = false;
			$errors = [];

			if(isset($note) && isset($request->id) && $note->id == $request->id) :
				$validation = Note::validate($data);

				if($validation->passes()) :
					$note_info = $note->info;
					$note_info->description = $request->note;
					$note_info->save();
					$note_info->attachfiles()->delete();

					dispatch(new SaveUploadedFile($request->uploaded_files, 'note_info', $note_info->id, $this->directory, $this->location));
					dispatch(new CleanRemovedFile($request->removed_files, $this->directory, $this->location));

					if($note->pin == 1) :
						$html = $note->getNoteHtmlAttribute(null, null, true);
						$location = 0;
					else :
						$prev_location = Note::wherePin(0)
											 ->whereLinked_type($note->linked_type)
											 ->whereLinked_id($note->linked_id)
											 ->where('id', '>', $note->id)
											 ->orderBy('id')
											 ->first();

						$top = !isset($prev_location) ? true : null;
						$html = $note->getNoteHtmlAttribute($top);		
						$location = $note->id;	 
					endif;

					$status = true;
				else :
					$messages = $validation->getMessageBag()->toArray();
					foreach($messages as $msg) :
						$errors[] = $msg;
					endforeach;	
				endif;	
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'html' => $html, 'location' => $location, 'saveId' => $request->id]);
		endif;
	}



	public function pin(Request $request, Note $note)
	{
		if($request->ajax()) :
			$status = true;
			$pin_html = null;
			$pin_location = null;
			$unpin_html = null;
			$unpin_location = null;
			$prev_location = null;
			$count = null;
			$pin = (bool)$request->pin;


			if(!is_bool($pin)) :
				$status = false;
			endif;

			if($status == true) :
				if($pin) :
					$old_pin = Note::whereLinked_type($note->linked_type)->whereLinked_id($note->linked_id)->wherePin(1)->first();
					Note::whereLinked_type($note->linked_type)->whereLinked_id($note->linked_id)->update(['pin' => 0]);
					$unpin = isset($old_pin) ? Note::find($old_pin->id) : null;

					$note->pin = 1;
					$note->update();
					$pin_html = $note->getNoteHtmlAttribute(null, null, true);	
					$pin_location = $note->id;
				else :
					$note->pin = 0;
					$note->update();
					$unpin_location = $note->id;
					$unpin = $note;
				endif;	

				$count = Note::whereLinked_type($note->linked_type)->whereLinked_id($note->linked_id)->wherePin(0)->count();

				if(isset($unpin)) :
					$prev_location = Note::wherePin(0)
										 ->whereLinked_type($unpin->linked_type)
										 ->whereLinked_id($unpin->linked_id)
										 ->where('id', '>', $unpin->id)
										 ->orderBy('id')
										 ->first();

					$unpin_top_status = !isset($prev_location) ? true : null;
					$unpin_html = $unpin->getNoteHtmlAttribute($unpin_top_status);
					$prev_location = isset($prev_location) ? $prev_location->id : 0; 
				endif;	
			endif;	
			
			return response()->json(['status' => $status, 'pinHtml' => $pin_html, 'pinLocation' => $pin_location, 'timelineInfoCount' => $count, 'unpinHtml' => $unpin_html, 'unpinLocation' => $unpin_location, 'prevLocation' => $prev_location]);
		endif;
	}	



	public function destroy(Request $request, Note $note)
	{
		if($request->ajax()) :
			$status = true;
			$count = null;

			if($note->id != $request->note_id) :
				$status = false;
			endif;

			if($status == true) :
				$linked_type = $note->linked_type;
				$linked_id = $note->linked_id;

				foreach($note->attachfiles as $file) :
					if($file->public) :
						\File::delete($file->path);
					else :
						\Storage::disk('base')->delete($file->path);
					endif;
				endforeach;

				$note->info->attachfiles()->delete();
				$note->info()->delete();
				Note::where('note_info_id', $note->note_info_id)->delete();
				$count = Note::whereLinked_type($linked_type)->whereLinked_id($linked_id)->wherePin(0)->count();
			endif;	
			
			return response()->json(['status' => $status, 'timelineInfoId' => $request->note_id, 'timelineInfoCount' => $count]);
		endif;	
	}
}