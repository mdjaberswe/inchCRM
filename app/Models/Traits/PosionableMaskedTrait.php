<?php

namespace App\Models\Traits;

trait PosionableMaskedTrait
{
	public static function resetPosition()
	{
		$min_position = self::whereMasked(0)->min('position');

		if(isset($min_position)) :
			$min_position_id = self::whereMasked(0)->wherePosition($min_position)->first()->id;
			$top = self::whereMasked(0)->find($min_position_id);	
			$rest = self::whereMasked(0)->where('id', '!=', $min_position_id)->orderBy('position')->get();
			
			$start_with = 1;
			$top->position = $start_with;
			$top->save();

			if($rest->count()) :
				foreach($rest as $single_rest) :
					$start_with++;
					$single_rest->position = $start_with;
					$single_rest->save();
				endforeach;
			endif;

			$trashed_records = self::whereMasked(0)->onlyTrashed()->orderBy('position')->get();
			if($trashed_records->count()) :
				foreach($trashed_records as $trashed_record) :
					$start_with++;
					$trashed_record->position = $start_with;
					$trashed_record->save();
				endforeach;
			endif;

			return true;
		endif;

		return false;
	}

	public static function afterPickedPositionVal($picked_position_id, $target_position_id = null)
	{
		$picked_position_val = self::whereMasked(0)->find($picked_position_id)->position;
		$picked_next_position = self::whereMasked(0)->where('position', '>', $picked_position_val)->min('position');
		$addition_val = isset($picked_next_position) ? ($picked_next_position - $picked_position_val)/2 : 1;
		$position_val = $picked_position_val + $addition_val;

		// CASE 1 : When Form Position Field Value Remain Unchanged
		if(isset($target_position_id) && isset($picked_next_position)) :
			$picked_next_position_id = self::whereMasked(0)->wherePosition($picked_next_position)->first()->id;
			if($picked_next_position_id == $target_position_id) :
				$position_val = $picked_next_position;
			endif;	
		endif;	

		return $position_val;
	}

	public static function downgradeTop($target_position_id = null)
	{
		$outcome = ['status' => false, 'empty_top_position' => 1];
		$top_position = self::whereMasked(0)->min('position');

		if(isset($top_position)) :
			$top_position_id = self::whereMasked(0)->wherePosition($top_position)->first()->id;
			if($top_position_id !== $target_position_id) :
				$next_to_top_position = self::whereMasked(0)->where('position', '>', $top_position)->min('position');				
				$downgrade = isset($next_to_top_position) ? ($next_to_top_position - $top_position)/2 : 1;
				$downgrade_position = $top_position + $downgrade;
				$top = self::whereMasked(0)->find($top_position_id);
				$top->position = $downgrade_position;
				$top->save();
			endif;	

			$outcome = ['status' => true, 'empty_top_position' => $top_position];
			return $outcome;
		endif;
		
		return $outcome;	
	}

	public static function getEmptyTopPosition($target_position_id = null)
	{
		$downgrade_top = self::downgradeTop($target_position_id);
		$empty_top_position = $downgrade_top['empty_top_position'];

		return $empty_top_position;
	}

	public static function getBottomPosition($target_position_id = null)
	{
		$empty_bottom_position = 1;
		$last_position = self::whereMasked(0)->max('position');

		if(isset($last_position)) :			
			$empty_bottom_position = $last_position + 1;

			if(isset($target_position_id)) :
				$last_position_id = self::whereMasked(0)->wherePosition($last_position)->first()->id;
				
				if($last_position_id == $target_position_id) :
					$empty_bottom_position = $last_position;
				endif;	
			endif;	
		endif;
		
		return $empty_bottom_position;	
	}

	public static function getTargetPositionVal($picked_position_id, $target_position_id = null)
	{
		switch($picked_position_id) :
			case 0 : // PLACE AT TOP
				$position_val = self::getEmptyTopPosition($target_position_id);
			break;

			case -1 : // PLACE AT BOTTOM
				$position_val = self::getBottomPosition($target_position_id);
			break;
			//  PLACE AFTER PICKED POSITION
			default : $position_val = self::afterPickedPositionVal($picked_position_id, $target_position_id);
		endswitch;

		return $position_val;
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getPositionAfterNameAttribute()
	{
		return 'AFTER : ' . $this->name;
	}

	public function getPrevPositionIdAttribute()
	{
		$prev = self::whereMasked(0)->where('position', '<', $this->position)->latest('position')->first();
		$prev_position_id = isset($prev) ? $prev->id : 0;

		return $prev_position_id;
	}

	public function getCategoryHtmlAttribute()
	{
		return "<span class='capitalize'>" . snake_to_space($this->category) . "</span>";
	}

	public function getDragAndDropAttribute()
	{
		$input_position = "<input type='hidden' name='positions[]' value='" . $this->id . "'>";
		$reorder_icon = "<i class='mdi mdi-drag-vertical'></i>";

		return $reorder_icon . $input_position;			   
	}
}