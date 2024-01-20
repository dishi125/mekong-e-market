<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favourite extends Model
{
    use SoftDeletes;
    public $table='favourite_posts';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        "user_type",
        "user_profile_id",
        "post_id",
    ];
}
