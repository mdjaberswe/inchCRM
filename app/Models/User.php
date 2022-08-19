<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class User extends Authenticatable
{
    use EntrustUserTrait { restore as private restoreEntrust; }
    use SoftDeletes { restore as private restoreSoftDeletes; }
    use RevisionableTrait;

    protected $table = 'users';
    protected $fillable = ['email', 'status', 'password', 'last_login', 'linked_id', 'linked_type'];
    protected $dates = ['deleted_at', 'last_login'];
    protected $hidden = ['password', 'remember_token'];
    protected $revisionCreationsEnabled = true;
    protected $dontKeepRevisionOf = ['remember_token', 'last_login'];

    public function restore()
    {
        $this->restoreEntrust();
        $this->restoreSoftDeletes();
    }

    public static function boot()
    {
        parent::boot();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOnlyStaff($query)
    {
        $query->where('linked_type', 'staff');
    }

    public function scopeOnlyContact($query)
    {
        $query->where('linked_type', 'contact');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    // relation: morphTo
    public function linked()
    {
        return $this->morphTo()->withTrashed();
    }

    // relation: belongsToMany
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    // relation: hasMany
    public function revisions()
    {
        return $this->hasMany(Revision::class)->withTrashed();
    }
}
