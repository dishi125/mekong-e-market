<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CreditManagement
 * @package App\Models
 * @version February 15, 2020, 5:56 am UTC
 *
 * @property integer buyer_id
 * @property integer post_id
 * @property number price
 * @property number bid_price
 * @property integer buyer_fees
 * @property string transaction_id
 * @property string credit_transaction_id
 * @property string transaction_status
 * @property double total_amount
 */
class CreditManagement extends Model
{
    use SoftDeletes;

    public $table = 'credit_managements';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'buyer_id',
        'post_id',
        'bid_price',
        'buyer_fees',
        'credit_transaction_id',
        'total_amount',
        'transaction_status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'buyer_id' => 'integer',
        'post_id' => 'integer',
        'bid_price' => 'double',
        'buyer_fees' => 'double',
        'transaction_id' => 'string',
        'total_amount' => 'double'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /*public function buyer()
    {
        return $this->hasOne(UserProfile::class, 'id', "buyer_id")->where('user_type',4);
    }*/

    public function buyer()
    {
        return $this->hasOne(UserProfile::class, 'id', "buyer_id");
    }

    public function post()
    {
        return $this->belongsTo(Post::class, "post_id", 'id');
    }

    public function getBidAmountAttribute()
    {
        return (float)$this->bid_price * (float)$this->post->qty;
    }

    public function getDisplayStartDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d.m.Y H:i');
    }
    public function posts()
    {
        return $this->hasOne(Post::class, 'id', "post_id");
    }
    public function getRatings(){
        return $this->hasOne(Rating::class,'credit_management_id','id');
    }
}
