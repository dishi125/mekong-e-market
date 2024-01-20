<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    public $table = 'products';


    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_profile_id',
        'main_category_id',
        'sub_category_id',
        'species_id',
        'other_species',
        'imported',
        'grade_id',
        'url',
        'pickup_point',
        'description',
        'fast_buy',
        'fast_buy_price',
        'is_mygap',
        'is_organic',
        'repost',
        'end_time',
        'status',
    ];

    public function user()
    {
        return $this->hasOne(UserProfile::class, 'id', "user_profile_id");
    }
    public function maincategory()
    {
        return $this->hasOne(MainCategory::class, 'id', "main_category_id");
    }
    public function subcategory()
    {
        return $this->hasOne(SubCategory::class, 'id', "sub_category_id");
    }
    public function species()
    {
        return $this->hasOne(Specie::class, 'id', "species_id");
    }
    public function area()
    {
        return $this->hasOne(Area::class, 'id', "area_id");
    }
    public function state()
    {
        return $this->hasOne(State::class, 'id', "state_id");
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', "id");
    }

    public function product_image()
    {
        return $this->hasOne(ProductImage::class, 'product_id', "id");
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'product_id', "id");
    }
    public function getGrade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', "id")->withTrashed();
    }
    public function getGradeAttribute()
    {
        return "".$this->getGrade->name;
    }
    public function getAddressAttribute()
    {
        $address = '';
        if($this->area){
            $address .= $this->area->name;
        }
        if($this->state){
            $address .= ', '.$this->state->name;
        }
        return trim($address,',');
    }

    public function getAppPostImagesAttribute()
    {

        $images = array();
        foreach ($this->images as $image){
            array_push($images,array('image' => $image['image']));
        }
        return $images;
    }
}
