<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditSetting2 extends Model
{
    public $table = 'credit_setting2';

    public $fillable = [
        'main_category_id',
        'spices_category',
        'credit_per_transaction',
        'sub_categories'
    ];

    public static $rules = [
        'main_category_id' => 'required',
        'spices_category' => 'required',
        'sub_categories' => 'required',
    ];
    public static $messages=[
        "main_category_id.required"=>"Please Select Main Category.",
        "spices_category.required"=>"Please Enter spices category.",
        "sub_categories.required"=>"Please Enter sub categories.",
    ];

    public function main_category(){
        return $this->hasOne(MainCategory::class,'id','main_category_id');
    }
    public function getDisplayCreatedDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }

}
