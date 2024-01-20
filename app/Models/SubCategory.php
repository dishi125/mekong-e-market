<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SubCategory
 * @package App\Models
 * @version January 31, 2020, 11:04 am UTC
 *
 * @property integer main_category_id
 * @property string name
 * @property integer status
 */
class SubCategory extends Model
{
    use SoftDeletes;

    public $table = 'sub_categories';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'main_category_id',
        'name',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'main_category_id' => 'integer',
        'name' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'main_category_id' => 'required',
        'name' => 'required'
    ];
    public static $messages=[
        "main_category_id.required"=>"Select Main Category",
        "name.required"=>"Please Enter Sub Category ."
    ];
    public function species_dropdown()
    {
        return $this->hasMany(Specie::class,"sub_category_id",'id')->where('status',1);
    }
    public function species()
    {
        return $this->hasMany(Specie::class,"sub_category_id",'id');
    }
    public function maincategory()
    {
        return $this->hasOne(MainCategory::class,"id","main_category_id");
    }
    public function getDisplayStartDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }
}
