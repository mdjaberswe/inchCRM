<?php

namespace App\Models;

use App\Models\BaseModel;

class Country extends BaseModel
{
	protected $table = 'countries';
	public $timestamps = false;
	protected $fillable = ['code', 'iso3', 'iso_numeric', 'fips', 'name', 'ascii_name', 'capital', 'currency_code', 'phone'];

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function staffs()
	{
		return $this->hasMany(Staff::class, 'country_code', 'code')->withTrashed();
	}

	public function leads()
	{
		return $this->hasMany(Lead::class, 'country_code', 'code');
	}

	public function accounts()
	{
		return $this->hasMany(Account::class, 'country_code', 'code');
	}
}