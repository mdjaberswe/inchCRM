<?php

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Setting;
use App\Models\Country;
use App\Models\Notification;
use App\Models\NotificationInfo;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

if(!function_exists('pp'))
{
	function pp($string)
	{
		echo '<pre>';
		print_r($string);
		die();
	}
}

if(!function_exists('not_null_empty'))
{
	function not_null_empty($val)
	{
		return (!is_null($val) && !empty($val));
	}
}

if(!function_exists('get_layout_status'))
{
	function get_layout_status()
	{
		$outcome = [];
		$outcome['logo'] = '';
		$outcome['top_nav'] = '';
		$outcome['nav'] = '';
		$outcome['main'] = '';

		if(\Session::has('is_compress') && \Session::get('is_compress') == true) :
			$outcome['logo'] = 'compress';
			$outcome['top_nav'] = 'expand';
			$outcome['nav'] = 'compress';
			$outcome['main'] = 'expand';
		endif;

		return $outcome;
	}
}

if(!function_exists('active_menu'))
{
	function active_menu($identifier)
	{		
		$current_route = \Route::getCurrentRoute()->getName();

		$outcome = null;
		if(is_array($identifier)) :
			$except = $identifier['except'];
			$common = $identifier['common'];

			if($except !== $current_route) :
				if(strpos($current_route, $common) !== false) :
					$outcome = 'active';
				endif;	
			endif;	
		else :
			$identifier_array = explode('|', $identifier);

			foreach($identifier_array as $single_identifier) :
				if(strpos($current_route, $single_identifier) !== false) :
					$outcome = 'active';
				endif;
			endforeach;	
		endif;		

		return $outcome;
	}
}

if(!function_exists('active_menu_arrow'))
{
	function active_menu_arrow($identifier)
	{		
		$current_route = \Route::getCurrentRoute()->getName();

		$outcome = null;
		if(strpos($current_route, $identifier) !== false) :
			$outcome = 'down';
		endif;

		return $outcome;
	}
}

if(!function_exists('active_tree'))
{
	function active_tree($identifier, $nav_status)
	{
		if($nav_status == 'compress') :
			return null;
		endif;
			
		$current_route = \Route::getCurrentRoute()->getName();

		$outcome = null;
		if(strpos($current_route, $identifier) !== false) :
			$outcome = "style='display: block;'";
		endif;

		return $outcome;
	}
}

if(!function_exists('breadcrumbs_render'))
{
	function breadcrumbs_render($str_breadcrumbs)
	{
		$breadcrumbs = explode('|', $str_breadcrumbs);

		$items_bread = count($breadcrumbs);
		$current_bread = 0;
		$render = "<ol class='breadcrumb'>";

		foreach($breadcrumbs as $breadcrumb) :
		    $pos = strpos($breadcrumb, ':');
		    $route_params = [];

		    if($pos !== false) :
		        $text = substr($breadcrumb, $pos+1);
		        $route = substr($breadcrumb, 0, $pos);

		        $route_has_param = strpos($route, ',');
		        if($route_has_param !== false) :
		        	$route_params[] = substr($route, $route_has_param+1);
		        	$route = substr($route, 0, $route_has_param);
		        endif;	
		    else :
		        $text = $breadcrumb;
		    endif; 

		    if(++$current_bread == $items_bread) :
		        $render .= "<li class='active'>" . $text . "</li>";
		    else :
		        $render .= '<li>' . link_to_route($route, $text, $route_params) . '</li>';
		    endif;
		endforeach;

		$render .= '</ol>';

		return $render;
	}
}

if(!function_exists('option_attr_render'))
{
	function option_attr_render($opt_array, $default = null)
	{
		$html = '';

		foreach($opt_array as $key => $display) :
			$selected = (!is_null($default) && $key == $default) ? 'selected' : '';

		    if(is_array($display)) :
		        $html .= "<option value='" . $key . "' for='" . $display[1] . "' $selected>" . $display[0] . "</option>";
		    else :
		        $html .= "<option value='" . $key . "' $selected>" . $display . "</option>";
		   endif;
		endforeach;

		return $html;
	}
}	

if(!function_exists('get_field_conditions_list'))
{
	function get_field_conditions_list($none = true)
	{
		$condition_list['string'] = ['equal' => ['is equal to', 'string'], 'not_equal' => ['not equal to', 'string'], 'contain' => ['contains', 'string'], 'not_contain' => ['does not contain', 'string'], 'empty' => 'is empty', 'not_empty' => 'is not empty'];
		$condition_list['dropdown'] = ['equal' => ['is equal to', 'dropdown'], 'not_equal' => ['not equal to', 'dropdown'], 'empty' => 'is empty', 'not_empty' => 'is not empty'];
		$condition_list['numeric'] = ['equal' => ['= is equal to', 'numeric'], 'not_equal' => ['!= not equal to', 'numeric'], 'less' => ['< is less than', 'numeric'], 'greater' => ['> is greater than', 'numeric']];
		$condition_list['date'] = ['before' => ['is before', 'days'], 'after' => ['is after', 'days'], 'last' => ['in the last', 'days'], 'next' => ['in the next', 'days'], 'empty' => 'is empty', 'not_empty' => 'is not empty'];
		$condition_list['email'] = ['0' => 'No time limit', '7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days'];
	
		if($none) :
			$condition_list['string'] = array_merge(['' => '-None-'], $condition_list['string']);
			$condition_list['dropdown'] = array_merge(['' => '-None-'], $condition_list['dropdown']);
			$condition_list['numeric'] = array_merge(['' => '-None-'], $condition_list['numeric']);
			$condition_list['date'] = array_merge(['' => '-None-'], $condition_list['date']);
			$condition_list['email'] = array_merge(['' => '-None-'], $condition_list['email']);
		endif;

		return $condition_list;
	}
}	

if(!function_exists('conditional_filter_query'))
{
	function conditional_filter_query($query, $attribute, $condition, $conditional_value)
	{
		switch($condition) :
			case 'equal' :
				$conditional_value = (array)$conditional_value;
				return $query->whereIn($attribute, $conditional_value);
			break;

			case 'not_equal' :
				$conditional_value = (array)$conditional_value;
				return $query->whereNotIn($attribute, $conditional_value);
			break;

			case 'contain' :
				return $query->where($attribute, 'LIKE', '%' . $conditional_value . '%');
			break;

			case 'not_contain' :
				return $query->where($attribute, 'NOT LIKE', '%' . $conditional_value . '%');
			break;

			case 'empty' :
				return $query->where($attribute, '')->orWhereNull($attribute);
			break;

			case 'not_empty' :
				return $query->where($attribute, '!=', '')->whereNotNull($attribute);
			break;

			case 'less' :
				return $query->where($attribute, '<', $conditional_value);
			break;

			case 'greater' :
				return $query->where($attribute, '>', $conditional_value);
			break;

			case 'before' :
				$before_date = date("Y-m-d H:i:s", strtotime("-$conditional_value days"));
				return $query->where($attribute, '<', $before_date);
			break;

			case 'after' :
				$after_date = date("Y-m-d H:i:s", strtotime("+$conditional_value days"));
				return $query->where($attribute, '>', $after_date);
			break;

			case 'last' :
				$today = date("Y-m-d H:i:s");
				$last_date = date("Y-m-d H:i:s", strtotime("-$conditional_value days"));
				return $query->where($attribute, '<=', $today)->where($attribute, '>=', $last_date);
			break;

			case 'next' :
				$today = date("Y-m-d H:i:s");
				$next_date = date("Y-m-d H:i:s", strtotime("+$conditional_value days"));
				return $query->where($attribute, '>=', $today)->where($attribute, '<=', $next_date);
			break;

			default : return $query;
		endswitch;	
	}
}		

