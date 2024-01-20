<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications_api extends Model
{
    public $table = 'notifications_api';

    public $fillable = [
        'from_user_id',
        'to_user_id',
        'rating_id',
        'post_id',
        'bid_price',
        'fast_buy',
        'desc'
    ];

    protected $casts = [
        'id' => 'integer',
        'from_user_id' => 'integer',
        'to_user_id' => 'integer',
        'rating_id' => 'integer',
        'post_id' => 'integer',
        'bid_price' => 'integer',
        'desc' => 'string'
    ];

    public function user()
    {
        return $this->hasOne(UserProfile::class,'id',"from_user_id");
    }
    public function post()
    {
        return $this->hasOne(Post::class,'id',"post_id");
    }
    public function rating()
    {
        return $this->hasOne(Rating::class,'id',"rating_id");
    }
}
