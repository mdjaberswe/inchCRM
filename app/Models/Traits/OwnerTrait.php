<?php

namespace App\Models\Traits;

trait OwnerTrait
{
	public function getAuthCanViewAttribute()
	{
		return $this->authCan('view');
	}

	public function getAuthCanEditAttribute()
	{
		return $this->authCan('edit');
	}

	public function getAuthCanDeleteAttribute()
	{
		return $this->authCan('delete');
	}

	public function getAuthCanChangeOwnerAttribute()
	{
		$change_owner_permission = 'change_owner.' . $this->identifier;

		if($this->authCan('edit') && permit($change_owner_permission)) :
			return true;
		endif;	

		return false;
	}	

	public function getAuthCanSendEmailAttribute()
	{
		$send_email_permission = 'send_email.' . $this->identifier;

		if($this->authCan('view') && permit($send_email_permission)) :
			return true;
		endif;	

		return false;
	}	

	public function getAuthCanSendSmsAttribute()
	{
		$send_sms_permission = 'send_SMS.' . $this->identifier;

		if($this->authCan('view') && permit($send_sms_permission)) :
			return true;
		endif;	

		return false;
	}	

	public function authCan($action)
	{
		$permission = $this->permission . '.' . $action;
		$can_permission = 'can_' . $action;
		$owner = $this->identifier . '_owner';		
		$is_auth_permit = permit($permission);
		$access = ($action == 'view') ? 'public' : 'public_rwd';

		if(!$is_auth_permit) : return false; endif;
		if(auth_staff()->admin) : return true; endif;

		if(($this->access == $access) && $is_auth_permit) : return true; endif;

		if(($this->$owner == auth_staff()->id) && $is_auth_permit) : return true; endif;

		$is_creator = (non_property_checker($this->createdBy(), 'linked_id') == auth_staff()->id);
		if($is_creator && $is_auth_permit) : return true; endif;

		$is_auth_allowed = in_array(auth_staff()->id, $this->allowedstaffs->pluck('staff_id')->toArray()) ? $this->allowedstaffs()->whereStaff_id(auth_staff()->id)->first()->$can_permission : false;
		if($is_auth_allowed && $is_auth_permit) : return true; endif;

		return false;
	}

	public static function getAuthViewData()
	{
		$model = with(new static);
		$table = $model->table;
		$identifier = $model->identifier;
		$permission = $model->permission . '.' . 'view';
		$join_id =  $table . '.id';
		$owner = $identifier . '_owner';		
		$is_auth_permit = permit($permission);	

		if(!$is_auth_permit) :
			return self::whereNull("$table.id");
		endif;

		if(auth_staff()->admin) : 
			return self::where("$table.id", ">", 0);
		endif;

		$ids = self::whereAccess('public')
					->orWhere('access', 'public_rwd')
					->orWhere($owner, auth_staff()->id)
					->leftjoin('revisions', $join_id, '=', 'revisions.revisionable_id')
					->leftjoin('allowed_staffs', $join_id, '=', 'allowed_staffs.linked_id')								
					->orWhere(function($query) use ($identifier)
					{
						$query->whereRevisionable_type($identifier)
							  ->whereUser_id(auth()->user()->id)
							  ->wherekey('created_at');
					})		
					->orWhere(function($query) use ($identifier)
					{
						$query->where('allowed_staffs.linked_type', $identifier)
							  ->where('allowed_staffs.staff_id', auth_staff()->id)
							  ->where('allowed_staffs.can_view', 1);
					})
					->select("$table.*")
					->groupBy($join_id)
					->pluck("$table.id");

		$data = self::whereIn($join_id, $ids);			

		return $data;			
	}

	public function getPrevRecordAttribute()
	{
		if($this->order_type == 'desc') :
			return self::getAuthViewData()->where('id', '>', $this->id)->first();
		endif;	

		return self::getAuthViewData()->where('id', '<', $this->id)->latest('id')->first();
	}

	public function getNextRecordAttribute()
	{
		if($this->order_type == 'desc') :
			return self::getAuthViewData()->where('id', '<', $this->id)->latest('id')->first();
		endif;	

		return self::getAuthViewData()->where('id', '>', $this->id)->first();
	}
}