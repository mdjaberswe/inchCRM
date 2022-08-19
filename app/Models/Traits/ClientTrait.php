<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait ClientTrait
{
	public function getNameAttribute()
	{
	    return $this->first_name . ' ' . $this->last_name;
	}

	public function getAgeAttribute()
	{
		if(not_null_empty($this->date_of_birth)) :
	    	return Carbon::parse($this->date_of_birth)->age;
	    endif;	

	    return null;
	}
	
	public function getImagePathAttribute()
	{
		if(!is_null($this->image)) :
			return 'app/' . $this->identifier . 's/' . $this->image;
		endif;
		
		return null;	
	}

	public function getAddressAttribute($street = null, $zip = null)
	{
		$address = '';

		if(isset($street) && $street == true) :
			if(not_null_empty($this->street)) :
				$address .= $this->street;
			endif;
		endif;	

		if(not_null_empty($this->city)) :
			$address .= empty($address) ? '' : ', ';
			$address .= $this->city;
		endif;	

		if(not_null_empty($this->state)) :
			$address .= empty($address) ? '' : ', ';
			$address .= $this->state;
		endif;	

		if(not_null_empty($this->country_code)) :
			$address .= empty($address) ? '' : ', ';
			$address .= non_property_checker($this->country, 'ascii_name');
		endif;	

		if(isset($zip) && $zip == true) :
			if(not_null_empty($this->zip)) :
				$address .= empty($address) ? '' : ', ';
				$address .= $this->zip;
			endif;	
		endif;	

		return $address;
	}

	public function getCompressAddressAttribute()
	{
		$address_tooltip = '';
		$full_address = $this->getAddressAttribute(true, true);
		if($full_address != str_limit($this->address, 23, '')) :
			$address_tooltip = "data-toggle='tooltip' data-placement='top' title='" . $full_address . "'";
		endif;	

		$compress_address = "<span $address_tooltip class='sm-txt'><i class='fa fa-map-marker'></i>" . str_limit($this->address, 23) . "</span>";

		return $compress_address;
	}

	public function getAvatarAttribute($photo = null)
	{
		if(isset($this->image) && file_exists(storage_path($this->image_path))) :
			return (string)\Image::make(storage_path($this->image_path))->encode('data-url');
		elseif(isset($photo) && $photo == true) :
			return $this->identifier != 'account' ?  asset('img/avatar.png') : asset('img/company.png');
		else :
			return \Avatar::create($this->name)->toBase64();
		endif;
	}

	public function getProfileHtmlAttribute()
	{
		$tooltip = '';
		$name_css = '';
		if(strlen($this->name) > 20) :
			$tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->name . "'";
			$name_css = 'top-0';
		endif;	
			
		$profile_html = "<a href='" . $this->show_route . "' class='link-type-a sm'>" . 
							"<img src='" . $this->avatar . "'>" . 
							"<p class='$name_css'><span $tooltip>" . str_limit($this->name, 20) . "</span></p>" . 
					  	"</a>";

		return $profile_html;
	}

	public function getIdTypeAttribute()
	{
		return $this->identifier . '-' . $this->id;
	}

	public function getSocialDataAttribute($media = null)
	{
		$data = null;
		$social = is_null($media) ? $this->socialmedia->first() : $this->socialmedia->where('media', $media)->first();

		if(!is_null($social)) :
			$data = json_decode($social->data);
		endif;	

		return $data;
	}

	public function getSocialLinkAttribute($media, $media_url = null)
	{
		$outcome = is_null($media_url) ? 'https://www.' . $media . '.com/' : $media_url;

		$link = non_property_checker($this->getSocialDataAttribute($media), 'link');
		
		if(isset($link)) :
			if(filter_var($link, FILTER_VALIDATE_URL)) :
				$outcome = $link;
			else :	
				$outcome = $outcome . $link;
			endif;	
		endif;	

		return $outcome;
	}

	public function getFacebookAttribute()
	{
		return non_property_checker($this->getSocialDataAttribute('facebook'), 'link');
	}

	public function getTwitterAttribute()
	{
		return non_property_checker($this->getSocialDataAttribute('twitter'), 'link');
	}

	public function getSkypeAttribute()
	{
		return non_property_checker($this->getSocialDataAttribute('skype'), 'link');
	}

	public function getLastSeenDateAttribute()
	{
		// (Not possible now) Last date when lead visited your website
		return null;
	}

	public function getLastContactedTypeAttribute()
	{
		// (Not possible now) Email, Call, SMS, Chat
		return null;
	}

	public function getLastContactedDateAttribute()
	{
		// (Not possible now) nearest before today
		return null;
	}

	public function getItemTotalAttribute()
	{
		return number_format($this->items->sum('total'), 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getCartItemsAttribute()
	{
		$cart_items = $this->items->pluck('pivot', 'id')->toArray();
		$cart_items = array_forget_all($cart_items, ['linked_id', 'linked_type', 'item_id']);
		return $cart_items;
	}

	public function getWondealsAttribute()
	{
		$won_stage_id = \App\Models\DealStage::getCategoryIds('closed_won');
		return $this->deals->whereIn('deal_stage_id', $won_stage_id);
	}

	public function getLostdealsAttribute()
	{
		$lost_stage_id = \App\Models\DealStage::getCategoryIds('closed_lost');
		return $this->deals->whereIn('deal_stage_id', $lost_stage_id);
	}

	public function getDealConversionAttribute()
	{
		if($this->deals()->count()) :
			$percentage = ($this->wondeals->count() / $this->deals->count()) * 100;
			$percentage = number_format($percentage, 2, '.', '') + 0;
			return $percentage;
		endif;
		
		return 0;	
	}

	public function getDealConversionHtmlAttribute()
	{
		$html = $this->deal_conversion . '<i>%</i>';
		return $html;
	}

	public function getDealConversionCssAttribute()
	{
		if($this->deal_conversion >= 0 && $this->deal_conversion <= 30) :
			return 'cold';
		elseif($this->deal_conversion > 30 && $this->deal_conversion <= 70) :
			return 'warm';
		elseif($this->deal_conversion > 70 && $this->deal_conversion <= 100) :
			return 'hot';
		else :
			return null;
		endif;	
	}

	public function getOpendealsAttribute()
	{
		$open_stage_id = \App\Models\DealStage::getCategoryIds('open');
		return $this->deals->whereIn('deal_stage_id', $open_stage_id);
	}

	public function getOpenDealsAmountHtmlAttribute()
	{
		return $this->amountTotalHtml('opendeals', 'amount');
	}
}	