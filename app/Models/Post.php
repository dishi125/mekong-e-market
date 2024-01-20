<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use SoftDeletes;
    public $table = 'posts';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'product_id',
        'starting_price',
        'second_price',
        'third_price',
        'fourth_price',
        'ended_price',
        'qty',
        'weight_unit_id',
        'date_time',
        'frame',
        'credit_fee',
    ];

    public function product()
    {
        return $this->hasOne(Product::class,'id',"product_id");
    }
    public function creditmanagement()
    {
        return $this->hasOne(CreditManagement::class, 'post_id', "id");
    }
    public function weightunit()
    {
        return $this->hasOne(WeightUnit::class, 'id', "weight_unit_id");
    }
    public function getDisplayCreatedDateTimeAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d.m.Y \a\\t h.ia');
    }
    public function getDisplayDateTimeAttribute()
    {
        return \Carbon\Carbon::parse($this->date_time, "UTC")->setTimezone(env('TIME_ZONE'))->format('d.m.Y \a\\t h.ia');
    }
    public function getDisplayEndDateTime($date_time)
    {
        return \Carbon\Carbon::parse($date_time, "UTC")->setTimezone(env('TIME_ZONE'))->format('d.m.Y \a\\t h.ia');
    }
    public function getUnit()
    {
        return $this->belongsTo(WeightUnit::class, 'weight_unit_id', "id")->withTrashed();
    }
    public function getUnitAttribute() {
        return $this->getUnit->unit;
    }

    public function getWeightAttribute(){
        return $this->qty.' '.$this->unit;
    }
    public function allFavouritePost() {
        return $this->hasMany(Favourite::class, 'post_id', "id");
    }
    public function favouritePost(){//get favourite post for buyer
        return $this->hasMany(Favourite::class, 'post_id', "id")->where('user_type',0);
    }
    public function favouriteSellerPost(){//get favourite post for buyer
        return $this->hasMany(Favourite::class, 'post_id', "id")->where('user_type',1);
    }
    public function getPostRateAttribute() {
        //for re-post including old post review
        $product_id = $this->product->id;
        $ratings = Post::select('ratings.id','ratings.rate', 'ratings.review')
                    ->join('credit_managements','posts.id','=','credit_managements.post_id')
                    ->join('ratings','credit_managements.id','=','ratings.credit_management_id')
                    ->where('posts.product_id',$product_id)
                    ->whereNull('ratings.deleted_at')
                    ->whereNull('credit_managements.deleted_at');

        $avg_rating = $ratings->avg('rate');
        $rate = $avg_rating ? $avg_rating : 0;

        $review = $ratings->whereNotNull('ratings.review')->where('ratings.review','!=','')->count();
        return array('rate' => round($rate,2), 'review' => $review);
    }

    public function getBuyerDetailAttribute(){

        $buyer_detail = array();
        $creditmanagement = $this->creditmanagement;
        if($creditmanagement){
            $buyer_detail['id'] = $creditmanagement->buyer->id;
            $buyer_detail['credit_management_id'] = $creditmanagement->id;
            $buyer_detail['bid_price'] = $creditmanagement->bid_price;
            $buyer_detail['buyer_name'] = $creditmanagement->buyer->name;
            $buyer_detail['profile_pic'] = $creditmanagement->buyer->profile_pic;
            $buyer_detail['transaction_status'] = $creditmanagement->transaction_status;
            $buyer_detail['created_at'] = $creditmanagement->created_at;
            $buyer_detail['purchase_date'] = $creditmanagement->created_at;
        }
        return $buyer_detail;
    }
}
