<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Training extends Model implements JWTSubject
{

    protected $table = 'trainings';
    public $timestamps = true;
    protected $guarded=[];
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function order()
    {
        return $this->belongsTo('App\Models\OrderTraining', 'order_id');
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