if(!function_exists('route_has'))
{
	function route_has($route)
	{
		return \Route::has($route);
	}
}

if(!function_exists('global_placeholder'))
{
	function global_placeholder()
	{
		return asset('img/placeholder.png');
	}
}

if(!function_exists('chart_color_serialize'))
{
	function chart_color_serialize()
	{
		$rgba = [
			[255, 135, 135, 1],
			[255, 171, 215, 1],
			[165, 146, 247, 1],
			[69, 176, 247, 1],
			[35, 236, 255, 1],
			[81, 253, 199, 1],
			[124, 237, 138, 1],
			[198, 237, 124, 1],
			[255, 152, 126, 1],
			[255, 183, 116, 1],
			[255, 255, 137, 1],
			[255, 245, 206, 1],
			[255, 115, 0, 1],
			[255, 175, 0, 1],
			[255, 236, 0, 1],
			[213, 243, 11, 1],
			[82, 215, 38, 1],
			[27, 170, 47, 1],
			[45, 203, 117, 1],
			[38, 215, 174, 1],
			[124, 221, 221, 1],
			[95, 183, 212, 1],
			[151, 217, 255, 1],
			[0, 126, 214, 1],
			[131, 153, 235, 1],
			[142, 108, 239, 1],
			[156, 70, 208, 1],
			[199, 88, 208, 1],
			[224, 30, 132, 1]	
		];

		return $rgba;
	}
}		

if(!function_exists('generate_rgba_color'))
{
	function generate_rgba_color($nth)
	{
		$chart_color = chart_color_serialize();
		$total_chart_color = count($chart_color);

		if($nth <= ($total_chart_color - 1)) :
			$color = $chart_color[$nth];
		else :
			$remainder = ($nth + 1) % $total_chart_color;
			$color = $chart_color[$remainder];
		endif;	

		$rgba = "rgba($color[0], $color[1], $color[2], $color[3])";

		return $rgba;		
	}
}

if(!function_exists('ignore_ob_start'))
{
	function ignore_ob_start()
	{
		ignore_user_abort(true);
		set_time_limit(0);
		ob_start();
	}
}

if(!function_exists('response_continue'))
{
	function response_and_continue($response = [])
	{
		echo json_encode($response);
		header('Connection: close');
		header('Content-Length: '.ob_get_length());
		ob_end_flush();
		ob_flush();
		flush();
	}
}	

if(!function_exists('flush_response'))
{
	function flush_response($response = [])
	{
		ignore_user_abort(true);
		set_time_limit(0);
		ob_start();
		echo json_encode($response);
		header('Connection: close');
		header('Content-Length: '.ob_get_length());
		ob_end_flush();
		ob_flush();
		flush();
	}
}

if(!function_exists('valid_url_or_domain'))
{
	function valid_url_or_domain($url)
	{
	    $url_info = parse_url(filter_var($url, FILTER_SANITIZE_URL));

	    $valid_url = domain_to_url($url);	            
        if(filter_var($valid_url, FILTER_VALIDATE_URL) !== false) :
            return true;
        endif;

        if(filter_var(gethostbyname($url), FILTER_VALIDATE_IP)) :
            return true;
        elseif(array_key_exists('host', $url_info) && filter_var(gethostbyname($url_info['host']), FILTER_VALIDATE_IP)) :
        	return true;
        elseif(array_key_exists('path', $url_info) && filter_var(gethostbyname($url_info['path']), FILTER_VALIDATE_IP)) :
        	return true;
        endif;

	    return false;
	}
}	

if(!function_exists('file_content_exists'))
{
	function file_content_exists($url)
	{
		$file_headers = get_headers($url);
		return !(strpos($file_headers[0], '404') !== false);
	}
}		

if(!function_exists('get_url_title'))
{
	function get_url_title($url)
	{
		$contents = @file_get_contents($url) or '';

		if(strlen($contents)) :
			$contents = trim(preg_replace('/\s+/', ' ', $contents));
			preg_match("/\<title\>(.*)\<\/title\>/i", $contents, $title);
			return $title[1];
		endif;

		return url_to_domain($url);
	}
}

if(!function_exists('quick_url'))
{
	function quick_url($url)
	{
		$http = substr($url, 0, 4);		
		if($http != 'http') :
			$url = 'http://' . $url;
		endif;

		return $url;
	}
}		

if(!function_exists('domain_to_url'))
{
	function domain_to_url($domain, $force_http = false)
	{
		if(filter_var($domain, FILTER_VALIDATE_URL) !== false) :
		    return $domain;
		endif;

		$url_info = parse_url(filter_var($domain, FILTER_SANITIZE_URL));

		if(!isset($url_info['host'])) :
		    $url_info['host'] = $url_info['path'];
		endif;

		if($url_info['host']!='') :
			if (!isset($url_info['scheme'])) :
		        $url_info['scheme'] = 'http';
		    endif;

		    if((checkdnsrr($url_info['host'], 'A') && in_array($url_info['scheme'],array('http','https')) && ip2long($url_info['host']) === FALSE) || $force_http == true) :
		        $url_info['host'] = preg_replace('/^www\./', '', $url_info['host']);
		        $url = $url_info['scheme'].'://'.$url_info['host']. "/";
		        return $url;        
		    endif;
		endif;   

		return $domain;
	}
}		

if(!function_exists('url_to_domain'))
{
	function url_to_domain($url)
	{
		$url = trim($url, '/');
		if(!preg_match('#^http(s)?://#', $url)) :
		    $url = 'http://' . $url;
		endif;

		$url_info = parse_url($url);
		$domain = preg_replace('/^www\./', '', $url_info['host']);
		return $domain;
	}
}	

if(!function_exists('no_space'))
{
	function no_space($str)
	{
		return str_replace(' ', '&nbsp;', $str);
	}
}	

if(!function_exists('clean_older_files'))
{
	function clean_older_files($directory, $days = 1)
	{
		$path = storage_path($directory);

		if(file_exists($path)) :
			$files = \Storage::disk('base')->files($directory);
			$now = time();
			$before_limit = 60 * 60 * 24 * $days;

			foreach ($files as $file) :
				$last_modified_time = \Storage::disk('base')->lastModified($file);
				$file_age = $now - $last_modified_time;			
				if($file_age >= $before_limit) :
					\Storage::disk('base')->delete($file);
				endif;
			endforeach;

			return true;
		endif;
		
		return false;	
	}
}	

if(!function_exists('unlink_file'))
{
	function unlink_file($file_path, $public)
	{
		if($public) :
		    \File::delete($file_path);
		else :
		    \Storage::disk('base')->delete($file_path);
		endif;
	}
}		

