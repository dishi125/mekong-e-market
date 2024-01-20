<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Rating extends Model
{
    use SoftDeletes;

    public $table = 'ratings';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'rate',
        'review',
        'buyer_id',
        'seller_id',
        'credit_management_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'rate' => 'double',
        'review' => 'text',
        'buyer_id' => 'integer',
        'seller_id' => 'integer'
    ];

    public function user()
    {
        return $this->hasOne(UserProfile::class,"id","buyer_id");
    }
    public function buyer()
    {
        return $this->hasOne(UserProfile::class,"id","buyer_id");
    }
    public function seller()
    {
        return $this->hasOne(UserProfile::class,"id","seller_id");
    }
    public function getDisplayDateAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d.m.Y \a\\t h.ia');
    }
}
