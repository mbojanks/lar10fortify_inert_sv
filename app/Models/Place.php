<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $placeid
 * @property integer $municipality_id
 * @property string $place
 * @property string $placepostnum
 * @property Company[] $companies
 * @property Municipality $municipality
 * @property Stop[] $stops
 * @property User[] $users
 */
class Place extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'placeid';

    /**
     * @var array
     */
    protected $fillable = ['municipality_id', 'place', 'placepostnum'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->hasMany('App\Models\Company', null, 'placeid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipality()
    {
        return $this->belongsTo('App\Models\Municipality', 'municipality_id', 'municipalityid');
    }

    /*public function getMunicipalityAttribute() {
        return MunicipalityResource::collection($this->municipality);
    }*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stops()
    {
        return $this->hasMany('App\Models\Stop', null, 'placeid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\User', 'user_place_id', 'placeid');
    }
}
