<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    public $table = 'product_images';

    protected $fillable=[
        'image',
        'product_id'
    ];

    public function getImageAttribute($value){
        $image = '';
        if($value){
            $image = url('public/' . $value);
        }
        return $image;
    }

    public function getLocalImageAttribute(){
        $image = null;
        if($this->image != ''){
            $image = str_replace(env('APP_URL'),'',$this->image);
        }
        return $image;
    }
}