if(!function_exists('table_json_columns'))
{
	function table_json_columns($columns, $hide_columns = [])
	{
		$json_columns = '';

		foreach($columns as $index => $column) :
			if(is_array($column) && !in_array($index, $hide_columns) ||  !is_array($column) && !in_array($column, $hide_columns)) :
				if(is_array($column)) :
					$column_parameter = [];
					$column_parameter['data'] = $index;
					foreach($column as $addition_parameter => $value) :
						$column_parameter[$addition_parameter] = $value;
					endforeach;
				else :	
					$column_parameter = ['data' => $column];
				endif;

				$json_columns .= json_encode($column_parameter) . ',';	
			endif;	
		endforeach;

		return $json_columns;
	}
}

if(!function_exists('permit_json_columns'))
{
	function permit_json_columns($columns, $permission, $only_bulk_del = false)
	{
		$permit_checkbox = permit_checkbox($permission, $only_bulk_del);
		$permit_action = permit_action($permission);

		if($permit_checkbox) :
			$columns = array_prepend($columns, 'checkbox');
		endif;

		if($permit_action) :
			array_push($columns, 'action');
		endif;

		$outcome = table_json_columns($columns);

		return $outcome;
	}
}	

if(!function_exists('permit_checkbox'))
{
	function permit_checkbox($permission, $only_bulk_del = false)
	{
		$edit_permission = $permission . '.edit';
		$delete_permission = $permission . '.delete';

		if(permit($edit_permission) || permit($delete_permission)) :
			if(!$only_bulk_del || ($only_bulk_del && permit($delete_permission))) :
				return true;
			endif;			
		endif;

		return false;
	}
}

if(!function_exists('permit_action'))
{
	function permit_action($permission)
	{
		$edit_permission = $permission . '.edit';
		$delete_permission = $permission . '.delete';

		if(permit($edit_permission) || permit($delete_permission)) :
			return true;
		endif;

		return false;
	}
}

if(!function_exists('get_data_from_attribute'))
{
	function get_data_from_attribute($data)
	{
		if(isset($data) && $data != '') :
			$outcome = [];
			$data = explode('|', $data);
			foreach($data as $single_data) :
				$key_val = explode(':', $single_data);
				$key = $key_val[0];
				$value = $key_val[1];
				$outcome[$key] = $value;
			endforeach;	

			return $outcome;
		endif;

		return null;
	}
}

if(!function_exists('table_showhide_columns'))
{
	function table_showhide_columns($table)
	{
		$json_columns = '';

		if(!isset($table['checkbox']) || (isset($table['checkbox']) && $table['checkbox'] == true)) :
			$json_columns .= json_encode(['text' => 'CHECKBOX', 'className' => 'show-hide']) . ',';
		endif;

		foreach($table['thead'] as $thead) :
			if(is_array($thead)) :
				$json_columns .= json_encode(['text' => $thead[0], 'className' => 'show-hide']) . ',';
			else :
				$json_columns .= json_encode(['text' => $thead, 'className' => 'show-hide']) . ',';
			endif;			
		endforeach;	 

		if(!isset($table['action']) || (isset($table['action']) && $table['action'] == true)) :   			
			$json_columns .= json_encode(['text' => 'ACTION', 'className' => 'show-hide']);
		endif;	

		return $json_columns;
	}
}	

if(!function_exists('table_filter_html'))
{
	function table_filter_html($filter_input, $item, $white = false)
	{
		$filter_html = '';
		foreach($filter_input as $input_name => $input) :

			if($input['type'] == 'dropdown') :
				$class = isset($input['no_search']) ? 'select-type-single-b' : 'select-type-single';
				$class = $white ? 'white-' . $class : $class;
				$filter_html .= "<select name='" . $input_name . "' id='" . strtolower($item) . '-' . $input_name . "' class='" . $class . "'>";
				foreach($input['options'] as $key => $display) :
					$filter_html .= "<option value='" . $key . "'>" . $display . "</option>";
				endforeach;	

				$filter_html .= "</select>";
			endif;						
		endforeach;

		return $filter_html;
	}
}	

if(!function_exists('number_options_html'))
{
	function number_options_html($min = 0, $max = 100, $step = 10, $default = null)
	{
		$options = '';

		while($min <= $max) :
			$selected = (!is_null($default) && $min == $default) ? 'selected' : '';
			$options .= "<option value='" . $min . "' $selected>" . $min . "</option>";
			$min = $min + $step;
		endwhile;

		return $options;	
	}
}		

if(!function_exists('route_path'))
{
	function route_path($route_url)
	{
		$base_url = url('/');
		$route_path = str_replace($base_url, '', $route_url);

		return $route_path;
	}
}	

if(!function_exists('permit'))
{
	function permit($permission)
	{
		return \Auth::user()->can($permission);
	}
}		

if(!function_exists('auth_staff'))
{
	function auth_staff()
	{
		if(auth()->check() && auth()->user()->linked_type == 'staff' && isset(auth()->user()->linked)) :
			return auth()->user()->linked;
		endif;
		
		return null;
	}
}

if(!function_exists('auth_contact'))
{
	function auth_contact()
	{
		if(auth()->check() && auth()->user()->linked_type == 'contact' && isset(auth()->user()->linked)) :
			return auth()->user()->linked;
		endif;
		
		return null;
	}
}

if(!function_exists('auth_linked'))
{
	function auth_linked()
	{
		if(auth()->check() && isset(auth()->user()->linked)) :
			return auth()->user()->linked;
		endif;
		
		return null;
	}
}

if(!function_exists('morph_to_model'))
{
	function morph_to_model($morph)
	{
		$model = str_replace('_', ' ', $morph);
		$model = ucwords($model);		
		$model = "\App\Models\ $model";
		$model = str_replace(' ', '', $model);

		return $model;
	}
}	

if(!function_exists('collection_paginator'))
{
	function collection_paginator($items, $base_route, $perPage = 15, $page = null, $options = [])
	{
		$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
		$items = $items instanceof Collection ? $items : Collection::make($items);
		$paginate_items = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
		$paginate_items->setPath(route($base_route));

		return $paginate_items;
	}
}

if(!function_exists('table_config_set'))
{
	function table_config_set($table_name)
	{
		if(\Schema::hasTable($table_name)) :
			$morph = substr($table_name, 0, -1);
		    config()->set($morph, morph_to_model($morph)::pluck('value', 'key')->all());
		endif;
	}
}

if(!function_exists('rename_array_key'))
{
	function rename_array_key($array, $keys)
	{
		$json = json_encode($array);

		foreach($keys as $current_key => $new_key) :
			$json = str_replace('"' . $current_key . '":', '"' . $new_key . '":', $json);
		endforeach;
			
		return json_decode($json, true); 
	}
}

if(!function_exists('array_parent_search'))
{
	function array_parent_search($arr, $element)
	{
		foreach($arr as $key => $level_arr) :
			if(in_array($element, $level_arr)) :
				return $key;
			endif;	
		endforeach;	

		return null;
	}
}

if(!function_exists('array_forget_all'))
{
	function array_forget_all($array, $forget_keys)
	{
		$keys = array_keys($array);

		foreach($forget_keys as $forget_key) :
			foreach($keys as $key) :
				array_forget($array, "$key.$forget_key");
			endforeach;
		endforeach;	
		
		return $array;
	}
}		

