<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $roleid
 * @property string $rolename
 * @property Rideworker[] $rideworkers
 * @property Cap[] $rolecaps
 * @property User[] $users
 */
class Role extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'roleid';

    /**
     * @var array
     */
    protected $fillable = ['rolename'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rideworkers()
    {
        return $this->hasMany('App\Models\Rideworker', null, 'roleid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function caps()
    {
        return $this->belongsToMany('App\Models\Cap' /*, null, 'roleid' */);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\User', null, 'roleid');
    }
}
