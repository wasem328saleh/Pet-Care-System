<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class OrderAdoption extends Model implements JWTSubject
{

    protected $table = 'orders_adoption';
    public $timestamps = true;
    protected $guarded=[];
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function adoption()
    {
        return $this->hasOne('App\Models\Adoption', 'order_id');
    }
// Rest omitted for brevity
    /** * Get the identifier that will be stored in the subject claim of the JWT.
     * * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /** * Return a key value array, containing any custom claims to be added to the JWT. * * @return array */
    public function getJWTCustomClaims() { return []; }
}