if(!function_exists('map_auto_select'))
{
	function map_auto_select($heading, $column, $fields, $lower_fields)
	{
		if(array_key_exists($heading, $fields)) :
			return $heading;

		elseif(in_array($heading, $fields)) :
			return array_search($heading, $fields);

		elseif(array_key_exists($column, $fields)) :
			return $column;

		elseif(in_array($column, $fields)) :
			return array_search($column, $fields);

		elseif(in_array(strtolower($heading), $lower_fields)) :
			return array_search(strtolower($heading), $lower_fields);

		elseif(in_array(strtolower($column), $lower_fields)) :
			return array_search(strtolower($column), $lower_fields);

		else :
			foreach($lower_fields as $key => $field) :
				$heading_semilarity = similar_text(strtolower($heading), $field, $perc_head);
				if(round($perc_head, 2) > 70) :
					return $key;
				endif;	

				$column_semilarity = similar_text(strtolower($column), $field, $perc_col);
				if(round($perc_col, 2) > 70) :
					return $key;
				endif;

				$field_words = explode(' ', $field);

				$heading_words = explode(' ', $heading);				
				$heading_match_words = array_intersect($heading_words, $field_words);
				$heading_match = (count($heading_match_words) / count($field_words)) * 100;

				if(round($heading_match, 2) > 55) :
					return $key;
				elseif(round($heading_match, 2) > 50 && (end($heading_match_words) == end($field_words) || array_first($heading_match_words) == array_first($field_words))) :
					return $key;	
				elseif(round($heading_match, 2) >= 30 &&  count($heading_words) == count($heading_match_words)) :
					return $key;	
				endif;

				$column_words = explode('_', $column);
				$column_match_words = array_intersect($column_words, $field_words);
				$column_match = (count($column_match_words) / count($field_words)) * 100;

				if(round($column_match, 2) > 55) :
					return $key;
				elseif(round($column_match, 2) > 50 && (end($column_match_words) == end($field_words) || array_first($column_match_words) == array_first($field_words))) :
					return $key;	
				elseif(round($column_match, 2) >= 30 && count($column_words) == count($column_match_words)) :
					return $key;	
				endif;
			endforeach;	

			return null;
		endif;
	}
}

if(!function_exists('render_map_row'))
{
	function render_map_row($heading, $column, $fields, $auto_select = null)
	{
		$tr = '<tr>';
		$tr .= '<td>' . $heading . '</td>'; 
		$tr .= '<td>' . \Form::select($column, $fields, $auto_select, ['class' => 'form-control white-select-single-clear', 'data-placeholder' => 'Choose a field']) . '</td>';
		$tr .= '</tr>';

		return $tr;
	}
}

if(!function_exists('days_between_dates'))
{
	function days_between_dates($start_date, $end_date)
	{
		$start_time = strtotime($start_date);
		$end_time = strtotime($end_date);
		$time_diff = $end_time - $start_time;
		$day_diff = (int)floor($time_diff / (60 * 60 * 24));
		return $day_diff;
	}
}

if(!function_exists('string_number_convert'))
{
	function string_number_convert($value)
	{
		$value = (int)$value == (float)$value ? (int)$value : number_format($value, 2, '.', '');
		return $value;
	}
}

if(!function_exists('readable_date'))
{
	function readable_date($date)
	{
		if(not_null_empty($date)) :
			return Carbon::createFromFormat('Y-m-d', $date)->format('M j, Y');
		endif;
		
		return null;	
	}
}

if(!function_exists('readable_date_html'))
{
	function readable_date_html($date, $time = false)
	{
		$html = '';

		$readable_date = is_object($date) ? $date->format('M j, Y') : date('M j, Y', strtotime($date));
		$html .= "<span>" . $readable_date . "</span>";
		if($time == true) :
			$readable_time = is_object($date) ? $date->format('h:i a') : date('h:i a', strtotime($date));
			$html .= '&nbsp;';
			$html .= "<span class='shadow'>" . $readable_time . "</span>";
		endif;

		return $html;
	}
}

if(!function_exists('readable_full_date'))
{
	function readable_full_date($date)
	{
		return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('M j, Y');
	}
}

if(!function_exists('ampm_to_sql_datetime'))
{
	function ampm_to_sql_datetime($ampm)
	{
		$divider = strpos($ampm, ' ');
		$date = substr($ampm, 0, $divider);
		$time = substr($ampm, $divider+1);
		$strtotime = strtotime($time);
		$sql_time = date('G:i:s', $strtotime);
		$outcome = $date . ' ' . $sql_time;

		return $outcome;
	}
}

if(!function_exists('before_date'))
{
	function before_date($date, $before, $before_type)
	{
		$substract = 'sub' . ucfirst($before_type) . 's';
		return Carbon::createFromFormat('Y-m-d H:i:s', $date)->$substract($before)->format('Y-m-d H:i:s');
	}
}

if(!function_exists('after_date'))
{
	function after_date($date, $after, $after_type)
	{
		$add = 'add' . ucfirst($after_type) . 's';
		return Carbon::createFromFormat('Y-m-d H:i:s', $date)->$add($after)->format('Y-m-d H:i:s');
	}
}

if(!function_exists('time_short_form'))
{
	function time_short_form($diffForHumans)
	{
		$diffForHumans = str_replace(['seconds', 'second'], 'sec', $diffForHumans);
		$diffForHumans = str_replace(['minutes', 'minute'], 'min', $diffForHumans);
		$diffForHumans = str_replace('hour', 'hr', $diffForHumans);
		$diffForHumans = str_replace('hours', 'hrs', $diffForHumans);

		if(strpos($diffForHumans, 'from now') !== FALSE) :
			$diffForHumans = 'in ' . str_replace('from now', '', $diffForHumans);
		endif;	

		return $diffForHumans;
	}
}

if(!function_exists('strpos_array'))
{
	function strpos_array($arr, $sentence)
	{
	    $arr = !is_array($arr) ? array($arr) : $arr;

	    foreach($arr as $word) :
	        if(strpos($sentence, $word) !==false) :
	        	return true;
	        endif;	
	    endforeach;

	    return false;
	}
}

if(!function_exists('file_modified_at'))
{
	function file_modified_at($file_path)
	{
		if(file_exists($file_path)) :
			return date('Y-m-d H:i:s', filemtime($file_path));
		endif;

		return null;
	}
}

if(!function_exists('has_internet_connection'))
{
	function has_internet_connection()
	{
		$connected = @fsockopen('www.google.com', 80, $errno, $errstr, 30); 

	    if($connected) :
	        $has_connection = true;
	        fclose($connected);
	    else :
	        $has_connection = false;
	    endif;

	    return $has_connection;
	}
}

if(!function_exists('time_zones_list'))
{
	function time_zones_list()
	{
		$time_zones = timezone_identifiers_list();
		$time_zones_list = [];
		
		foreach($time_zones as $key => $timezone) :
		    date_default_timezone_set($timezone);

		    $timezone_apart = explode('/', $timezone);
		    $city = end($timezone_apart);
		    $city = str_replace('_', ' ', $city);
		    $diff_gmt = date('P', time());

		    $time_zones_list[$key]['timezone_val'] = $timezone;
		    $time_zones_list[$key]['timezone_display'] = '(GMT'. $diff_gmt .') '. trim($city);
		endforeach;

		date_default_timezone_set(config('app.timezone'));

		$time_zones_list = collect($time_zones_list);

		$minus_time_zones = $time_zones_list->filter(function($value, $key)
											  {
											  	return (strpos($value['timezone_display'], 'GMT-') !== false);
											  })->sortByDesc('timezone_display');

		$plus_time_zones = $time_zones_list->filter(function($value, $key)
											  {
											  	return (strpos($value['timezone_display'], 'GMT+') !== false);
											  })->sortBy('timezone_display');

		$time_zones_list = $minus_time_zones->merge($plus_time_zones);

		return $time_zones_list->pluck('timezone_display', 'timezone_val')->toArray();
	}
}

