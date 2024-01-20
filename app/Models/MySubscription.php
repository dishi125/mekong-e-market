<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MySubscription extends Model
{
    use SoftDeletes;

    public $table = 'my_subscriptions';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'subscription_id',
        'user_profile_id',
        'start_date',
        'end_date',
        'transaction_id',
        'security_deposit_transaction_id',
        'credit_transaction_id',
        'is_running',
        'status',
        ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'=>'integer',
        'subscription_id'=>'integer',
        'user_profile_id'=>'integer',
        'start_date'=>'timestamp',
        'end_date'=>'timestamp',
        'transaction_id'=>'string',
        'security_deposit_transaction_id'=>'integer',
        'credit_transaction_id'=>'integer',
        'is_running'=>'integer',
        'status'=>'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    public function user()
    {
        return $this->hasOne(UserProfile::class, 'id', "user_profile_id");
    }

    public function subscription_package()
    {
        return $this->hasOne(Subscription::class, 'id', "subscription_id");
    }

    public function credit_transaction()
    {
        return $this->hasOne(CreditTransaction::class, 'id', "credit_transaction_id");
    }

    public function displayDate($date)
    {
        return \Carbon\Carbon::parse($date, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }
}
