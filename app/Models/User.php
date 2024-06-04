<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Role;
use App\Http\Resources\CapResource;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable, FieldsWithFilesObservable;

    /**
     * BM> files for the fields to be in synch
     */
    public $fieldsWithFile = [
        'userimg' => ['filePath' => 'public/users', 'fileDriver' => 'public', 'fileRequired' => false, 'fieldext_deletefile' => '_filedelete']
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'useraddress',
        'phone',
        'user_place_id',
        'userimg',
        'role_id'
    ];

    /**
     * Should it be here?
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function depworkers()
    {
        return $this->hasMany('App\Models\Depworker');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }

    /**
     * Role data
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function caps() {
        $caps = $this->role->caps;
        $allCaps = CapResource::collection(Cap::all());
        $userCaps = [];
        foreach($allCaps as $cap) {
            $userCaps[$cap] = false;
        }
        foreach($caps as $c) {
            $userCaps[$c] = true;
            if ($c->capscope) {
                $userCaps[$c] = $c->capscope;
            }
        }
        return $userCaps;
    }

    public function place()
    {
        return $this->belongsTo(Place::class,'user_place_id','placeid');
    }

    public function getCapsAttribute()
    { // so capabilities could be called as dynamic property, even within (json) resources
        return $this->caps();
    }
    /**
     * Should it be here for file-dependant fields?
     */
    /* protected $appends = [
        'userimg'
    ]; */
}