if(!function_exists('non_property_checker'))
{
	function non_property_checker($object = null, $property = null)
	{
		if(is_null($object) || !is_object($object) || is_null($property)) :
			return null;
		endif;

		return $object->$property;
	}
}

if(!function_exists('empty_property_checker'))
{
	function empty_property_checker($object = null, $property = null)
	{
		if(is_null($object) || !is_object($object) || is_null($property)) :
			return '&nbsp;';
		endif;

		return $object->$property;
	}
}

if(!function_exists('single_request_field'))
{
	function single_request_field($request, $field_array)
	{
		foreach($field_array as $field) :
			if(isset($request->$field)) :
				return $field;
			endif;	
		endforeach;	

		return null;
	}
}

if(!function_exists('get_date_after'))
{
	function get_date_after($days, $from_date = null)
	{
		if($from_date == null) :
			$from_date = date('Y-m-d');
		endif;

		$time = strtotime($from_date);
		$date = strtotime("+$days days", $time);
		$outcome = date('Y-m-d', $date);

		return $outcome;
	}
}

if(!function_exists('min_zero'))
{
	function min_zero($num)
	{
		if($num < 0) :
			return 0;
		endif;

		return $num;
	}
}

if(!function_exists('max_value_fixer'))
{
	function max_value_fixer($num, $max)
	{
		if($num > $max) :
			return $max;
		endif;

		return $num;
	}
}

if(!function_exists('generate_uploaded_filename'))
{
	function generate_uploaded_filename($original_name)
	{
		$uploaded_filename = time() . '_' . str_random(10) . '_' . $original_name;
		return $uploaded_filename;
	}
}

if(!function_exists('uploaded_filename_original'))
{
	function uploaded_filename_original($uploaded_filename)
	{		
		$sublen = strpos($uploaded_filename, '_') + 12;
		$original_filename = substr($uploaded_filename, $sublen);
		$ext = pathinfo($original_filename, PATHINFO_EXTENSION);
		if(!empty($ext)) :
			return $original_filename;
		endif;
		
		return $uploaded_filename;
	}
}

if(!function_exists('filesize_kb'))
{
	function filesize_kb($file_path)
	{
		$file_size_bytes = filesize($file_path);
		$file_size_kb = number_format(($file_size_bytes / 1024), 2, '.', '') + 0;
		return $file_size_kb;
	}
}

if(!function_exists('readable_filesize'))
{
	function readable_filesize($kilobytes)
	{
		$bytes = $kilobytes * 1024;

		if($bytes < 1048576) :
			return $kilobytes . ' KB';
		endif;

		if($bytes >= 1073741824) : 
			$outcome = number_format($bytes / 1073741824, 2) . ' GB';
		else : 
			$outcome = number_format($bytes / 1048576, 2) . ' MB';
		endif;

		return $outcome;
	}
}

if(!function_exists('get_file_icon'))
{
	function get_file_icon($extension)
	{
		switch($extension) :
	      	case 'webp':
	      	case 'jpeg':
	        case 'jpg' :
	        case 'png' :
	        case 'gif' :
	            return "<i class='icon image fa fa-file-image-o'></i>";
	        break;

	        case 'zip':
	        case 'rar':
	        case 'iso':
	        case 'tar':
	        case 'tgz':
	        case '7z' :
	        case 'apk':
	        case 'dmg':
	            return "<i class='icon zip fa fa-file-zip-o'></i>";
	        break;

	        case 'docx':
	        case 'doc' :
	            return "<i class='icon word fa fa-file-word-o'></i>";
	        break;

	        case 'xlsx':
	        case 'xls' :
	        case 'csv' :
	        case 'ods' :
	            return "<i class='icon excel fa fa-file-excel-o'></i>";
	        break;

	        case 'pptx':
	        case 'pptm':
	        case 'ppt' :
	            return "<i class='icon powerpoint fa fa-file-powerpoint-o'></i>";
	        break;

	        case 'pdf':
	            return "<i class='icon pdf fa fa-file-pdf-o'></i>";
	        break;

	        case 'wav' :
	        case 'wma' :
	        case 'mpc' :
	        case 'msv' :
	        	return "<i class='icon audio fa fa-file-audio-o'></i>";
	        break;

	        case 'mp3' :
	        case 'm4a' :
	        case 'm4b' :
	        case 'm4p' :
	            return "<i class='icon audio fa fa-music'></i>";
	        break;

	        case 'mov':
	        case 'mp4':
	        case 'avi':
	        case 'flv':
	        case 'wmv':
	        case 'swf':
	        case 'mkv':
	        case 'mpg':
	            return "<i class='icon video fa fa-file-video-o'></i>";
	        break;

	        case 'txt':
	            return "<i class='icon text fa fa-file-text-o'></i>";
	        break;

	        case 'html':
	        case 'php' :
	            return "<i class='icon code fa fa-file-code-o'></i>";
	        break;

	        default: return "<i class='icon file fa fa-file-o'></i>";
		endswitch;
	}
}

if(!function_exists('null_if_empty'))
{
	function null_if_empty($val = null)
	{
		if(isset($val) && $val !== '') :
			return $val;
		endif;

		return null;
	}
}

if(!function_exists('null_if_not_key'))
{
	function null_if_not_key($key, $arr)
	{
		if(array_key_exists($key, $arr)) :
			return $arr[$key];
		endif;

		return null;
	}
}

if(!function_exists('json_if_array'))
{
	function json_if_array($val = null)
	{
		if(isset($val) && $val !== '') :
			return is_array($val) ? json_encode($val) : $val;
		endif;

		return null;
	}
}

if(!function_exists('replace_null_if_empty'))
{
	function replace_null_if_empty($arr)
	{
		foreach($arr as $key => $val) :
			$arr[$key] = (isset($val) && $val !== '') ? $arr[$key] : null;
		endforeach;	

		return $arr;
	}
}

if(!function_exists('in_array_filter'))
{
	function in_array_filter($needle, $haystack, $default = null)
	{
		if(in_array($needle, $haystack)) :
			return $needle;
		endif;	

		return $default;
	}
}

if(!function_exists('encrypt_if_has_value'))
{
	function encrypt_if_has_value($val = null)
	{
		if(isset($val) and $val !== '') :
			return encrypt($val);
		endif;

		return null;
	}
}

if(!function_exists('check_before_decrypt'))
{
	function check_before_decrypt($val = null)
	{
		if(isset($val) and $val !== '') :
			return decrypt($val);
		endif;

		return null;
	}
}

if(!function_exists('standard_number_format'))
{
	function standard_number_format($num_val)
	{
		if(floor($num_val) != $num_val) :
			return number_format($num_val, 2, '.', ',');
		endif;	

		return number_format($num_val);
	}
}

if(!function_exists('trim_lower_snake'))
{
	function trim_lower_snake($str)
	{
		$outcome = trim($str);
		$outcome = strtolower($outcome);
		$outcome = str_replace(' ', '_', $outcome);

		return $outcome;
	}
}

if(!function_exists('snake_to_space'))
{
	function snake_to_space($str)
	{
		$outcome = trim($str);
		$outcome = str_replace('_', ' ', $outcome);

		return $outcome;
	}
}

