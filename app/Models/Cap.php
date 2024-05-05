<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $capid
 * @property string $cap
 * @property Rolecap[] $rolecaps
 */
class Cap extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'capid';

    /**
     * @var array
     */
    protected $fillable = ['cap'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rolecaps()
    {
        return $this->hasMany('App\Models\Rolecap', null, 'capid');
    }
}
