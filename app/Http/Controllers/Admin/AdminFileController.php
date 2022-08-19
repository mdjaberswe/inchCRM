<?php

namespace App\Http\Controllers\Admin;

use File;
use Image;
use Storage;
use App\Models\AttachFile;
use App\Jobs\SaveUploadedFile;
use App\Jobs\CleanRemovedFile;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminFileController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();
	}



	public function fileData(Request $request, $linked_type, $linked_id)
	{
		if($request->ajax()) :
			if(in_array($linked_type, AttachFile::linkedTypes())) :
				$model = morph_to_model($linked_type);
				$linked = $model::find($linked_id);

				if(isset($linked)) :
					$files = $linked->attachfiles()->latest('id')->get();
					return DatatablesManager::fileData($files, $request);
				endif;
			endif;
		endif;

		return null;
	}



	public function upload(Request $request)
	{
		if($request->ajax()) :
			$data = $request->all();
			$status = false;
			$file_name = null;
			$validation = AttachFile::uploadValidate($data);

			if($validation->passes()) :
				$status = true;
				$directory = AttachFile::directoryRule($request->linked);
				$upload_directory = str_replace('.', '/', $directory['location']);
				$upload_path = $directory['public'] ? public_path($upload_directory) : storage_path($upload_directory);

				if(!file_exists($upload_path)) :
					mkdir($upload_path, 0777, true);
				endif;

				$file = $request->file('file');
				$file_name = generate_uploaded_filename($file->getClientOriginalName());
				$file->move($upload_path, $file_name);
			endif;	

			return response()->json(['status' => $status, 'fileName' => $file_name]);
		endif;	
	}



	public function uploadAvatar(Request $request)
	{
		if($request->ajax()) :
			$data = $request->all();
			$status = false;
			$errors = null;
			$filename = null;
			$image = null;
			$image_type = $request->linked_type . $request->linked_id;
			$validation = AttachFile::avatarValidate($data);

			if($validation->passes()) :
				$status = true;
				$folder = $request->linked_type . 's';
				$upload_directory = "app/temp/";
				$upload_path = storage_path($upload_directory);

				if(!file_exists($upload_path)) :
					mkdir($upload_path, 0777, true);
				endif;

				$file = $request->file('photo');
				$filename = generate_uploaded_filename($file->getClientOriginalName());
				$save_path = storage_path($upload_directory . $filename);
				Image::make($file)->crop($request->width, $request->height, $request->x, $request->y)->fit(200, 200)->save($save_path);

				if(!empty($request->linked_id)) :
					$model = morph_to_model($request->linked_type);
					$linked = $model::find($request->linked_id);

					if(isset($linked)) :
						if(!is_null($linked->image)) :
							\Storage::disk('base')->delete($linked->image_path);
						endif;
						$image_path = storage_path("app/$folder/" . $filename);
						\File::move($save_path, $image_path);
						$save_path = $image_path;
						$linked->image = $filename;
						$linked->update();
					endif;	
				endif;

				$image = (string)Image::make($save_path)->encode('data-url');
				flush_response(['status' => $status, 'errors' => $errors, 'fileName' => $filename, 'modalImage' => $image, 'modalImageType' => $image_type]);
				clean_older_files($upload_directory);
			else :
				$errors = $validation->getMessageBag()->toArray();	
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors, 'fileName' => $filename, 'modalImage' => $image, 'modalImageType' => $image_type]);
		endif;	
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$data = $request->all();
			$status = true;
			$errors = [];

			$validation = AttachFile::validate($data);

			if($validation->passes()) :
				$directory = AttachFile::directoryRule($request->linked_type);
				$location = str_replace('.', '/', $directory['location']) . '/';
				dispatch(new SaveUploadedFile($request->uploaded_files, $request->linked_type, $request->linked_id, $directory, $location));
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function linkStore(Request $request)
	{
		if($request->ajax()) :
			$data = $request->all();
			$status = true;
			$errors = [];
			$validation = AttachFile::linkValidate($data);

			if($validation->passes()) :
				$url = domain_to_url($request->url);
				$name = get_url_title($url);

				$file = new AttachFile;
				$file->name = $name;
				$file->location = $request->url;
				$file->linked_id = $request->linked_id;
				$file->linked_type = $request->linked_type;
				$file->save();
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function show(Request $request, AttachFile $file, $filename, $download = null)
	{
		$status = true;
		if($file->name != $filename || !file_exists($file->full_path)) :
			$status = false;			
		endif;

		if($request->ajax()) :
			return response()->json(['status' => $status]);
		endif;

		if(!$status) :
			abort(404);
		endif;	

		$file_content = File::get($file->full_path);
		$file_type = File::mimeType($file->full_path);

		if(!is_null($download) && $download == 'download') :				
			return response()->download($file->full_path, $filename, ["Content-Type: $file_type"]);
		endif;

		$response = response()->make($file_content, 200);
		$response->header('Content-Type', $file_type);

		return $response;
	}



	public function remove(Request $request)
	{
		if($request->ajax()) :
			$data = $request->all();
			$status = false;
			$validation = AttachFile::removeValidate($data);

			if($validation->passes()) :
				$status = true;
				$directory = AttachFile::directoryRule($request->linked);
				$location = str_replace('.', '/', $directory['location']) . '/';
				dispatch(new CleanRemovedFile($request->uploaded_files, $directory, $location));
			endif;	

			return response()->json(['status' => $status]);
		endif;	
	}



	public function destroy(Request $request, AttachFile $file)
	{
		if($request->ajax()) :
			$status = true;

			if($file->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				if(!$file->is_link && $file->is_exist) :
					if($file->public) :
						File::delete($file->path);
					else :
						Storage::disk('base')->delete($file->path);
					endif;
				endif;	

				$file->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}
}