if(!function_exists('snake_to_ucwords'))
{
	function snake_to_ucwords($snake)
	{
		$ucwords = str_replace('_', ' ', $snake);
		$ucwords = ucwords($ucwords);		

		return $ucwords;
	}
}

if(!function_exists('vowel_checker'))
{
	function vowel_checker($word)
	{
		$vowels = ['a', 'e', 'i', 'o', 'u'];
		$firstword = substr($word, 0, 1);
		$firstword = strtolower($firstword);
		$outcome = 'a ' . $word;
		if(in_array($firstword, $vowels)) :
			$outcome = 'an ' . $word;
		endif;	

		return $outcome;
	}
}

if(!function_exists('place_currency_symbol'))
{
	function place_currency_symbol($amount, $symbol, $symbol_position)
	{
		$format = $symbol_position == 'after' ?  $amount . $symbol : $symbol . $amount;
		return $format;
	}
}

if(!function_exists('append_css_class'))
{
	function append_css_class($class, $append_class, $filter_type = true, $types_array, $type)
	{
		$outcome = $class . ' ' . $append_class;

		if($filter_type == true && in_array($type, $types_array)) :
			return $outcome;
		endif;		

		if($filter_type == false && !in_array($type, $types_array)) :
			return $outcome;
		endif;

		return $class;
	}
}

if(!function_exists('tag_attr'))
{
	function tag_attr($value, $expected_value, $attribute_name, $attribute_value = null)
	{
		$attribute_value = is_null($attribute_value) ? $attribute_name : $attribute_value;

		if($value == $expected_value) :
			return $attribute_name . "='" . $attribute_value . "'";
		endif;

		return null;
	}
}

if(!function_exists('setting'))
{
	function setting($key, $alternative = null)
	{
		$key_object = Setting::whereKey($key)->first();

		if(isset($key_object) && is_object($key_object)) :
			return $key_object->value;
		endif;	

		return $alternative;
	}
}

if(!function_exists('is_file_writable'))
{
	function is_file_writable($file_path)
	{
		if(file_exists($file_path) && is_writable($file_path)) :
			return true;
		endif;	

		return false;
	}
}

