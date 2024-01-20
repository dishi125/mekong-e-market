<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Subscription
 * @package App\Models
 * @version February 6, 2020, 4:33 am UTC
 *
 * @property string package_name
 * @property number price
 * @property string description
 * @property string credit
 * @property string security_deposit
 * @property ineger sub_user
 * @property number bidding
 * @property integer status
 */
class Subscription extends Model
{
    use SoftDeletes;

    public $table = 'subscriptions';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'package_name',
        'price',
        'description',
        'credit',
        'security_deposit',
        'sub_user',
        'bidding',
        'package_type',
        'status',
        'duration'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'package_name' => 'string',
        'price' => 'double',
        'description' => 'string',
        'credit' => 'string',
        'security_deposit' => 'string',
        'bidding' => 'double',
        'package_type' => 'integer',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'package_name' => 'required',
        'price' => 'required',
        'description' => 'required'
    ];

    public function getDisplayStartDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }

    public function getMySubscription(){
        return $this->hasOne(MySubscription::class,'subscription_id','id');
    }
}
