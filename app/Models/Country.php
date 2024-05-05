<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $countryid
 * @property string $country
 * @property Municipality[] $municipalities
 */
class Country extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'countryid';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['country'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function municipalities()
    {
        return $this->hasMany('App\Models\Municipality', 'country_id', 'countryid');
    }
}