if(!function_exists('is_json'))
{
	function is_json($str = null)
	{
		json_decode($str);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}

if(!function_exists('decode_if_json'))
{
	function decode_if_json($str = null)
	{
		if(is_json($str)) :
			return json_decode($str);
		endif;	

		return $str;
	}
}

if(!function_exists('country_code_to_name'))
{
	function country_code_to_name($country_code)
	{
		$country = Country::whereCode($country_code)->first();

		if(isset($country) && is_object($country)) :
			return $country->ascii_name;
		endif;	

		return null;
	}
}

if(!function_exists('file_public_uploads'))
{
	function file_public_uploads($file, $upload_directory)
	{
		$public_path = public_path($upload_directory);
		if(!file_exists($public_path)) :
			mkdir($public_path, 0777, true);
		endif;

		$file_name = time() . '_' . $file->getClientOriginalName();
		$upload_path = public_path($upload_directory . $file_name);
		\Image::make($file->getRealPath())->save($upload_path);

		return $file_name;
	}
}

if(!function_exists('clean_public_uploads'))
{
	function clean_public_uploads($file_path)
	{
		if(isset($file_path) && strpos($file_path, 'uploads/') !== false) :			
			$file_public_path = public_path($file_path); 
			if(file_exists($file_public_path)) :
				unlink($file_public_path);	
			endif;
		endif;	
	}
}

if(!function_exists('module_icon'))
{
	function module_icon($module)
	{
		$default_icon = 'fa fa-cube';

		$icon_list = [
			'lead'		=> 'mdi mdi-bullseye-arrow',
			'contact'	=> 'mdi mdi-account-circle',
			'account'	=> 'mdi mdi-domain lg',
			'deal'		=> 'fa fa-handshake-o',
			'project'	=> 'mdi mdi-library-books',
			'estimate'	=> 'fa fa-calculator sm',
			'invoice'	=> 'mdi mdi-file-document-box',
			'campaign'	=> 'fa fa-bullhorn sm',
			'task'		=> 'mdi mdi-clipboard-check',
			'event'		=> 'mdi mdi-calendar-star',
			'call'		=> 'mdi mdi-phone',
			'call-incoming'	=> 'mdi mdi-phone-incoming',
			'call-outgoing'	=> 'mdi mdi-phone-outgoing',
		];

		if(array_key_exists($module, $icon_list)) :
			return $icon_list[$module];
		endif;	

		return $default_icon;
	}
}		

if(!function_exists('currency_icon'))
{
	function currency_icon($code, $symbol)
	{
		$icon_list = [
			'USD' => ['$', 'fa fa-usd'],
			'CAD' => ['$', 'fa fa-dollar'],
			'GBP' => ['£', 'fa fa-gbp'],
			'EUR' => ['€', 'fa fa-eur'],
			'BTC' => ['Ƀ', 'fa fa-btc'],
			'CNY' => ['¥', 'fa fa-cny'],				
			'ILS' => ['₪', 'fa fa-ils'],	
			'INR' => ['₹', 'fa fa-inr'],	
			'JPY' => ['¥', 'fa fa-jpy'],
			'KRW' => ['₩', 'fa fa-krw'],	
			'RMB' => ['¥', 'fa fa-rmb'],	
			'RUB' => ['₽', 'fa fa-rub'],	
			'NIS' => ['₪', 'fa fa-shekel'],	
			'TRY' => ['₺', 'fa fa-try'],	
			'WON' => ['₩', 'fa fa-won'],	
			'YEN' => ['¥', 'fa fa-yen'],
			'ETH' => ['Ξ', 'mdi mdi-currency-eth'],
			'KZT' => ['₸', 'mdi mdi-currency-kzt'],
			'NGN' => ['₦', 'mdi mdi-currency-ngn'],
			'PHP' => ['₱', 'mdi mdi-currency-php'],
			'TWD' => ['$', 'mdi mdi-currency-twd']
		];

		if(array_key_exists($code, $icon_list)) :
			return $symbol == $icon_list[$code][0] ? $icon_list[$code][1] : null;
		endif;

		return null;
	}
}

if(!function_exists('time_period_list'))
{
	function time_period_list()
	{
		return [
			'any' => 'Any time',
			'between' => 'Is between',
			'yesterday' => 'Yesterday',
			'today' => 'Today',
			'tommorrow' => 'Tommorrow',
			'last_month' => 'Last Month',
			'current_month' => 'Current Month',
			'next_month' => 'Next Month',
			'last_7_days' => 'Last 7 days',
			'last_30_days' => 'Last 30 days',
			'last_60_days' => 'Last 60 days',
			'last_90_days' => 'Last 90 days',
			'last_120_days' => 'Last 120 days',
			'last_6_months' => 'Last 6 months',
			'last_12_months' => 'Last 12 months',
			'next_7_days' => 'Next 7 days',
			'next_30_days' => 'Next 30 days',
			'next_60_days' => 'Next 60 days',
			'next_90_days' => 'Next 90 days',
			'next_120_days' => 'Next 120 days',
			'next_6_months' => 'Next 6 months',
			'next_12_months' => 'Next 12 months'
		];
	}
}

if(!function_exists('default_time_period'))
{
	function default_time_period($time_period_key, $alternative = null)
	{
		if(array_key_exists($time_period_key, time_period_list())) :
			return $time_period_key;
		elseif(!is_null($alternative) && array_key_exists($alternative, time_period_list())) :
			return $alternative;
		else :
			return 'last_30_days';
		endif;	
	}
}		

if(!function_exists('time_period_dates'))
{
	function time_period_dates($time_period_key, $start_date = null, $end_date = null)
	{
		if(!is_null($start_date) && !is_null($end_date)) :
			return ['start_date' => $start_date, 'end_date' => $end_date];
		endif;

		switch($time_period_key) :
			case 'yesterday' :
				$date = date("Y-m-d", strtotime("-1 days"));
				$date = date("Y-m-d H:i:s", strtotime($date));
				return ['start_date' => $date, 'end_date' => $date];
			break;

			case 'today' :
				$date = date("Y-m-d");
				$date = date("Y-m-d H:i:s", strtotime($date));
				return ['start_date' => $date, 'end_date' => $date];
			break;

			case 'tommorrow' :
				$date = date("Y-m-d", strtotime("+1 days"));
				$date = date("Y-m-d H:i:s", strtotime($date));
				return ['start_date' => $date, 'end_date' => $date];
			break;

			case 'last_month' :
				$start_date = date("Y-m-d H:i:s", strtotime("first day of previous month"));
				$end_date = date("Y-m-d H:i:s", strtotime("last day of previous month"));
				return ['start_date' => $start_date, 'end_date' => $end_date];
			break;

			case 'current_month' :
				$start_date = date("Y-m-d H:i:s", strtotime("first day of this month"));
				$end_date = date("Y-m-d H:i:s", strtotime("last day of this month"));
				return ['start_date' => $start_date, 'end_date' => $end_date];
			break;

			case 'next_month' :
				$start_date = date("Y-m-d H:i:s", strtotime("first day of next month"));
				$end_date = date("Y-m-d H:i:s", strtotime("last day of next month"));
				return ['start_date' => $start_date, 'end_date' => $end_date];
			break;

			case 'last_7_days' :
				$start_date = date("Y-m-d H:i:s", strtotime("-7 days"));
				return ['start_date' => $start_date, 'end_date' => date("Y-m-d H:i:s")];
			break;

			case 'last_30_days' :
				$start_date = date("Y-m-d H:i:s", strtotime("-30 days"));
				return ['start_date' => $start_date, 'end_date' => date("Y-m-d H:i:s")];
			break;

			case 'last_60_days' :
				$start_date = date("Y-m-d H:i:s", strtotime("-60 days"));
				return ['start_date' => $start_date, 'end_date' => date("Y-m-d H:i:s")];
			break;

			case 'last_90_days' :
				$start_date = date("Y-m-d H:i:s", strtotime("-90 days"));
				return ['start_date' => $start_date, 'end_date' => date("Y-m-d H:i:s")];
			break;

			case 'last_120_days' :
				$start_date = date("Y-m-d H:i:s", strtotime("-120 days"));
				return ['start_date' => $start_date, 'end_date' => date("Y-m-d H:i:s")];
			break;

			case 'last_6_months' :
				$start_date = date("Y-m-d H:i:s", strtotime("first day of this month - 5 months"));
				$end_date = date("Y-m-d H:i:s", strtotime("last day of this month"));
				return ['start_date' => $start_date, 'end_date' => $end_date];
			break;

			case 'last_12_months' :
				$start_date = date("Y-m-d H:i:s", strtotime("first day of this month - 11 months"));
				$end_date = date("Y-m-d H:i:s", strtotime("last day of this month"));
				return ['start_date' => $start_date, 'end_date' => $end_date];
			break;

			case 'next_7_days' :
				$end_date = date("Y-m-d H:i:s", strtotime("+7 days"));
				return ['start_date' => date("Y-m-d H:i:s"), 'end_date' => $end_date];
			break;

			case 'next_30_days' :
				$end_date = date("Y-m-d H:i:s", strtotime("+30 days"));
				return ['start_date' => date("Y-m-d H:i:s"), 'end_date' => $end_date];
			break;

			case 'next_60_days' :
				$end_date = date("Y-m-d H:i:s", strtotime("+60 days"));
				return ['start_date' => date("Y-m-d H:i:s"), 'end_date' => $end_date];
			break;

			case 'next_90_days' :
				$end_date = date("Y-m-d H:i:s", strtotime("+90 days"));
				return ['start_date' => date("Y-m-d H:i:s"), 'end_date' => $end_date];
			break;

			case 'next_120_days' :
				$end_date = date("Y-m-d H:i:s", strtotime("+120 days"));
				return ['start_date' => date("Y-m-d H:i:s"), 'end_date' => $end_date];
			break;

			case 'next_6_months' :
				$start_date = date("Y-m-d H:i:s", strtotime("first day of next month"));
				$end_date = date("Y-m-d H:i:s", strtotime("last day of next month + 5 months"));				
				return ['start_date' => $start_date, 'end_date' => $end_date];
			break;

			case 'next_12_months' :
				$start_date = date("Y-m-d H:i:s", strtotime("first day of next month"));
				$end_date = date("Y-m-d H:i:s", strtotime("last day of next month + 11 months"));				
				return ['start_date' => $start_date, 'end_date' => $end_date];
			break;

			default : return ['start_date' => date("Y-m-d H:i:s", strtotime("-30 days")), 'end_date' => date("Y-m-d H:i:s")];
		endswitch;	
	}
}		

if(!function_exists('get_notification_title'))
{
	function get_notification_title($case, $item)
	{
		$title = null;
		$item = snake_to_space($item);

		if(strrpos($case, 'created') !== false) :
			$title = 'Added ' . vowel_checker($item);
			return $title;
		endif;

		$notification_titles = ['task_assigned' => 'Added a task',
								'estimate_sent'	=> 'Estimate sent to account',
								'estimate_accepted' => 'Account accepted the estimate',
								'estimate_declined' => 'Account declined the estimate',
								'invoice_sent' => 'Invoice sent to account',
								'invoice_paid' => 'Invoice paid',
								'invoice_partially_paid' => 'Invoice partially paid'];

		$title = $notification_titles[$case];						

		return $title;
	}
}	

if(!function_exists('notification_log'))
{
	function notification_log($case, $case_linked_type, $case_linked_id, $notifee_type, $notifee = null)
	{
		// Auth User can not notify himself / herself
		if($notifee_type == auth()->user()->linked_type && !is_null($notifee) && auth_linked()->id == $notifee) :
			return false;
		endif;

		$notification_info = new NotificationInfo;
		$notification_info->case = $case;
		$notification_info->linked_type = $case_linked_type;
		$notification_info->linked_id = $case_linked_id;		
		$notification_info->save();

		$notifees = [];
		if(!is_null($notifee)) :
			if(!is_array($notifee)) :
				$notification = new Notification;
				$notification->notification_info_id = $notification_info->id;
				$notification->linked_type = $notifee_type;
				$notification->linked_id = $notifee;			
				$notification->save();
			else :
				$notifees = $notifee;
			endif;	
		else :
			$notifees = morph_to_model($notifee_type)::where('id', '!=', auth_linked()->id)->pluck('id')->toArray();
		endif;

		if(count($notifees) > 0) :
			foreach($notifees as $single_notifee) :
				Notification::create(['notification_info_id' => $notification_info->id, 'linked_type' => $notifee_type, 'linked_id' => $single_notifee]);
			endforeach;	
		endif;	

		return true;
	}
}

if(!function_exists('module_identifier'))
{
	function module_identifier($string)
	{
		$pos = strpos($string, '.');
		$identifier = substr($string, $pos+1);
		return $identifier;
	}
}

if(!function_exists('permissions_map_key'))
{
	function permissions_map_key($string)
	{
		$permission_array = explode('.', $string);
		$map_key = $permission_array[1];
		return $map_key;
	}
}

if(!function_exists('permission_summary'))
{
	function permission_summary($string)
	{
		$outcome = snake_to_space($string);
		$outcome = ucwords($outcome);
			
		return $outcome;
	}
}

if(!function_exists('permission_checked'))
{
	function permission_checked($has_permission)
	{
		$outcome = '';
		if($has_permission == true) :
			$outcome = 'checked';
		endif;

		return $outcome;
	}
}

if(!function_exists('display_module_permissions'))
{
	function display_module_permissions($module_permissions, $is_disabled = false)
	{
		$outcome = '';
		$permission_summary = "<p class='para-type-b'>";
		$permission_details = "<div class='div-type-e'>";
		$single_permissions = '';
		$permission_block_has = false;
		$container_show = false;
		$disabled = '';

		if($is_disabled == true) :
			$disabled = ' disabled';
		endif;	

		foreach($module_permissions as $key => $value) :
			$status = '';
			$show_permission_summary = 0;
			$display_none = '';
			if(count($value) == 1) :
				$single_permissions .= "<p class='para-type-c pretty danger smooth'><input type='checkbox' name='permissions[]' parent='" . permission_summary($key) . "' value='" . $value[0]['id'] . "' " . permission_checked($value[0]['has_permission']) . $disabled . "><label><i class='mdi mdi-check'></i></label> <span>" . $value[0]['display_name'] . "</span></p>";
				$status .= $value[0]['id'];
				$show_permission_summary += $value[0]['has_permission'];
			else :
				$permission_block_has = true;
				$permission_details .= "<div class='div-type-f'>";
				$permission_details .= "<h3 class='title-type-c'>" . snake_to_space($key) . "</h3>";
					foreach($value as $permission) :
						$permission_details .= "<p class='para-type-c pretty danger smooth'><input type='checkbox' name='permissions[]' parent='" . permission_summary($key) . "' value='" . $permission['id'] . "' " . permission_checked($permission['has_permission']) . $disabled . "><label><i class='mdi mdi-check'></i></label> <span>" . $permission['display_name'] . "</span></p>";
						$status .= $permission['id'];
						$show_permission_summary += $permission['has_permission'];
					endforeach;
				$permission_details .= "</div>";
			endif;

			if($show_permission_summary == 0) :
				$display_none = "style='display: none'";
			else :
				$container_show = true;
			endif;

			if($value == end($module_permissions)) :
				$permission_summary .= "<span id='" . permission_summary($key) . "-" . $status . "' status='" . $status . "' name='" . permission_summary($key) . "' " . $display_none . ">" . permission_summary($key) . "</span>";
			else :
				$permission_summary .= "<span id='" . permission_summary($key) . "-" . $status . "' status='" . $status . "' name='" . permission_summary($key) . "' " . $display_none . ">" . permission_summary($key) . ", </span>";
			endif;	 
		endforeach;

		if($single_permissions != '') :
			if($permission_block_has == true) :
				$permission_details .= "<div class='div-type-g'>";		            									
				$permission_details .= "<h3 class='title-type-c'>Others</h3>";		            									
				$permission_details .=	$single_permissions;
				$permission_details .= "</div>";
			else :
				$permission_details .= "<div class='div-type-g m-bottom-0'>";  									
				$permission_details .=	$single_permissions;
				$permission_details .= "</div>";
			endif;
		endif;

		if($permission_block_has == true) :
			$permission_details = substr_replace($permission_details, "<div class='div-type-e double'>", 0, 24);
		endif;

		if($container_show == true) :
			$start_container = "<div class='col-xs-12 col-sm-6 col-md-8 col-lg-8 div-type-d block'>";			
		else :
			$start_container = "<div class='col-xs-12 col-sm-6 col-md-8 col-lg-8 div-type-d'>";
		endif;	

		$end_container = "</div>";

		$permission_summary .= "</p>";
		$permission_details .= "</div>";

		$outcome .= $permission_summary;
		$outcome .= "<p class='para-type-b'><span class='pe-7s-angle-down pe-2x pe-va'></span></p>";
		$outcome .= "<div class='line'></div>";
		$outcome .= $permission_details;
		$outcome = $start_container . $outcome . $end_container;

		return $outcome;
	}
}

if(!function_exists('tab_nav_html'))
{
	function tab_nav_html($tabs)
	{
		$menu_css = count($tabs['list']) > 15 ? 'high-density' : '';
		$html = "<ul id='item-tab' class='menu-h $menu_css'>";
		$dropdown = "<ul class='dropdown-menu up-caret'>";
		$total_li = count($tabs['list']);

		if($total_li > 9 && $total_li <= 13) :
			$dropdown_class = 'hide-lim-md';
		elseif($total_li > 7 && $total_li <= 9) :
			$dropdown_class = 'hide-lim-sm';
		elseif($total_li > 5 && $total_li <= 7) :
			$dropdown_class = 'hide-lim-xs';
		elseif($total_li > 3 && $total_li <= 5) :
			$dropdown_class = 'hide-lim-xxs';
		elseif($total_li >= 1 && $total_li <= 3) :
			$dropdown_class = 'none';
		else :
			$dropdown_class = 'block';
		endif;	

		$i = 1;
		foreach($tabs['list'] as $key => $value) :
			$class = $key == $tabs['default'] ? 'active' : null;

			if($i > 9 && $i <= 13) :
				$li_class = 'display-lim-lg';
				$dropdown_li_class = 'hide-lim-md';
			elseif($i > 7 && $i <= 9) :
				$li_class = 'display-lim-md';
				$dropdown_li_class = 'hide-lim-sm';
			elseif($i > 5 && $i <= 7) :
				$li_class = 'display-lim-sm';
				$dropdown_li_class = 'hide-lim-xs';
			elseif($i > 3 && $i <= 5) :
				$li_class = 'display-lim-xs';
				$dropdown_li_class = 'hide-lim-xxs';
			elseif($i >= 1 && $i <= 3) :
				$li_class = 'display-lim-xxs';
				$dropdown_li_class = 'none';
			else :
				$li_class = 'none';
				$dropdown_li_class = 'block';
			endif;	

			$html .= "<li class='$li_class'><a class='$class' tabkey='$key'>$value</a></li>";
			$dropdown .= "<li class='$dropdown_li_class'><a class='tab-link $class' tabkey='$key'>$value</a></li>";
			$i++;
		endforeach;		

		$dropdown .= "</ul>";
		$html .= "<li class='dropdown dark $dropdown_class'>
					<a class='not-load dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'><i class='mdi mdi-dots-horizontal fa-md pe-va'></i></a>
					$dropdown
				  </li>";

		$html .= "<a class='setup'><i class='fa fa-cog'></i></a>";		  

		$html .= "</ul>";

		return $html;
	}
}		