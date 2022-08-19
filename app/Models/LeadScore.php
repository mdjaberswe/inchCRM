<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class LeadScore extends BaseModel
{
	use SoftDeletes;	
	use RevisionableTrait;
	
	protected $table = 'lead_scores';
	protected $fillable = ['score'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

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
	public function getRuleHtmlAttribute()
	{
		$html = '';
		$total_rules = $this->rules()->count();

		$i = 0;
		foreach($this->rules as $rule) :
			$i++;
			if($i < $total_rules) :
				$html .= "<div class='block-chain'>
							<a editid='" . $rule->id . "' class='block-chain-info edit'>" .
								$rule->description . "
							</a>
							<span class='block-chain-line'><i>and</i></span>
						  </div>";
			else :
				$html .= "<div class='block-chain bottom-prev'>
							<a editid='" . $rule->id . "' class='block-chain-info edit'>" .
								$rule->description . "
							</a>
						  </div>";

				$html .= "<div class='block-chain bottom-btn'>
							<span class='block-chain-line'></span>
							<button type='button' class='block-chain-btn add-multiple' data-item='rule' data-action='" . route('admin.administration-setting-lead-scoring-rule.store') . "' data-content='setting.leadscore.partials.form' data-default='lead_score_id:" . $this->id . "' data-hide='scoring_type|score' data-modalsize='medium' modal-title='Add New \"AND\" Rule' save-new='false'>AND</button>
						 </div>";
			endif;	
		endforeach;	

		return $html;
	}

	public function getScoreHtmlAttribute()
	{
		$text = $this->score > 0 ? '+' . $this->score : $this->score;		
		$rule_id = $this->rules()->first()->id;
		$html = "<a class='link-txt common-edit-btn' data-item='rule' modal-title='Update Rule Score' modal-small='true' data-url='" . route('admin.administration-setting-lead-scoring-rule.edit', $rule_id) . "' data-posturl='" . route('admin.administration-setting-lead-scoring-rule.update', $rule_id) . "' data-default='score_only:1' editid='" . $rule_id . "'>" . $text . "</a>";
		return $html;
	}

	public function extendActionHtml($edit_permission = true)
	{
		$extend_action = '';
		$can_add_and_rule = (permit('settings.lead_scoring_rule.create') && permit('settings.lead_scoring_rule.edit'));

		if($can_add_and_rule) :
			$extend_action .= "<li><a class='block-chain-btn add-multiple' data-item='rule' data-action='" . route('admin.administration-setting-lead-scoring-rule.store') . "' data-content='setting.leadscore.partials.form' data-default='lead_score_id:" . $this->id . "' data-hide='scoring_type|score' data-modalsize='medium' modal-title='Add New \"AND\" Rule' save-new='false'><i class='mdi mdi-shape-square-plus'></i> AND Rule</a></li>";
		endif;

		return $extend_action;	
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function rules()
	{
	    return $this->hasMany(LeadScoreRule::class, 'lead_score_id');
	}
}