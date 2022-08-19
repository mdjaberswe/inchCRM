<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class LeadScoreRule extends BaseModel
{
	use SoftDeletes;	
	use RevisionableTrait;
	
	protected $table = 'lead_score_rules';
	protected $fillable = ['lead_score_id', 'related_to', 'attribute', 'condition', 'value', 'description'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function formDataCheck($data)
	{
		if(array_key_exists('score_only', $data) && $data['score_only'] == 1) : 
			$rules = ["scoring_type"	=> "required|in:1,0",
					  "score"			=> "required|numeric|min:0"];

			$outcome['validation'] = \Validator::make($data, $rules);
			
			return $outcome;		  
		endif;	

		$lead_score_ids = LeadScore::commaSeparatedIds([0]);

		$required = '';
		if(array_key_exists('lead_score_id', $data) && $data['lead_score_id'] == 0) : 
			$required = "required";
		endif;	

		$rules = ["related_to"		=> "required|in:lead_property,email_activity",
				  "scoring_type"	=> "$required|in:1,0",
				  "score"			=> "$required|numeric|min:0",
				  "lead_score_id"	=> "required|in:$lead_score_ids"];

		if(array_key_exists('related_to', $data) && $data['related_to'] == 'email_activity') :
			$rules['subject'] = "required";
			$rules['email_activity'] = "required|in:opened,clicked";
			$rules['email_condition'] = "required|in:0,7,30,90";
		endif;		 

		if(array_key_exists('related_to', $data) && $data['related_to'] == 'lead_property') : 
			$property_list = Lead::scorePropertyList();
			$properties = array_keys($property_list);
			$properties_str = implode(',', $properties);
			$rules['lead_property'] = "required|in:$properties_str";

			if(array_key_exists('lead_property', $data)) :
				$classify_property = Lead::scoreDividedByCondition();
				$lead_property = $data['lead_property'];
				$property_class = array_parent_search($classify_property, $lead_property);
				$outcome['condition'] = $property_class . '_condition';
				$outcome['value'] = $property_class == 'dropdown' ? $lead_property : $property_class . '_value';

				switch($property_class) :
					case 'string' :	
						$rules['string_condition'] = "required|in:equal,not_equal,contain,not_contain,empty,not_empty";
						
						if(array_key_exists('string_condition', $data) && !in_array($data['string_condition'], ['empty', 'not_empty'])) :
							$rules['string_value'] = "required";
						endif;	
					break;

					case 'numeric' :
						$rules['numeric_condition'] = "required|in:equal,not_equal,less,greater";
						$rules['numeric_value'] = "required|numeric";
					break;

					case 'date' :
						$outcome['value'] = 'days_value';
						$rules['date_condition'] = "required|in:before,after,last,next,empty,not_empty";

						if(array_key_exists('date_condition', $data) && !in_array($data['date_condition'], ['empty', 'not_empty'])) :
							$rules['days_value'] = "required|in:7,30,90";
						endif;	
					break;

					case 'dropdown' :	
						$rules['dropdown_condition'] = "required|in:equal,not_equal,empty,not_empty";

						if(array_key_exists('dropdown_condition', $data) && !in_array($data['dropdown_condition'], ['empty', 'not_empty'])) :
							switch($lead_property) :
								case 'currency_id' :
									$rules['currency_id'] = "required|exists:currencies,id,deleted_at,NULL";
								break;

								case 'no_of_employees' :
									$rules['no_of_employees'] = "required|array|in:1-10,11-50,51-200,201-500,501-1000,1001";
								break;								

								case 'access' :
									$rules['access'] = "required|array|in:private,public,public_rwd";
								break;

								case 'country_code' :
									$rules['country_code'] = "required|exists:countries,code";
								break;

								case 'campaign_id' :
									$rules['campaign_id'] = "required|exists:campaigns,id,deleted_at,NULL";
								break;

								case 'event_id' :
									$rules['event_id'] = "required|exists:events,id,deleted_at,NULL";
								break;

								case 'source_id' :
									$rules['source_id'] = "required|exists:sources,id,deleted_at,NULL";
								break;

								case 'lead_stage_id' :
									$rules['lead_stage_id'] = "required|exists:lead_stages,id,deleted_at,NULL";
								break;

								case 'last_activity_type' :
									$rules['last_activity_type'] = "required|array|in:chat,phone,task,email,sms";
								break;

								case 'lead_owner' :
								case 'created_by' :
								case 'modified_by' :
									$rules[$lead_property] = "required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL";
								break;
							endswitch; 
						endif;	
					break;
				endswitch;	
			endif; 
		endif;

		$outcome['validation'] = \Validator::make($data, $rules);

		return $outcome;
	}

	public static function classifyScoreValidate($data)
	{
		$range_end_min = array_key_exists('range_start', $data) ? "min:" . ($data['range_start'] + 5) : '';

		$rules = ["range_start"	=> "required|integer|min:11",
				  "range_end"	=> "required|integer|max:90|different:range_start|$range_end_min",
				  "cold_lead"	=> "required|max:200",
				  "warm_lead"	=> "required|max:200|different:cold_lead",
				  "hot_lead"	=> "required|max:200|different:cold_lead|different:warm_lead"];

		return \Validator::make($data, $rules);
	}

	public static function descriptionMaker($request, $attribute = null, $condition = null, $value = null)
	{
		$description = null;

		if($request->related_to == 'email_activity') :
			$description = "Lead <span class='highlight'>" . $request->email_activity . "</span> email \"" . $request->subject . "\"";
			$description .= $request->email_condition ? " within the <span class='highlight'>last " . $request->email_condition . " days</span>." : ".";
		endif;

		if($request->related_to == 'lead_property') :
			$display_attributes = Lead::scorePropertyList();
			$display_value = is_array($value) ? implode(', <i>or</i> ', $value) : $value;			

			$custom_display_values = ['source_id', 'lead_stage_id', 'currency_id', 'campaign_id', 'event_id', 'lead_owner', 'created_by', 'modified_by', 'country_code', 'access'];
			if(in_array($attribute, $custom_display_values)) :
				if(strpos($attribute, '_id') !== false) :
					$morph = str_replace('_id', '', $attribute);
					$name = morph_to_model($morph)::whereIn('id', $value)->get()->pluck('name')->toArray();

				elseif(strpos($attribute, '_by') !== false || $attribute == 'lead_owner') :
					$name =  Staff::whereIn('id', $value)->get(['first_name', 'last_name'])->pluck('name')->toArray();

				elseif($attribute == 'access') :
					if(in_array('public_rwd', $value)) :				
						$key = array_search('public_rwd', $value);
						$value[$key] = 'public read/write/delete';
					endif;	
					$name = array_map('ucfirst', $value);

				elseif($attribute == 'country_code') :
					$name =  Country::whereIn('code', $value)->get()->pluck('ascii_name')->toArray();
				endif;	

				$display_value = is_array($name) ? implode(', <i>or</i> ', $name) : $name;
			endif;

			$any_of = strpos($display_value, ', <i>or</i> ') !== false ? "any of" : "";

			switch($condition) :
				case 'equal' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "is equal to $any_of <span class='highlight'>" . $display_value . "</span>.";
				break;

				case 'not_equal' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "is not equal to $any_of <span class='highlight'>" . $display_value . "</span>.";
				break;

				case 'contain' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "contains $any_of <span class='highlight'>" . $display_value . "</span>.";
				break;

				case 'not_contain' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "does not contain $any_of <span class='highlight'>" . $display_value . "</span>.";
				break;

				case 'empty' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "is empty.";
				break;

				case 'not_empty' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "is not empty.";
				break;

				case 'less' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "is less than <span class='highlight'>" . standard_number_format($display_value) . "</span>";
				break;

				case 'greater' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "is greater than <span class='highlight'>" . standard_number_format($display_value) . "</span>";
				break;

				case 'before' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "is <span class='highlight'>before " . $display_value . " days</span>.";
				break;

				case 'after' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "is <span class='highlight'>after " . $display_value . " days</span>.";
				break;

				case 'last' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "is in the <span class='highlight'>last " . $display_value . " days</span>.";
				break;

				case 'next' :
					$description = "The lead property <span class='highlight'>" . $display_attributes[$attribute] . "</span> ";
					$description.= "is in the <span class='highlight'>next " . $display_value . " days</span>.";
				break;
			endswitch;	
		endif;	

		return $description;
	}

	public function setRoute()
	{
		return 'administration-setting-lead-scoring-rule';
	}

	public function setPermission()
	{
		return 'settings.lead_scoring_rule';
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getLeadFormFieldAttribute()
	{
		$classify_property = Lead::scoreDividedByCondition();
		$lead_property = $this->attribute;
		$property_class = array_parent_search($classify_property, $lead_property);
		$value = decode_if_json($this->value);
		$outcome['condition'] = $property_class . '_condition';
		$outcome['value'] = $property_class == 'dropdown' ? $lead_property : $property_class . '_value';
		$outcome['value'] = $property_class == 'date' ? 'days_value' : $outcome['value'];
		$outcome['value'] = is_array($value) ? $outcome['value'] . '[]' : $outcome['value'];

		return $outcome;
	}

	public function getScoreValAttribute()
	{
		return abs($this->score->score);
	}

	public function getScoringTypeAttribute()
	{
		return ($this->score->score < 0) ? 0 : 1;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function score()
	{
		return $this->belongsTo(LeadScore::class, 'lead_score_id');
	}
}