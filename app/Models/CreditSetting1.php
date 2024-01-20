<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MainCategory;
use Illuminate\Database\Eloquent\SoftDeletes;


class CreditSetting1 extends Model
{
    //    use SoftDeletes;

    public $table = 'credit_setting1';


//    protected $dates = ['deleted_at'];


    public $fillable = [
        'main_category_id',
        'hot_species_credit',
        'mid_species_credit',
        'low_species_credit'
    ];

    public static $rules = [
        'main_category_id' => 'required',
        'hot_species_credit' => 'required',
        'mid_species_credit' => 'required',
        'low_species_credit' => 'required',
    ];
    public static $messages=[
        "main_category_id.required"=>"Please Select Main Category.",
        "hot_species_credit.required"=>"Please Enter Credit per transaction for hot spices.",
        "mid_species_credit.required"=>"Please Enter Credit per transaction for mid spices.",
        "low_species_credit.required"=>"Please Enter Credit per transaction for low spices.",
    ];

    public function main_category(){
        return $this->hasOne(MainCategory::class,'id','main_category_id');
    }

    public function getDisplayCreatedDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }
}
