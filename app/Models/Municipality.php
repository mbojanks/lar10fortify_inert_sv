<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $municipalityid
 * @property integer $country_id
 * @property string $municipality
 * @property Country $country
 * @property Place[] $places
 */
class Municipality extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'municipalityid';

    /**
     * @var array
     */
    protected $fillable = ['country_id', 'municipality'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id', 'countryid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function places()
    {
        return $this->hasMany('App\Models\Place', 'municipality_id', 'municipalityid');
    }
}
